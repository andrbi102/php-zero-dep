# Changelog — php-zero-dep

همه تغییرات قابل‌توجه این مهارت در این فایل ثبت می‌شود.
قالب: [Keep a Changelog](https://keepachangelog.com/) — نسخه‌بندی: [SemVer](https://semver.org/).

## [0.0.2] — 2026-11-25

### Added — الگوبرداری از ۵ مخزن مرجع

- **`docs/references/`** (پوشهٔ جدید + `00-index.md`): پنج سند مرجع برای مخازن
  الگوبرداری‌شده، هرکدام با: هویت، ساختار، آنچه الگوبرداری شد (با artifact)،
  و آنچه صادقانه الگوبرداری *نشد*:
  - `01-hermes-agent.md` — NousResearch/hermes-agent (MIT): مدل ارکستراسیون Worker.
  - `02-humanizer.md` — blader/humanizer (MIT): کاتالوگ «بوی AI».
  - `03-firecrawl.md` — firecrawl/firecrawl (AGPL-3.0/MIT): واکشی وب + HTML→markdown.
  - `04-ui-ux-pro-max.md` — nextlevelbuilder/ui-ux-pro-max-skill (MIT): توکن طراحی.
  - `05-skillspector.md` — nvidia/skillspector (Apache-2.0): ممیزی امتیازی skill.
- پنج مخزن به `docs/catalog.md` (بخش ۶ جدید) و `RULES.json` (`references` آرایه) اضافه شد.

### Added — الگوبرداری کامل hermes-agent برای بخش ورکرها

- `docs/org/12-worker-orchestration.md` (سند جدید): مدل ارکستراسیون پنج‌بدیهه‌ای
  (delegate/kanban/review/moa/estop) با state machine، IterationBudget،
  DenialCircuitBreaker، handoff، capability token HMAC — الگوبرداری‌شده از
  hermes-agent، **بدون افزودن نقش جدید** (۷۹/۳۷۲/۴۲ دست‌نخورده).
- `scaffolds/workers/_template.md`: غنی‌سازی با بخش ۹ (بدیهه‌های ارکستراسیون
  hermes-derived) + فیلدهای capability_token، allowed_toolsets/blocked_tools،
  iteration_budget، denial_circuit_breaker، estop، handoff_quota.
- `scaffolds/worker-orchestrator.php` (اسکلت جدید، PHP خالص): پیاده‌سازی
  `WorkerOrchestrator` + `WorkerLaunchRequest` + `IterationBudget` +
  `DenialCircuitBreaker` + `WorkerState`/`WorkerRecord`.

### Added — اسکلت‌ها و سندهای جدید (الگوبرداری‌شده)

- `scaffolds/humanizer.php` + `docs/frontend/08-humanizer-prose.md` — آشکارساز
  «بوی AI» مبتنی‌بر-قاعده (۲۶ الگو در ۶ گروه، نمرهٔ ۰-۱۰۰، claim-check) — الگوی humanizer.
- `scaffolds/web-fetch.php` + `examples/10-web-fetch-demo.php` — واکشی سبک +
  HTML→markdown (واترفال موتور، حذف عناصر ناخواسته، robots.txt، فیلتر لینک) — الگوی firecrawl.
- `scaffolds/design-tokens.php` + `docs/frontend/09-design-tokens.md` — توکن سه‌لایه
  (primitive→semantic→component) با صدور PHP/JSON/CSS از یک resolver — الگوی ui-ux-pro-max.

### Added — تاکسونومی کاربردها (۲۰ دسته، ~۳۶۰ کاربرد)

- `docs/use-cases/00-catalog.md` (کاتالوگ جامع با کد `<دسته>.<زیرمجموعه>` + روابط افقی + پروفایل پیشنهادی).
- ۲۰ فایل دستهٔ جدید `07-publishing.md` تا `26-food-daily.md`، هرکدام با:
  زیرمجموعه‌ها + نحوهٔ ساخت php-zero-dep + scaffolds کلیدی + checklist + anti-patterns.

### Added — محیط توسعه، ADR، ممیزی

- `docs/dev-environment/PHP_DEV_ENV_SETUP.md` (کپی سند کاربر) + `00-index.md` —
  راهنمای راه‌اندازی PHP در sandbox (بدون root، مستقل از نسخه).
- ۱۱ ADR جدید (`ADR/0015-…-0025`): مدل ارکستراسیون، بازبین مستقل (SoD-5)،
  IterationBudget/ESTOP، DenialCircuitBreaker، پالایش نثر، واکشی وب، توکن طراحی،
  ممیزی امتیازی، محیط توسعه، capability token، تاکسونومی کاربردها.
- `scripts/skill_score.py` (الگوی skillspector): ممیزی امتیازی ۰-۱۰۰ +
  توصیهٔ SAFE/CAUTION/DO_NOT_INSTALL + completeness ledger + قرارداد JSON + exit ۰/۱/۲.

### Changed

- نسخه مهارت **0.0.1 → 0.0.2** در همه فایل‌ها (VERSION، SKILL/AGENTS/MCP/RULES/SPEC،
  PHPDoc `@version`، فوترها) — به‌جز رکوردهای تاریخی (CHANGELOG `[0.0.1]`، صورت‌جلسهٔ
  v0.0.1، مشورت‌های زمان v0.0.1).
- `SKILL.md`: نقشهٔ بسته (افزودن references/ و dev-environment/) + جدول scaffolds
  (۴ اسکلت جدید + بخش ۹ ورکرها).
- `README.md`: badgeها و شمارش‌ها به‌روز (۴۱۰ فایل، ۳۶۰ سند، ۴۲ PHP، ۲۵ ADR، ۱۰ مثال، ۳۰ scaffold).
- `docs/catalog.md`: بخش ۶ (مراجع الگوبرداری).
- `docs/org/00-index.md`: لینک `12-worker-orchestration.md`.
- `council-log.md`: جلسهٔ v0.0.2. `lessons-learned.md`: L-005 (الگوبرداری AGPL بدون کپی کد)، L-006 (شمارش‌ها دست‌نخورده).
- `tests/smoke-test.php`: ۴ اسکلت جدید + ۲۳ چک رفتار (ارکستراسیون/humanizer/web-fetch/design-tokens).

### Fixed — اصلاحات پس از انتشار اولیهٔ v0.0.2 (۲۰۲۶-۱۱-۲۶)

این اصلاحات همراه با انتقال برچسب `v0.0.2` اعمال شد (v0.0.2 اولیه ناقص/ناسازگار بود):

- **A1:** حذف کلمهٔ روسی آلوده‌کنندهٔ «оставлен» در کامنت `scaffolds/worker-orchestrator.php`.
- **A2:** لایهٔ `component` در `design-tokens.php` اکنون در هر سه فرمت صدور (PHP/JSON/CSS)
  ظاهر می‌شود — قبلاً تعریف ولی unreachable بود.
- **A3:** افزودن `DENIAL_CROSS_DOMAIN` در `web-fetch.php`؛ لینک cross-domain دیگر
  به‌اشتباه `DENIAL_NON_HTTP` نمی‌گیرد.
- **A4:** داک `moa` به‌روز شد: اجرای **متوالی** در PHP خالص (نه «ThreadPool موازی»).
- **B5+6:** بخش ۹ (ارکستراسیون hermes-derived) به همهٔ ۷۹ قرارداد Worker + ۷۹ کارت
  by-role اضافه شد (قالب وعده می‌داد ولی کارت‌ها فاقدش بودند). delegate بر اساس سطح:
  council/senior مجاز، برگ‌ی مسدود.
- **B6:** ۱۷۳ فایل جا‌مانده روی `0.0.1` (۷۹ ورکر + ۷۹ by-role + ۱۰ by-phase + پراکنده)
  به `0.0.2` هم‌تراز شد؛ هاردکد `generate_role_cards.py` اصلاح و کارت‌ها بازتولید شد.
- **B7:** عنوان scaffolds در SKILL.md از «۳۰» به «۲۹» اصلاح شد (دوبارشماری).
- **B8:** مسیر stale در `PHP_DEV_ENV_SETUP.md` به مکان واقعی به‌روز شد.
- **C9:** `check-links.sh` اجرا شد؛ ارجاع جعلی `phpdet` (crates.io ۴۰۴) حذف و با
  `mago` (ابزار واقعی AST/PHP به Rust) ادغام شد؛ ۵۷ لینک سبز (۲۰۲۶-۱۱-۲۶).
- **C10:** `tests/smoke-test.php` با ۱۱ چک جدید (steer/handoff/moa/forceTerminate/
  continueWorker/filterLinks/robotsAllows) گسترش یافت (مجموعاً ۴۸ چک).
- **C11:** ۴ سناریوی eval جدید (id ۹–۱۲) برای ارکستراسیون، مسیریابی use-case،
  humanizer و web-fetch (`evals/evals.json`: ۸→۱۲ سناریو).

### Added — تکمیل‌های پس از انتشار (D12–D15)

- **D12:** `RELEASE.md` (یادداشت انتشار v0.0.2 با آمار، نصب، راستی‌آزمایی، اصلاحات).
- **D13:** درگاه `content-completeness` در `skill_score.py` (CMP-001: هر قرارداد بخش ۹
  دارد؟ CMP-002: هر مرجع به ADR موجود لینک می‌دهد؟) — ممیزی اکنون ۶ درگاهی.
- **D14:** `docs/references/06-composition-map.md` — نقشهٔ ترکیب ۵ مرجع در یک جریان فاز.
- **D15:** `docs/use-cases/case-studies/` — ۲۰ مطالعهٔ موردی کامل ۱۰-فازی (یک به‌ازای
  هر دستهٔ ۰۷..۲۶) با کارفرمای فرضی concreet، پیوند به scaffold/Worker/humanizer/skillspector.

### Notes

- نسخه مهارت: **0.0.2**. نسخهٔ مدل سازمانی: **2.0** (دست‌نخورده).
- شمارش‌های سازمانی (۷۹/۳۷۲/۴۲) **تغییری نکرد** — `verify_org.py` سبز.
- هیچ وابستگی اضافه نشد (قید ۱-۲ نقض‌ناپذیر)؛ همهٔ الگوها در PHP خالص بازنویسی شدند.
- هیچ کد از مخازن AGPL/GPL کپی نشد — فقط *ایدهٔ ساختاری* الگوبرداری شد (درس L-005).

## [0.0.1] — 2026-11-25

### Added
- انتشار اولیه مهارت `php-zero-dep` بر پایه سند `SPEC.md`.
- SKILL.md، AGENTS.md (۱۵ قاعده سازمانی + قیدهای سخت)، RULES.json (منبع واحد حقیقت + org_model v2)، MCP.md.
- `docs/` — ۱۷ دسته (۱۰۳ فایل): architecture (۱۷)، reverse-eng (۴)، elliptic-curve (۵)، security (۸)، database (۵)، modern-php (۵)، deployment (۵)، runtime (۲)، infrastructure (۴)، data-structures (۴)، performance (۵)، tooling (۶)، frontend (۷)، use-cases (۶)، vision (۳)، mcp (۵)، org (۱۲ فایل + ۶ زیرپوشه) + `docs/catalog.md`.
- `scaffolds/` — ۲۵ اسکلت PHP خودکفا (strict_types + PHPDoc با @version 0.0.1) + `tools/hello.php` + قرارداد ۷۹ Worker.
- `ADR/` — قالب MADR + ۱۵ تصمیم معماری؛ `council-log.md` و `lessons-learned.md` با محتوای اولیه واقعی.
- `examples/` — ۹ مثال قابل اجرا؛ `tests/` — smoke-test و security-audit.
- `scripts/` — check-links.sh (بازبینی سه‌ماهه منابع) و verify_org.py (گارد رگرسیون شمارش‌های سازمانی).

### Changed — غنی‌سازی مستندات (۲۰۲۶-۱۱-۲۵)

- `SKILL.md` بازنویسی کامل عملیاتی: شروع سریع ایجنت (۵ دقیقه اول)، نمونه‌های فعال‌شدن، دلیل («چرا») هر ۱۴ قید، مطالعه موردی کامل «وبلاگ روی هاست اشتراکی» با هر ۱۰ فاز، شرح هر scaffold، روش راستی‌آزمایی هر درگاه کیفیت، و پرسش‌های متداول موارد لبه (۸ سناریو).
- `AGENTS.md`: هر ۱۵ قاعده سازمانی با دلیل و مثال نقض؛ هر ۱۳ قید تولید کد با جفت مثال درست/غلط؛ سناریوی کامل قاعده منابع خارجی؛ جدول سیاست نسخه PHP.
- `MCP.md`: درخت تصمیم «کی مشورت کنم»، سناریوی کامل مشورت (resolve-library-id → query-docs)، و Playbook خطا (اتصال/401/429/پاسخ نامرتبط).
- `council-log.md` و `lessons-learned.md`: رکوردهای کامل با زمینه/کشف/ریشه/پیشگیری و قالب‌های ثبت.
- `scaffolds/workers/_template.md`: راهنمای پرکردن هر بخش + نمونه تکمیل‌شده واقعی (T-005).
- `evals/evals.json`: از ۳ به ۸ سناریو با روبریک داوری گذر/شکست برای هر معیار.
- `docs/org/`: ۱۳ سند هسته بسط یافت — نقشه مسیرهای خواندن (00-index)، نمونه‌های اجرایی برای سلسله‌مراتب، RACI، شورا (جلسه نمونه با وتو)، مشورت (C-001 گام‌به‌گام)، چرخه Worker (استخدام T-049 از تا تا)، DAG (نمونه چرخه و اصلاح)، پروفایل‌ها (solo چه چیزی را حذف «نمی‌کند») و SoD (نمونه SoD-5).
- `docs/org/atomic-tasks/by-role/`: هر ۷۹ کارت نقش به «کارت کامل» ارتقا یافت (شناسنامه + جدول وظایف ۵ ستونی + مجوزها + مشورت‌ها + ارجاع) — تولیدشده با اسکریپت جدید `scripts/generate_role_cards.py` از RULES.json و قراردادها، با گارد سازگاری (۷۹/۳۷۲).
- `roles/by-level/` (۶ فایل): مقدمه هر سطح و راهنمای استفاده از جدول؛ سرصفحه‌ها اصلاح شد.
- `docs/org/templates/`: قالب ADR و مشورت با نمونه تکمیل‌شده؛ قالب Worker با جدول «داده هر بخش از کجا می‌آید».
- `consultation/log/` و `council/sessions/`: رکوردهای موجود با بررسی ریسک و اقدامات تکمیل شد.
- `docs/vision/01-limitations.md`: هر ۱۶ محدودیت با «چرا» و «راه جایگزین» به‌صورت جدول؛ `02` و `03` با نمونه کد درست/غلط؛ `architecture/16-selection-guide.md` با چهار پرسش تصمیم و دو مثال اجرایی.

### Notes
- نسخه مهارت: **0.0.1** (همه مستندات). نسخه مدل سازمانی: **2.0**.
- شمارش‌های سازمانی (۷۹ نقش / ۳۷۲ وظیفه اتمیک / ۴۲ مجوز) از `RULES.json` تولید می‌شوند — هرگز هاردکد نشوند.

[0.0.2]: https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.2
[0.0.1]: https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1
