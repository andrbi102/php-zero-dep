# ۱۲ — مدل ارکستراسیون Worker (الگوبرداری‌شده از NousResearch/hermes-agent)

> نسخه مهارت: **0.0.2** | منبع الگو: [`docs/references/01-hermes-agent.md`](../references/01-hermes-agent.md)
> پیاده‌سازی PHP خالص: [`scaffolds/worker-orchestrator.php`](../../scaffolds/worker-orchestrator.php)
>
> **چرا این سند:** مدل سازمانی v2 (۷۹ نقش / ۳۷۲ وظیفه) ساختار *نقش* و
> *وظیفه اتمیک* را تعریف می‌کند؛ این سند *بدیهه‌های ارکستراسیون* را
> می‌افزاید — یعنی فعل‌هایی که یک Worker می‌تواند فراتر از اجرای وظیفه‌اش
> انجام دهد: واگذاری، هدایت میان‌اجرا، بازبینی مستقل، توقف اضطراری. این
> فعل‌ها از معماری hermes-agent الگوبرداری شده‌اند و در PHP خالص
> پیاده‌سازی شده‌اند، بدون افزودن نقش جدید (شمارش‌ها ۷۹/۳۷۲/۴۲ دست‌نخورده).

## ۰. چرا hermes-agent

hermes-agent (MIT، NousResearch) یک ایجنت خودبهبودیاب است که با *واگذاری
پویا* کار می‌کند: یک هسته، و فرزندانی که به‌صورت ad-hoc با scope محدود
spawn می‌شوند. الگوی ارزشمند آن برای ما، **نه** خود ایجنت، بلکه **مدل
قرارداد و چرخه‌ی فرزند** است:

- Worker به‌صورت پویا spawn می‌شود، نه فایل نقش ثابت — اما ما نقش‌های ثابت
  داریم (۷۹ تا). پس الگو را به‌ این‌گونه تطبیق می‌دهیم: *نقش* از `RULES.json`
  می‌آید (ثابت)، اما *worker_id* و *capability_token* و *state* در زمان
 spawn تولید می‌شوند (پویا).
- پنج فعل ارکستراسیون (`delegate/kanban/review/moa/estop`) به‌عنوان
  «ابزارهای مجاز فراتر از اجرای وظیفه» در قرارداد هر Worker می‌نشینند
  (بخش ۹ قالب `_template.md`).

## ۱. قرارداد spawn (SubagentLaunchRequest)

هر Worker هنگام استخدام، یک *درخواست spawn* می‌گیرد — معادل ساختار
`SubagentLaunchRequest` در hermes (فایل `agent/subagent_lifecycle.py`):

| فیلد | معنا | در ما |
|------|------|-------|
| `goal` | هدف یک‌خطی | از `Task.عنوان` قرارداد |
| `context` | زمینهٔ اولین turn | خروجی فاز قبول +DoD |
| `role` | `leaf` یا `orchestrator` | از سطح استنتاج (سطح ۴ = leaf، ۰-۱ = orchestrator) |
| `allowed_toolsets` | ابزارهای مجاز | زیرمجموعهٔ والد — هرگز گسترده‌تر |
| `blocked_tools` | ابزارهای ممنوع | `delegate` برای leafها |
| `correlation_id` | رشتهٔ یکتا | `W-<run>-<task_id>` + HMAC |
| `metadata` | متادیتا | phase, parent_session, sibling_scope |
| `timeout_seconds` | مهلت | از Budget/Deadline |
| `contract_version` | نسخهٔ قرارداد | `0.0.2` |

**قاعده غیرقابل‌نقض:** `allowed_toolsets` همواره زیرمجموعهٔ toolsets والد
است. فرزند هرگز مجوزی فراتر از والد نمی‌گیرد — این، جلوی privilege
escalation از طریق nesting را می‌گیرد.

## ۲. State machine (SubagentState)

```
PENDING → STARTING → RUNNING → { SUCCEEDED | FAILED | INTERRUPTED }
                                       CANCEL_REQUESTED → CANCELLED
```

- snapshot پایانی تا **۳۶۰۰ ثانیه** نگه داشته می‌شود تا `continue` (از سرگیری
  Worker تمام‌شده) و بازرسی پس از انحلال ممکن باشد.
- `INTERRUPTED` = قطعی خارجی (backend/شبکه)؛ `CANCELLED` = درخواست صریح
  والد via `stop`.
