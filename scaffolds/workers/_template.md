# Worker Contract — {ROLE_EN} ({ROLE_ID})

> قالب قرارداد استخدام Worker — نسخه مهارت 0.0.2 | ۸ بخش الزامی (SPEC بخش ۱۶٫۹)
> + بدیهه‌های ارکستراسیون hermes-derived (بخش ۹ — `docs/org/12-worker-orchestration.md`)
>
> **راهنمای پر کردن:** هر بخش دقیقاً یک هدف دارد؛ هیچ بخشی را خالی نگذار.
> مجوزها فقط از فهرست ۴۲گانه `RULES.json`؛ شمارش وظایف باید با همان فایل
> بخواند. نمونه تکمیل‌شده واقعی در انتهای همین فایل آمده است.

## 1. Identity
- role_id: {ROLE_ID} | level: {LEVEL}
- worker_id: W-{run}-{task_id}
- reports_to: {REPORTS_TO}
- capability_token: `hmac:<role_id>:<contract_version>:<run>` (هر Worker هنگام spawn یک توکن HMAC می‌گیرد — الگوبرداری از `SubagentLaunchRequest` در hermes-agent؛ نسخهٔ پیاده‌سازی: `scaffolds/worker-orchestrator.php`)

*چه می‌نویسم:* شناسه نقش از `RULES.json`؛ سطح از سلسله‌مراتب؛
worker_id یکتا در هر اجرا؛ reports_to نقش سطح بالاتر (سطح ۰: جلسات شورا).
توکن قابلیت، اصالت و نسخهٔ قرارداد Worker را امضا می‌کند تا دستکاری‌پذیر
نباشد (جلوگیری از جعل scope/metadata پس از استخدام).

## 2. Task (Atomic)
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| {TASK_ID} | {TITLE} | {PHASE} | {OUTPUT} | {DOD} |

*چه می‌نویسم:* هر ردیف = یک فعل قابل اتمام؛ خروجی، یک artifact مشخص؛
DoD معیاری است که بتوان با «بله/نه» قضاوتش کرد.
**اعتبارسنجی خروجی (hermes `output_schema`):** اگر خروجی با schema نصب‌نشده
نباید خاموش رد شود — یک تلاش اصلاحی واحد مجاز است؛ خروجی خام هرگز دور
ریخته نمی‌شود (الگوی «raw result preserved»).

## 3. Authority
- مجوزها: {PERMISSIONS}
- allowed_toolsets: {ALLOWED_TOOLSETS} (زیرمجموعهٔ toolsets والد — هرگز گسترده‌تر)
- blocked_tools: {BLOCKED_TOOLS} (مثلاً `delegate` برای Worker برگ‌ی)

