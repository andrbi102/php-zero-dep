<?php

declare(strict_types=1);

/**
 * تست دود — همه اسکلت‌ها بارگذاری و رفتار پایه بررسی می‌شود.
 * اجرا: php tests/smoke-test.php   (خروجی 0 یعنی PASS)
 *
 * @package PhpZeroDep\Tests
 * @version 0.0.2
 */
$root = dirname(__DIR__) . '/scaffolds';
$failed = 0;

function check(string $label, bool $condition): void
{
    global $failed;
    echo $condition ? "  PASS  {$label}\n" : "  FAIL  {$label}\n";
    if (!$condition) {
        $failed++;
    }
}

$scaffolds = [
    'autoloader.php', 'router.php', 'container.php', 'database.php',
    'session.php', 'csrf.php', 'validator.php', 'view.php', 'logger.php',
    'error-handler.php', 'rate-limiter.php', 'cache.php', 'http-client.php',
    'crypto.php', 'graph.php', 'event-dispatcher.php', 'event-store.php',
    'command-bus.php', 'query-bus.php', 'pipeline.php', 'hmvc-dispatcher.php',
    'serverless-handler.php', 'mcp-server.php', 'tracing.php', 'db-migrate.php',
    // v0.0.2 — الگوبرداری‌شده از مخازن مرجع
    'worker-orchestrator.php', 'humanizer.php', 'web-fetch.php', 'design-tokens.php',
];

foreach ($scaffolds as $file) {
    check("exists scaffolds/{$file}", is_file("{$root}/{$file}"));
}

foreach ($scaffolds as $file) {
    if (is_file("{$root}/{$file}")) {
        require "{$root}/{$file}";
    }
}

$classes = [
    'Autoloader', 'Router', 'Container', 'Database', 'SecureSession', 'Csrf',
    'Validator', 'View', 'Logger', 'ErrorHandler', 'RateLimiter', 'FileCache',
    'HttpClient', 'Crypto', 'Graph', 'EventDispatcher', 'EventStore',
    'CommandBus', 'QueryBus', 'Pipeline', 'HmvcDispatcher', 'ServerlessHandler',
    'McpServer', 'Tracing', 'Migrator',
    // v0.0.2
    'WorkerOrchestrator', 'WorkerLaunchRequest', 'IterationBudget',
    'DenialCircuitBreaker', 'WorkerState', 'Humanizer', 'WebFetch', 'DesignTokens',
];
foreach ($classes as $class) {
    check("class {$class} loaded", class_exists($class));
}

// — رفتار پایه —
$graph = new Graph();
$graph->addEdge('a', 'b');
$graph->addEdge('a', 'c');
$graph->addEdge('b', 'd');
check('graph bfs order', $graph->bfs('a') === ['a', 'b', 'c', 'd']);
check('graph acyclic', !$graph->hasCycle());

$pipeline = new Pipeline([
    static fn ($p, $next) => '[' . $next($p) . ']',
    static fn ($p, $next) => $next(strtoupper((string) $p)),
]);
check('pipeline wraps+uppercases', $pipeline->process('x') === '[X]');

final class DemoCommand
{
}

$bus = new CommandBus();
$bus->register(DemoCommand::class, static fn (): string => 'done');
check('command bus dispatch', $bus->dispatch(new DemoCommand()) === 'done');

$v = new Validator(['email' => 'not-an-email']);
$v->required('email')->email('email');
check('validator rejects bad email', $v->fails());

check('view escape', View::e('<script>"x"</script>') === '&lt;script&gt;&quot;x&quot;&lt;/script&gt;');

// — v0.0.2: رفتار اسکلت‌های جدید (الگوبرداری‌شده از مخازن مرجع) —
$o = new WorkerOrchestrator();
$w = $o->spawn(new WorkerLaunchRequest(
    'demo task', 'ctx', 'leaf', ['write-core'], ['delegate'], 'smoke', [], 60
));
check('worker spawn state STARTING', $w->state === WorkerState::STARTING);
check('worker capability token hmac prefix', str_starts_with($w->capabilityToken, 'hmac:'));
$o->kanbanClaim($w->workerId);
check('worker kanban claim -> RUNNING', $w->state === WorkerState::RUNNING);
$verdict = $o->review('review x', 'ctx', static fn (string $id): string => 'continue');
check('worker review verdict continue', $verdict === 'continue');
try {
    for ($i = 0; $i < 3; $i++) {
        $o->kanbanRequestChanges($w->workerId, "fix $i");
    }
} catch (Throwable $e) {
}
check('denial circuit breaker trips after 3', $o->denials->tripped());
$o2 = new WorkerOrchestrator();
$o2->estop();
$estopBlocked = false;
try {
    $o2->spawn(new WorkerLaunchRequest('x', 'c', 'leaf', ['read-all'], ['delegate'], 'e', [], 10));
} catch (Throwable $e) {
    $estopBlocked = true;
}
$o2->estopClear();
check('estop blocks new spawn', $estopBlocked);