- کارت‌های قابل‌بارگذاری مجدد (capability cards) سه حالت curator دارند:
  `active | stale | archived` + `pinned` (معاف از GC).

## ۳. پنج فعل ارکستراسیون

### ۳-۱. `delegate` — واگذاری پویا

فعل واحد با پنج action:

| action | چه می‌کند |
|--------|-----------|
| `spawn` | فرزند با scope محدودتر؛ `role` از عمق استنتاج |
| `list` | فهرست فرزندان زنده |
| `steer` | اصلاح مسیر میان‌اجرا — **هرگز** فراخوانی ابزار جاری را قطع نمی‌کند؛ پیام در صف بعدی turn می‌نشیند |
| `stop` | درخواست لغو (CANT_REQUESTED) |
| `continue` | از سرگیری Worker تمام‌شده (snapshot < ۳۶۰۰s) |

**مرز پیام:** فقط *خلاصه* به مکالمهٔ والد بازمی‌گردد. فراخوانی‌های ابزار و
استدلال میانی فرزند هرگز به والد نشت نمی‌کند — این، آلودگی context والد را
می‌بندد و هزینهٔ token را کنترل می‌کند.

**fails closed:** backend ناموجود = خطای ابزار صریح، هرگز fallback خاموش.

### ۳-۲. `kanban` — کارت وظیفه با acceptance gate

هفت فعل (معادل `tools/kanban_tools.py`):

`claim → heartbeat → {complete | block | request_review | request_changes | unblock}`

- `complete` تا `acceptance criteria` تأیید نشده مسدود است (goal-mode judge):
  `verdict ∈ {blocked, continue}`. اگر `blocked`، کارت به `request_changes`
  می‌رود.
- این، معادل DoD ماست، اما با دروازهٔ صریح: DoD یک گزاره نیست، یک *رأی* است.

### ۳-۳. `review` — بازبین مستقل (SoD-5)

`/review` یک Worker مستقل با **امتیاز کامل** spawn می‌کند که نتیجه‌اش به‌عنوان
یک تکمیل‌شدنی ناهمگام وارد می‌شود. این، تجسم SoD-5 است: نویسنده کد هرگز
بازبین همان کد نیست. تفاوت با Code Reviewer (T-074): T-074 نقش ثابت
cross-cutting است؛ `/review` بازبین ad-hoc با scope تک‌وظیفه‌ای است.

### ۳-۴. `moa` — Mixture-of-Agents

چند مشاور (در پیاده‌سازی PHP خالص به‌صورت **متوالی** اجرا می‌شوند — موازی‌سازی
واقعی نیاز به `Fiber`/`pcntl` جداگانه دارد و در `scaffolds/worker-orchestration.php`
فعلاً لحاظ نشده) → یک تجمیع‌کننده. **نه رأی‌گیری** — تجمیع.
کاربرد: تصمیمات معماری با چند منظر (امنیت/عملکرد/کیفیت) که شورا روی آن
بحث می‌کند. در solo پروفایل، moa اغلب اضافی است؛ در full، برای تصمیمات
سطح ۰ به‌کار می‌آید.

### ۳-۵. `estop` — توقف اضطراری fail-safe

sentinel سراسری که **کار نو** را متوقف می‌کند ولی **در حال اجرا** را
می‌بخشد (hermes `agent/estop.py`). این، بالاتر از escalation هر Worker است:
وقتی T-001 (Council Security) estop بزند، هیچ Worker نو spawn نمی‌شود، اما
Worker در حال اجرا تا یافتن نقطهٔ امن ادامه می‌دهد — نه سقط ناگهانی که
داده خراب تولید کند.

## ۴. IterationBudget

هر Worker یک بودجهٔ تکرار منطقی دارد (نه زمان):

- والد: ۵۰۰ واحد | فرزند: ۵۰ واحد
- `consume(n)` / `refund(n)` — وظایف بازگشت‌پذیر (مانند verify-only) هزینه را بازپرداخت می‌کنند.
- `DeadlineExpired` از timeout ارائه‌دهنده متمایز است تا قطعی backend با
  بن‌بست محلی اشتباه نشود.

**چرا جدا از زمان:** زمان، منبع اشتراکی و متغیر است (درس L-... در
`lessons-learned.md`: اعداد absolute sandbox معنی ندارند). بودجهٔ تکرار
منطقی، یک شمارندهٔ قطعی است که قابل راستی‌آزمایی است.

## ۵. Denial circuit breaker

