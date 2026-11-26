<?php

declare(strict_types=1);

/**
 * ارکستراسیون Worker — الگوبرداری‌شده از NousResearch/hermes-agent (MIT)
 *
 * پیاده‌سازی PHP خالص پنج بدیههٔ ارکستراسیون:
 *   delegate {spawn,list,steer,stop,continue}
 *   kanban   {claim,heartbeat,complete,block,request_review,request_changes,unblock}
 *   review   (بازبین مستقل با امتیاز کامل — SoD-5)
 *   moa      (Mixture-of-Agents — تجمیع، نه رأی‌گیری)
 *   estop    (sentinel سراسری: کار نو متوقف، در حال اجرا بخشیده)
 *
 * به‌علاوه: IterationBudget (consume/refund)، DenialCircuitBreaker (۳ رد → stop)،
 * Process handoff (سقف ۳/فرزند)، capability token (HMAC).
 *
 * مدل سازمانی (۷۹ نقش) دست‌نخورده می‌ماند — این کد، فعل‌های مجاز فراتر از
 * اجرای وظیفه را پیاده می‌کند، نه نقش جدید.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 * @pattern-source NousResearch/hermes-agent (MIT) — agent/subagent_lifecycle.py
 */

/**
 * درخواست spawn — معادل SubagentLaunchRequest در hermes (readonly value object).
 */
final class WorkerLaunchRequest
{
    public readonly string $role;
    public readonly string $contractVersion;

    /**
     * @param array<int,string> $allowedToolsets زیرمجموعهٔ toolsets والد
     * @param array<int,string> $blockedTools    ابزارهای ممنوع (مثلاً delegate برای leaf)
     * @param array<string,mixed> $metadata
     */
    public function __construct(
        public readonly string $goal,
        public readonly string $context,
        string $role,
        public readonly array $allowedToolsets,
        public readonly array $blockedTools,
        public readonly string $correlationId,
        public readonly array $metadata,
        public readonly int $timeoutSeconds,
        string $contractVersion = '0.0.2',
    ) {
        if (!in_array($role, ['leaf', 'orchestrator'], true)) {
            throw new InvalidArgumentException("role must be leaf|orchestrator, got {$role}");
        }
        $this->role = $role;
        $this->contractVersion = $contractVersion;
    }
}

/**
 * بودجهٔ تکرار منطقی (نه زمان) — consume/refund.
 * والد = ۵۰۰ | فرزند = ۵۰ (hermes IterationBudget).
 */
final class IterationBudget
{
    private int $remaining;

    public function __construct(
        private readonly int $cap,
    ) {
        $this->remaining = $cap;
    }

    public static function parent(): self
    {
        return new self(500);
    }

    public static function child(): self
    {
        return new self(50);
    }

    public function consume(int $n = 1): void
    {
        if ($n <= 0) {
            return;
        }
        $this->remaining -= $n;
        if ($this->remaining < 0) {
            $this->remaining = 0;
            throw new RuntimeException('IterationBudget exhausted');
        }
    }

    /** وظایف بازگشت‌پذیر (verify-only) هزینه را بازپرداخت می‌کنند. */
    public function refund(int $n = 1): void
    {
        if ($n <= 0) {
            return;
        }
        $this->remaining = min($this->cap, $this->remaining + $n);
    }

    public function remaining(): int
    {
        return $this->remaining;
    }

    public function exhausted(): bool
    {
        return $this->remaining <= 0;
    }
}

/**
 * Circuit breaker — ۳ رد متوالی → hard-stop (history-invariant).
 */
final class DenialCircuitBreaker
{
    private int $consecutiveDenies = 0;
    private bool $tripped = false;
    private const THRESHOLD = 3;

    public function recordDenial(): void
    {
        if ($this->tripped) {
            return;
        }
        $this->consecutiveDenies++;
        if ($this->consecutiveDenies >= self::THRESHOLD) {
            $this->tripped = true;
        }
    }