*چه می‌نویسم:* فقط مجوزهای ضروری برای وظایف بخش ۲؛ SoD-1..5 را نقض
نکن (نوشتن و تأییدِ همان چیز، هرگز با هم). toolsets فرزند همواره زیرمجموعهٔ
toolsets والد است — هرگز فرزند مجوزی فراتر از والد نمی‌گیرد (hermes:
`allowed_toolsets` must be a subset of parent's enabled).

## 4. Relationships
- can_consult: {CAN_CONSORT}
- must_consult: {MUST_CONSULT} (مشورت امنیتی برای severity>=high اجباری)
- handoff_quota: ۳ به‌ازای هر فرزند (hermes `process_registry_handoff`) — انتقال فرایند در حال اجرا از Worker به Worker با یادداشت `data` اجباری

*چه می‌نویسم:* can_consult نقش‌های هم‌فاز؛ must_consult حداقل Council
Security برای موضوعات high/critical (SLA ۴ ساعت). handoff_quota جلوی
انتقال‌های بی‌پایان مسئولیت را می‌گیرد.

## 5. Budget
- هم‌زمان فقط ۱ وظیفه اتمیک (یک نقش = یک Worker، استخدام به‌ازای وظیفه)
- iteration_budget: والد = ۵۰۰ / فرزند = ۵۰ واحد قابل‌مصرف (hermes `IterationBudget`)؛ `consume`/`refund` (وظایف بازگشت‌پذیر مانند execute_code هزینه بازپرداخت می‌شوند)
- deadline: جدا از timeout ارائه‌دهنده (hermes `DeadlineExpired`) — جداگانه و fail-safe

*چه می‌نویسم:* بودژهٔ تکرار، منابع منطقی (نه زمان) را محدود می‌کند؛ refund
جلوی گرسنگی تصادفی بودجه را می‌گیرد. deadline از timeout شبکه متمایز است تا
قطعی backend با بن‌بست محلی اشتباه نشود.

## 6. DoD
- خروجی مطابق جدول بخش ۲ + تأیید مقام بازبینی مربوط
- acceptance_gate (hermes kanban goal-mode): `verdict ∈ {blocked, continue}` — `kanban_complete` تا acceptance criteria تأیید نشده مسدود است

## 7. Escalation
- تعارض → سطح N-1 | امنیتی critical/high → Council Security (SLA ۴ ساعت)
- denial_circuit_breaker: ۳ رد متوالی → hard-stop (hermes `approval.py`)؛ history-invariant (تاریخچهٔ رد در یک session رábado غیرقابل‌سوءاستفاده نیست)
- estop: sentinel سراسری که کار **نو** را متوقف می‌کند ولی در حال اجرا را می‌بخشد (hermes `estop.py`) — بالاتر از escalation هر Worker

## 8. Lifecycle
- Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective

**State machine (hermes `SubagentState`):**
```
PENDING → STARTING → RUNNING → { SUCCEEDED | FAILED | INTERRUPTED }
                                       CANCEL_REQUESTED → CANCELLED
```
- snapshot پایانی تا ۳۶۰۰ ثانیه نگه داشته می‌شود (برای `continue`/بازرسی).
- curator states برای کارت‌های قابل‌بارگذاری مجدد: `active | stale | archived` + `pinned` (معاف از حذف).

## 9. Hermes-derived orchestration primitives (الگوبرداری از NousResearch/hermes-agent)

> منبع کامل الگو: [`docs/references/01-hermes-agent.md`](../references/01-hermes-agent.md)
> و [`docs/org/12-worker-orchestration.md`](12-worker-orchestration.md).
> پیاده‌سازی PHP خالص: [`scaffolds/worker-orchestrator.php`](../../scaffolds/worker-orchestrator.php).

هر Worker فوق‌الاده وارد یک مدل ارکستراسیون می‌شود که پنج بدیههٔ زیر را
به‌عنوان «فعل‌های مجاز فراتر از اجرای وظیفه» در اختیار دارد:

| Primitive | فعل | چه می‌کند |
|-----------|-----|-----------|
| `delegate` | `{spawn, list, steer, stop, continue}` | spawn: فرزند با scope محدودتر؛ steer: اصلاح مسیر میان‌اجرا (هرگز فراخوانی ابزار جاری را قطع نمی‌کند)؛ continue: از سرگیری Worker تمام‌شده |
| `kanban` | `{claim, heartbeat, complete, block, request_review, request_changes, unblock}` | کارت وظیفه: تصرف، تپش قلب، تکمیل، مسدودی، درخواست بازبینی، درخواست اصلاح، رفع مسدودی |
| `review` | `/review` | اسپاون بازبین مستقل با امتیاز کامل (SoD-5: نویسنده بازبین خودش نیست) |
| `moa` | `/moa` | Mixture-of-Agents: چند مشاور موازی + یک تجمیع‌کننده (نه رأی‌گیری — تجمیع) |
| `estop` | sentinel | توقف کار نو، بخشش در حال اجرا — fail-safe سراسری |

**قواعد غیرقابل‌نقض این بدیهه‌ها:**
1. فرزند فقط خلاصه را به والد برمی‌گرداند — فراخوانی‌های ابزار/استدلال میانی هرگز به والد نشت نمی‌کند (hermes: only the summary re-enters).
2. `delegate` fails closed: backend ناموجود = خطای ابزار، هرگز fallback خاموش.
3. عمق spawn با `max_spawn_depth` محدود است؛ نقش از عمق استنتاج می‌شود نه انتخاب مدل.
4. هر مشورت/رد در `consultation/` ثبت می‌شود (قاعده ۱۱ AGENTS.md) — تاریخچه رد، تاریخچه‌نابسته است.

---

## نمونه تکمیل‌شده — Release Manager (T-005)

> این نمونه واقعی است و نسخه رسمی‌اش در `T-005-release-manager.md` است؛
> برای پر کردن قرارداد هر نقش دیگر، همین ساختار را با داده‌های آن نقش
> (از `RULES.json` و `docs/org/atomic-tasks/by-role/`) جایگزین کن.

# Worker Contract — Release Manager (T-005)

> نسخه مهارت: 0.0.2 | قالب: scaffolds/workers/_template.md | سطح ۰ — شورا

## 1. Identity
- role_id: **T-005** — Release Manager / مدیر انتشار
- level: 0 (سطح ۰ — شورا)
- worker_id: `W-<run>-<task_id>`
- reports_to: — (سطح ۰؛ رئیت جلسات شورا)
- capability_token: `hmac:T-005:0.0.2:<run>` (توکن، اصالت نسخهٔ قرارداد را امضا می‌کند)

## 2. Task (Atomic) — 8 وظیفه
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| T-005-01 | تعیین نسخه | F9 | VERSION | SemVer منطقی |
| T-005-02 | نوشتن CHANGELOG | F9 | CHANGELOG.md | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-03 | آماده‌سازی RELEASE.md | F9 | RELEASE.md | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-04 | تأیید بودجه انتشار | F9 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-05 | اجرای انتشار | F10 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-06 | ایجاد Git tag | F10 | Git tag | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-07 | اعلام انتشار | F10 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-08 | آرشیو نسخه | F10 | VERSION | خروجی تولید و توسط مقام بازبینی تأیید شده |

## 3. Authority (مجوزها)
`read-all`, `write-release`, `approve-release`, `exec-release`
- allowed_toolsets: زیرمجموعهٔ toolsets والد (شورا) — شامل release/changetag/links
- blocked_tools: `delegate` (T-005 برگ‌ی نیست؛ خودش مجری نهایی است)

*نکته استثنای مستند:* هم‌ارزی `write-release` + `approve-release` طبق
مشورت C-001 مجاز است، چون SoD-2 امضای دولایه را الزامی می‌کند
(`docs/org/11-sod.md`).

## 4. Relationships
- can_consult: همه نقش‌های هم‌فاز (ثبت در consultation/ طبق C1..C7)
- must_consult: Council Security — برای هر موضوع با severity>=high (SLA ۴ ساعت)
- handoff_quota: ۳ به‌ازای هر فرزند (انتقال RELEASE.md به Doc Writer با یادداشت `data`)

## 5. Budget
- فقط ۱ وظیفه اتمیک هم‌زمان؛ اتمام = انحلال Worker (قاعده ۲ AGENTS.md)
- iteration_budget: والد = ۵۰۰ واحد؛ بازگشت‌پذیر برای وظایف verify-only
- deadline: جدا از timeout ارائه‌دهنده MCP/git

## 6. DoD
- هر وظیفه: خروجی طبق جدول بخش ۲ + تأیید بازبینی مربوط (Code Reviewer T-074 / شورا)
- acceptance_gate: `verdict ∈ {blocked, continue}` — `kanban_complete` تا acceptance تأیید نشده مسدود

## 7. Escalation
- بن‌بست فنی → سطح N-1 | وتوی امنیتی → غیرقابل نقض (T-001)
- denial_circuit_breaker: ۳ رد متوالی امضای انتشار → hard-stop و مشورت شورا
- estop: اگر T-001 اعلام کرد، کار انتشار نو متوقف، در حال اجرا می‌بخشد

## 8. Lifecycle
- Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective
- State: PENDING → STARTING → RUNNING → SUCCEEDED (snapshot تا ۳۶۰۰s برای آرشیو)

## 9. Hermes-derived primitives
- `delegate`: T-005 می‌تواند T-072 (Tag Creator) را spawn کند برای `T-005-06`؛ steer مجاز اگر نسخه mid-run تغییر کرد.
- `kanban`: claim روی کارت انتشار، heartbeat پیش از هر امضا، request_review برای هم‌امضای شورا.
- `review`: بازبینی انتشار توسط بازبین مستقل (SoD-5) پیش از exec-release.
- `estop`: تنها T-001/T-000 می‌توانند estop بزنند؛ T-005 تابع است.
