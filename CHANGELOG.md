# Changelog — php-zero-dep

همه تغییرات قابل‌توجه این مهارت در این فایل ثبت می‌شود.
قالب: [Keep a Changelog](https://keepachangelog.com/) — نسخه‌بندی: [SemVer](https://semver.org/).

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

[0.0.1]: https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1