    public function recordApproval(): void
    {
        $this->consecutiveDenies = 0;
    }

    public function tripped(): bool
    {
        return $this->tripped;
    }

    public function reset(): void
    {
        // history-invariant: فقط در session جدید؛ نه برای دور زدن در همان session.
        $this->consecutiveDenies = 0;
        $this->tripped = false;
    }
}

/**
 * حالت‌های Worker (hermes SubagentState).
 */
final class WorkerState
{
    public const PENDING = 'PENDING';
    public const STARTING = 'STARTING';
    public const RUNNING = 'RUNNING';
    public const SUCCEEDED = 'SUCCEEDED';
    public const FAILED = 'FAILED';
    public const INTERRUPTED = 'INTERRUPTED';
    public const CANCEL_REQUESTED = 'CANCEL_REQUESTED';
    public const CANCELLED = 'CANCELLED';

    public static function isTerminal(string $state): bool
    {
        return in_array($state, [self::SUCCEEDED, self::FAILED, self::INTERRUPTED, self::CANCELLED], true);
    }
}

/**
 * رکورد یک Worker در حال اجرا/پایان‌یافته.
 */
final class WorkerRecord
{
    public string $state = WorkerState::PENDING;
    public float $spawnedAt;
    public ?float $terminatedAt = null;
    public ?string $summary = null;
    /** @var array<int,string> */
    public array $steerQueue = [];
    public int $handoffsGiven = 0;
    public IterationBudget $budget;

    /** @param array<int,string> $allowedToolsets */
    public function __construct(
        public readonly string $workerId,
        public readonly WorkerLaunchRequest $request,
        public readonly string $capabilityToken,
    ) {
        $this->spawnedAt = microtime(true);
        $this->budget = $request->role === 'leaf' ? IterationBudget::child() : IterationBudget::parent();
    }
}

/**
 * ارکستراسیون Worker — هستهٔ پنج بدیهه.
 *
 * ذخیره‌سازی: در حافظه (آرایه) — sandbox ریست می‌شود، state پایدار نداریم
 * (محدودیت مستندشده در docs/org/12-worker-orchestration.md بخش ۸).
 */
final class WorkerOrchestrator
{
    /** @var array<string,WorkerRecord> */
    private array $workers = [];
    private int $spawnCounter = 0;
    private bool $estop = false; // sentinel: کار نو متوقف، در حال اجرا بخشیده
    private readonly string $runKey;
    public readonly DenialCircuitBreaker $denials;

    public function __construct()
    {
        // کلید per-run (تصادفی) — توکنcapability بین runs ماندگار نیست.
        $this->runKey = bin2hex(random_bytes(16));
        $this->denials = new DenialCircuitBreaker();
    }

    // ===== delegate =====

    /** spawn: فرزند با scope محدودتر. fails closed اگر estop فعال باشد. */
    public function spawn(WorkerLaunchRequest $req): WorkerRecord
    {
        if ($this->estop) {
            throw new RuntimeException('ESTOP active — no new workers (in-flight spared)');
        }
        // privilege containment: allowedToolsets باید زیرمجموعهٔ… والد باشد.
        // در این پیاده‌سازی تک‌مرحله‌ای، والد همان orchestrator است؛
        // اعمال سخت‌گیرانهٔ containment در پیاده‌سازی چندسطحی واگذار شده به caller.
        $this->spawnCounter++;
        $workerId = sprintf('W-%d-%s', $this->spawnCounter, $req->correlationId);
        $token = $this->issueCapabilityToken($workerId, $req->contractVersion);
        $rec = new WorkerRecord($workerId, $req, $token);
        $rec->state = WorkerState::STARTING;
        $this->workers[$workerId] = $rec;
        return $rec;
    }

    /** @return array<int,WorkerRecord> */
    public function list(): array
    {
        return array_values(array_filter(
            $this->workers,
            static fn(WorkerRecord $r): bool => !WorkerState::isTerminal($r->state)
        ));
    }