هر مسیر/Worker، ردهای متوالی را ردیابی می‌کند (hermes `tools/approval.py`):
**۳ رد متوالی → hard-stop**. history-invariant: تاریخچهٔ رد در یک session
برای سوءاستفاده در session بعد قابل‌استفاده نیست. این، جلوی حلقه‌های بی‌پایان
«امتحان دوباره با همان ورودی» را می‌گیرد.

معادل در ما: اگر یک Worker سه بار پی‌درپی توسط بازبین رد شود (سه
`request_changes` پشت‌سرهم)، کارت به `block` می‌رود و مشورت شورا اجباری
می‌شود — نه تلاش چهارم.

## ۶. Process handoff

انتقال یک فرایند در حال اجرا از Worker به Worker (hermes
`process_registry_handoff`):

- سقف: **۳ به‌ازای هر فرزند**
- یادداشت `data` (rationale) اجباری
- مالکیت با spawn-tree lineage دنبال می‌شود

کاربرد: T-049 (Router Developer) یک فرایند build دارد که نیمه‌تمام است؛
به T-078 (Build Repairer) با یادداشت «خطای strict_types در src/X.php» تحویل
می‌دهد — بدون restart از صفر.

## ۷. قرارداد با مدل سازمانی موجود

| hermes | ما | تطبیق |
|--------|-----|-------|
| نقش پویا | نقش ثابت از `RULES.json` | نقش ثابت + worker_id/state پویا |
| `SubagentLaunchRequest` | بخش ۹ قالب `_template.md` | همان فیلدها |
| `delegate` | فعل مجاز در قرارداد | تنها برای orchestrator سطح ۰-۱ (leaf ممنوع) |
| `review` | T-074 (ثابت) + `/review` (ad-hoc) | SoD-5 حفظ |
| `estop` | T-001/T-000 | حق estop تنها شورا |
| ۷۹/۳۷۲/۴۲ | دست‌نخورده | هیچ نقش جدید اضافه نشد |

**گارد:** این مدل، شمارش‌های سازمانی را تغییر نمی‌دهد —
`python3 scripts/verify_org.py` همچنان ۷۹/۳۷۲/۴۲ را تأیید می‌کند.

## ۸. چه چیزی الگوبرداری *نشد*

صادقانه (درس L-002: منبع تأییدنشده، ممنوع):

- **HMAC capability token با کلید پایدار cross-session:** hermes از یک کلید
  سروری استفاده می‌کند؛ ما توکن را per-run با کلید تصادفی تولید می‌کنیم
  (هیچ state سروری بین runs ماندگار نیست — sandbox ریست می‌شود).
- **Kanban با ذخیره‌سازی پایدار:** hermes kanban را در SQLite نگه
  می‌دارد؛ ما در حافظه (آرایه) نگه می‌داریم — چون پردازش‌ها ماندگار نیستند.
- **MCP gateway ۲۵-پلتفرمی:** خارج از scope یک مهارت PHP.
- **Plugins/skills curator با کش آنلاین:** ما از کاتالوگ آفلاین استفاده
  می‌کنیم (`docs/catalog.md` + `scripts/check-links.sh` سه‌ماهه).

این محدودیت‌ها در [`docs/references/01-hermes-agent.md`](../references/01-hermes-agent.md)
بخش «چه چیزی الگوبرداری نشد» باز شده‌اند.

## ۹. مثال اجرایی — F4 با delegate

سناریو: Chief Architect (T-010) در F4، پیاده‌سازی router را به T-049 واگذار می‌کند.

```
T-010 (orchestrator, سطح ۱)
  └─ delegate.spawn(role=leaf, goal="پیاده‌سازی روتر از scaffolds/router.php",
                    allowed_toolsets=[read-architecture, write-core],
                    blocked_tools=[delegate])
       └─ W-run-T-049-01  (state: PENDING→STARTING→RUNNING)
            └─ kanban.claim → heartbeat → complete
                 └─ acceptance_gate: Code Reviewer T-074 → verdict=continue → complete
       ← خلاصه به T-010 بازگشت (نه کد میانی)
```

اگر T-049 سه بار رد شود → `denial_circuit_breaker` → کارت `block` →
مشورت شورا (ثبت در `consultation/`). اگر T-001 estop بزند → T-049 در حال
اجرا تا نقطهٔ امن ادامه می‌دهد، T-010 Worker نو spawn نمی‌کند.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: hermes-agent (MIT, NousResearch)
