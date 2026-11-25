# ۰۱ — مرجع: NousResearch/hermes-agent

> نسخه مهارت: **0.0.2** | لایسنس مرجع: **MIT** | زبان: Python 3.11 (+ TypeScript برای desktop/TUI)
> URL: <https://github.com/NousResearch/hermes-agent>
> ADRهای متناظر: [0015](../../ADR/0015-worker-orchestration-model.md)، [0016](../../ADR/0016-independent-review-worker.md)، [0017](../../ADR/0017-iteration-budget-and-estop.md)، [0018](../../ADR/0018-denial-circuit-breaker.md)، [0024](../../ADR/0024-capability-token-contract-version.md)

## هویت

hermes-agent «ایجنت خودبهبودیاب ساختهٔ Nous Research» است: یک هستهٔ واحد که
از CLI، TUI، desktop (Electron)، web dashboard، ویرایشگرهای ACP، و یک گیتوی
پیام‌رسانی ۲۵-پلتفرمی اجرا می‌شود؛ با افزونگی از طریق plugins/skills نه
رشد هسته. الگوی ارزشمند برای ما، **مدل قرارداد و چرخهٔ فرزند** آن است، نه
خود ایجنت.

## ساختار (مجموعهٔ مرتبط)

```
hermes-agent/
├── run_agent.py            # facade؛ turn loop در agent/turn_*.py
├── model_tools.py          # handle_function_call؛ dispatch ابزار
├── toolsets.py             # TOOLSETS dict + _HERMES_CORE_TOOLS
├── agent/                  # ~۳۰۰ ماژول: turn loop, delegation, moa, review, estop
│   ├── subagent_lifecycle.py   # SubagentState enum + SubagentLaunchRequest (frozen)
│   ├── iteration_budget.py     # consume/refund; والد ۵۰۰/فرزند ۵۰
│   ├── deadline.py             # DeadlineExpired (جدا از timeout provider)
│   ├── estop.py                # sentinel fail-safe
│   └── review_engine.py        # /review (بازبین مستقل)
├── tools/
│   ├── delegate_tool.py            # delegate_task {spawn,list,steer,stop,continue}
│   ├── kanban_tools.py             # claim/heartbeat/complete/block/request_review/...
│   ├── process_registry_handoff.py # handoff (سقف ۳/فرزند)
│   └── approval.py                 # DenialCircuitBreaker (۳ رد → hard-stop)
├── skills/  optional-skills/   # SKILL.md capability cards
├── persistence/              # SQLite session store
└── cron/                     # scheduler + kanban dispatcher
```

## آنچه آموختیم

۱. **نقش پویا، نه ثابت:** hermes نقش‌ها را در زمان spawn از عمق استنتاج
   می‌گیرد (`leaf`/`orchestrator`)؛ فایل YAML ثابت ندارد. ما نقش‌های ثابت
   داریم (۷۹ تا در `RULES.json`) — پس *نقش* ثابت می‌ماند، اما *worker_id*،
   *capability_token*، و *state* پویا تولید می‌شوند.
۲. **قرارداد spawn منجمد:** `SubagentLaunchRequest` یک dataclass با فیلدهای
   محدود و readonly است (`goal, context, role, allowed_toolsets, blocked_tools,
   correlation_id, metadata, timeout_seconds`) — قابل‌ممیزی و غیرقابل‌تغییر پس از spawn.
۳. **مرز پیام:** فقط *خلاصه* به والد بازمی‌گردد؛ فراخوانی‌های ابزار و
   استدلال میانی فرزند هرگز به والد نشت نمی‌کند. این، آلودگی context و
   هزینهٔ token را کنترل می‌کند.
۴. **fails closed:** backend ناموجود = خطای ابزار صریح، هرگز fallback خاموش.
۵. **دو لایهٔ کنترل جداگانه:** IterationBudget (منطقی) و DeadlineExpired (زمان) —
   جدا از timeout ارائه‌دهنده، تا قطعی backend با بن‌بست محلی اشتباه نشود.