    /** steer: اصلاح مسیر میان‌اجرا — هرگز فراخوانی جاری را قطع نمی‌کند. */
    public function steer(string $workerId, string $message): void
    {
        $rec = $this->mustGet($workerId);
        if ($rec->state !== WorkerState::RUNNING && $rec->state !== WorkerState::STARTING) {
            throw new RuntimeException("cannot steer non-running worker {$workerId}");
        }
        $rec->steerQueue[] = $message; // در turn بعدی خوانده می‌شود
    }

    /** stop: درخواست لغو (CANCEL_REQUESTED). */
    public function stop(string $workerId): void
    {
        $rec = $this->mustGet($workerId);
        if (WorkerState::isTerminal($rec->state)) {
            return; // idempotent
        }
        $rec->state = WorkerState::CANCEL_REQUESTED;
    }

    /** continue: از سرگیری Worker تمام‌شده (snapshot < ۳۶۰۰s). */
    public function continueWorker(string $workerId, string $message): void
    {
        $rec = $this->mustGet($workerId);
        if ($rec->terminatedAt === null || (microtime(true) - $rec->terminatedAt) > 3600.0) {
            throw new RuntimeException("worker {$workerId} snapshot expired or still running");
        }
        $rec->state = WorkerState::RUNNING;
        $rec->terminatedAt = null;
        $rec->steerQueue[] = $message;
    }

    // ===== kanban verbs =====

    public function kanbanClaim(string $workerId): void
    {
        $rec = $this->mustGet($workerId);
        if ($rec->state !== WorkerState::STARTING && $rec->state !== WorkerState::PENDING) {
            throw new RuntimeException("claim requires PENDING/STARTING, got {$rec->state}");
        }
        $rec->state = WorkerState::RUNNING;
    }

    public function kanbanHeartbeat(string $workerId): void
    {
        $rec = $this->mustGet($workerId);
        if ($rec->state !== WorkerState::RUNNING) {
            throw new RuntimeException("heartbeat requires RUNNING, got {$rec->state}");
        }
        // noop — فقط تأیید زنده بودن
    }

    /** complete فقط پس از acceptance (verdict=continue) مجاز است. */
    public function kanbanComplete(string $workerId, string $summary, bool $accepted): void
    {
        $rec = $this->mustGet($workerId);
        if (!$accepted) {
            throw new RuntimeException("complete blocked — acceptance_gate verdict=blocked");
        }
        $rec->summary = $summary;
        $rec->state = WorkerState::SUCCEEDED;
        $rec->terminatedAt = microtime(true);
        $this->denials->recordApproval();
    }

    public function kanbanBlock(string $workerId): void
    {
        $rec = $this->mustGet($workerId);
        $rec->state = WorkerState::INTERRUPTED; // مسدود، قابل رفع
    }

    public function kanbanRequestReview(string $workerId): string
    {
        // spawn یک بازبین مستقل (SoD-5) — بازبین ad-hoc با scope تک‌وظیفه‌ای.
        return $this->spawn(new WorkerLaunchRequest(
            goal: "review:{$workerId}",
            context: $this->workers[$workerId]->request->goal,
            role: 'leaf',
            allowedToolsets: ['read-all'],
            blockedTools: ['delegate'],
            correlationId: "rev-{$workerId}",
            metadata: ['reviews' => $workerId],
            timeoutSeconds: 300,
        ))->workerId;
    }

    public function kanbanRequestChanges(string $workerId, string $note): void
    {
        $rec = $this->mustGet($workerId);
        $rec->steerQueue[] = "request_changes: {$note}";
        $this->denials->recordDenial();
        if ($this->denials->tripped()) {
            // ۳ رد متوالی → hard-stop کارت، مشورت شورا اجباری.
            $rec->state = WorkerState::INTERRUPTED;
        }
    }

