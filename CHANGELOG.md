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

### Notes
- نسخه مهارت: **0.0.1** (همه مستندات). نسخه مدل سازمانی: **2.0**.
- شمارش‌های سازمانی (۷۹ نقش / ۳۷۲ وظیفه اتمیک / ۴۲ مجوز) از `RULES.json` تولید می‌شوند — هرگز هاردکد نشوند.

[0.0.1]: https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1