$h = new Humanizer();
$badText = 'Welcome to our powerful, seamless, and comprehensive platform. It is important to note that you can easily elevate your experience. As an AI I cannot guarantee results.';
check('humanizer detects AI smell (score > 0)', $h->score($badText) > 0);
check('humanizer shouldPolish on bad text', $h->shouldPolish($badText) === true);
check('humanizer passes clean text', $h->score('Sign up takes 30 seconds.') === 0);
$ck = $h->claimCheck('30 seconds', '30 seconds', ['30 seconds']);
check('humanizer claimCheck preserves fact', $ck['ok'] === true);

$dt = new DesignTokens();
check('design-tokens resolve primary to hex', $dt->resolve('{color.primary}') === '#1d4ed8');
check('design-tokens resolve nested component', $dt->resolve('{button.bg}') === '#1d4ed8');
$cssOut = $dt->export('css');
check('design-tokens css export has :root', str_contains($cssOut, ':root'));
check('design-tokens json valid', json_validate($dt->export('json')));

$wf = new WebFetch();
$cleaned = $wf->cleanHtml('<header><nav>x</nav></header><main><p>hello</p></main><footer>f</footer>');
check('web-fetch cleanHtml removes header/footer/nav', !str_contains($cleaned, 'x') && str_contains($cleaned, 'hello'));
$md = $wf->toMarkdown('<h1>T</h1><p>para <strong>b</strong></p>');
check('web-fetch toMarkdown heading+bold', str_contains($md, '# T') && str_contains($md, '**b**'));

// — v0.0.2 correction coverage: فعل‌هایی که تاکنون تست نشده بودند —

// steer: اصلاح مسیر میان‌اجرا (هرگز فراخوانی جاری را قطع نمی‌کند)
$o3 = new WorkerOrchestrator();
$w3 = $o3->spawn(new WorkerLaunchRequest('steer task', 'ctx', 'leaf', ['write-core'], ['delegate'], 'smoke', [], 60));
$o3->kanbanClaim($w3->workerId);
$o3->steer($w3->workerId, 'change direction');
check('steer enqueues message on running worker', !empty($w3->steerQueue) && $w3->steerQueue[0] === 'change direction');

// handoff: انتقال فرایند با rationale اجباری + سقف ۳
$wA = $o3->spawn(new WorkerLaunchRequest('A', 'ctx', 'leaf', ['write-core'], ['delegate'], 'smoke', [], 60));
$wB = $o3->spawn(new WorkerLaunchRequest('B', 'ctx', 'leaf', ['write-core'], ['delegate'], 'smoke', [], 60));
$o3->handoff($wA->workerId, $wB->workerId, 'transfer ownership');
check('handoff increments from.handoffsGiven', $wA->handoffsGiven === 1);
check('handoff enqueues note on target', str_contains($wB->steerQueue[count($wB->steerQueue) - 1] ?? '', 'handoff-from:'));
$handoffEmptyRejected = false;
try {
    $o3->handoff($wA->workerId, $wB->workerId, '');
} catch (Throwable $e) {
    $handoffEmptyRejected = true;
}
check('handoff rejects empty rationale', $handoffEmptyRejected);

// moa: اجرای متوالی چند مشاور + تجمیع‌کننده (نه رأی‌گیری)
$moaResult = $o3->moa(
    'arch-decision',
    [static fn (string $t): string => 'sec', static fn (string $t): string => 'perf'],
    static fn (array $op): string => implode('+', $op)
);
check('moa aggregates advisor opinions sequentially', $moaResult === 'sec+perf');

// forceTerminate + continueWorker: snapshot برای از سرگیری (< ۳۶۰۰s)
$o3->forceTerminate($w3->workerId, WorkerState::INTERRUPTED, 'manual stop');
check('forceTerminate sets terminal state + terminatedAt', WorkerState::isTerminal($w3->state) && $w3->terminatedAt !== null);
$o3->continueWorker($w3->workerId, 'resume');
check('continueWorker resumes terminated snapshot -> RUNNING', $w3->state === WorkerState::RUNNING);

// web-fetch filterLinks: هر دلیل رد به‌درستی برچسب می‌خورد
$links = $wf->filterLinks(['https://other.com/x', 'mailto:a@b.com', '#frag', 'https://example.com/p'], 'example.com', 5);
$byUrl = [];
foreach ($links as $l) {
    $byUrl[$l['url']] = $l['denial'];
}
check('filterLinks cross-domain -> DENIAL_CROSS_DOMAIN', $byUrl['https://other.com/x'] === WebFetch::DENIAL_CROSS_DOMAIN);
check('filterLinks mailto -> DENIAL_MAILTO', $byUrl['mailto:a@b.com'] === WebFetch::DENIAL_MAILTO);
check('filterLinks anchor -> DENIAL_ANCHOR', $byUrl['#frag'] === WebFetch::DENIAL_ANCHOR);
check('filterLinks same-host -> null (allowed)', $byUrl['https://example.com/p'] === null);

// web-fetch robotsAllows: URL بدون host -> مجاز (بدون واکشی شبکه)
check('robotsAllows no-host url -> true (no network fetch)', $wf->robotsAllows('not-a-url') === true);

echo $failed === 0 ? "\nSMOKE TEST: PASS\n" : "\nSMOKE TEST: {$failed} FAILURE(S)\n";
exit($failed === 0 ? 0 : 1);