    public function kanbanUnblock(string $workerId): void
    {
        $rec = $this->mustGet($workerId);
        if ($rec->state === WorkerState::INTERRUPTED) {
            $rec->state = WorkerState::RUNNING;
        }
    }

    // ===== review (independent full-privilege) =====

    /**
     * بازبین مستقل با امتیاز کامل — نتیجه ناهمگام.
     * @param callable(string):string $reviewerFn تابع بازبینی (workerId → verdict)
     */
    public function review(string $goal, string $context, callable $reviewerFn): string
    {
        $rev = $this->spawn(new WorkerLaunchRequest(
            goal: $goal,
            context: $context,
            role: 'leaf',
            allowedToolsets: ['read-all'],
            blockedTools: ['delegate'],
            correlationId: 'review-' . bin2hex(random_bytes(4)),
            metadata: ['independent' => true],
            timeoutSeconds: 600,
        ));
        $this->kanbanClaim($rev->workerId);
        $verdict = $reviewerFn($rev->workerId); // 'continue' | 'blocked'
        $this->kanbanComplete($rev->workerId, "verdict={$verdict}", $verdict === 'continue');
        return $verdict;
    }

    // ===== moa (Mixture-of-Agents — تجمیع، نه رأی‌گیری) =====

    /**
     * @param array<int,callable(string):string> $advisors   مشاوران موازی
     * @param callable(array<int,string>):string $aggregator تجمیع‌کننده
     */
    public function moa(string $topic, array $advisors, callable $aggregator): string
    {
        // اجرای متوالی (PHP تک‌نخی؛ موازی‌سازی واقعی نیاز به pcntl/Fiber جداگانه دارد)
        $opinions = [];
        foreach ($advisors as $i => $advisor) {
            $opinions[] = $advisor($topic);
        }
        return $aggregator($opinions);
    }

    // ===== estop =====

    /** sentinel سراسری — کار نو متوقف، در حال اجرا بخشیده. */
    public function estop(): void
    {
        $this->estop = true;
    }

    public function estopClear(): void
    {
        $this->estop = false;
    }

    public function estopActive(): bool
    {
        return $this->estop;
    }

    // ===== process handoff (سقف ۳/فرزند) =====

    /** انتقال فرایند در حال اجرا با یادداشت rationale اجباری. */
    public function handoff(string $fromId, string $toId, string $rationale): void
    {
        $from = $this->mustGet($fromId);
        $to = $this->mustGet($toId);
        if ($from->handoffsGiven >= 3) {
            throw new RuntimeException("handoff quota exhausted for {$fromId} (cap 3)");
        }
        if ($rationale === '') {
            throw new InvalidArgumentException('handoff rationale (data note) is mandatory');
        }
        $from->handoffsGiven++;
        $to->steerQueue[] = "handoff-from:{$fromId} rationale:{$rationale}";
    }

    // ===== capability token (HMAC) =====

    private function issueCapabilityToken(string $workerId, string $contractVersion): string
    {
        $payload = "{$workerId}:{$contractVersion}";
        $sig = hash_hmac('sha256', $payload, $this->runKey);
        return "hmac:{$contractVersion}:{$sig}";
    }

    public function verifyCapabilityToken(string $workerId, string $token, string $contractVersion): bool
    {
        $expected = $this->issueCapabilityToken($workerId, $contractVersion);
        return hash_equals($expected, $token);
    }

    // ===== helpers =====

    private function mustGet(string $workerId): WorkerRecord
    {
        $rec = $this->workers[$workerId] ?? null;
        if ($rec === null) {
            throw new InvalidArgumentException("unknown worker {$workerId}");
        }
        return $rec;
    }

    public function getRecord(string $workerId): ?WorkerRecord
    {
        return $this->workers[$workerId] ?? null;
    }

    public function forceTerminate(string $workerId, string $state, string $summary): void
    {
        $rec = $this->mustGet($workerId);
        $rec->state = $state;
        $rec->summary = $summary;
        $rec->terminatedAt = microtime(true);
    }
}