۶. **acceptance gate:** `kanban_complete` تا `verdict ∈ {blocked, continue}`
   تأیید نشده مسدود است — DoD یک رأی است، نه یک گزاره.

## آنچه الگوبرداری شد (با artifact)

| الگو | منبع (مسیر فایل) | artifact ما |
|------|------------------|-------------|
| `SubagentState` enum + snapshot ۳۶۰۰s | `agent/subagent_lifecycle.py` | `WorkerState` در [`scaffolds/worker-orchestrator.php`](../../scaffolds/worker-orchestrator.php) |
| `SubagentLaunchRequest` readonly | `agent/subagent_lifecycle.py` | `WorkerLaunchRequest` (همان اسکلت) |
| `delegate_task` {spawn,list,steer,stop,continue} | `tools/delegate_tool.py:852` | `WorkerOrchestrator::spawn/list/steer/stop/continueWorker` |
| kanban verbs + acceptance gate | `tools/kanban_tools.py` | `WorkerOrchestrator::kanban*` |
| `IterationBudget` (consume/refund; ۵۰۰/۵۰) | `agent/iteration_budget.py` | `IterationBudget` (همان اسکلت) |
| `DeadlineExpired` جدا از timeout | `agent/deadline.py` | مستند در بخش ۵ [`docs/org/12-worker-orchestration.md`](../org/12-worker-orchestration.md) |
| `estop` sentinel | `agent/estop.py` | `WorkerOrchestrator::estop()` |
| DenialCircuitBreaker (۳ رد) | `tools/approval.py` | `DenialCircuitBreaker` (همان اسکلت) |
| Process handoff (سقف ۳/فرزند + rationale) | `tools/process_registry_handoff.py` | `WorkerOrchestrator::handoff()` |
| capability token HMAC + contract_version | `agent/subagent_lifecycle.py` | `WorkerOrchestrator::issueCapabilityToken()` |
| `/review` بازبین مستقل | `agent/review_engine.py` | `WorkerOrchestrator::review()` (SoD-5) |
| `/moa` Mixture-of-Agents | `agent/moa_loop.py` | `WorkerOrchestrator::moa()` |
| SKILL.md frontmatter + curator states | `skills/AGENTS.md` | مستند در بخش ۲ [`docs/org/12-worker-orchestration.md`](../org/12-worker-orchestration.md) |

## آنچه الگوبرداری *نشد* (صداقت مهندسی)

| آنچه حذف شد | چرا |
|-------------|-----|
| HMAC capability token با کلید پایدار cross-session | sandbox ریست می‌شود؛ کلید per-run تصادفی (محدودیت مستند: [`docs/org/12-worker-orchestration.md`](../org/12-worker-orchestration.md) بخش ۸) |
| Kanban در SQLite پایدار | پردازش‌ها ماندگار نیستند؛ در حافظه نگه می‌داریم |
| MCP gateway ۲۵-پلتفرمی | خارج از scope یک مهارت PHP |
| Plugins/skills curator با کش آنلاین | ما کاتالوگ آفلاین + `check-links.sh` سه‌ماهه داریم |
| ThreadPoolExecutor واقعی برای moa | PHP تک‌نخی؛ moa به‌صورت متوالی با فیبر جداگانه در صورت نیاز |
| `DelegationProvider` ABC برای plugins | پیچیدگی غیرضروری برای مدل ما |

## گارد سازگاری

این الگوبرداری **شمارش‌های سازمانی را تغییر نمی‌دهد** — هیچ نقش جدیدی
اضافه نشد. `python3 scripts/verify_org.py` همچنان ۷۹/۳۷۲/۴۲ را تأیید می‌کند.
الگوها به‌عنوان *فعل‌های مجاز فراتر از اجرای وظیفه* در بخش ۹ قالب
`_template.md` می‌نشینند، نه به‌عنوان نقش جدید.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: hermes-agent (MIT, NousResearch)
