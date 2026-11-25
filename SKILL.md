---
name: php-zero-dep
version: 0.0.1
description: >-
  تولید کد PHP آماده production بدون هیچ وابستگی خارجی — بدون Composer، بدون
  vendor، بدون فریمورک؛ فقط PHP 8.2+ و افزونه‌های هسته، با امنیت‌محوری کامل
  (OWASP، Argon2id، CSRF امضاشده HMAC، Prepared Statement، escape خودکار) و
  مدل سازمانی ساخت‌یافته (فاز، نقش، وظیفه اتمیک، شورا). Use this skill
  whenever the user asks to build, scaffold, review, or upgrade ANYTHING in
  PHP — a website, REST API, ecommerce store, CMS, blog, CLI tool, framework,
  or PHP skill — even if they don't say "zero-dependency". Also trigger on:
  پروژه PHP، سایت PHP، فروشگاه، وبلاگ، API، بدون Composer، صفر وابستگی،
  PHP خالص، هاست اشتراکی بدون SSH، ارتقای کد PHP، امن‌سازی PHP، دست‌نویس
  autoloader/router/DI. Do NOT use for non-PHP projects.
---

# 🐘 php-zero-dep — مهارت PHP بدون وابستگی

> **نسخه مهارت: `0.0.1`** | سند مبدأ: `SPEC.md` | مجوز: MIT
> فلسفه: **بازآفرینی آگاهانه چرخ** — اتولودر، روتر، DI، Event Bus همه
> دست‌نویس و خودکفا؛ اما هرگز به سمت وابستگی خارجی منحرف نمی‌شویم.

## ۱. کی این مهارت فعال شود

- کاربر هر چیز PHP می‌خواهد بسازد: وب‌سایت، فروشگاه، API، CMS، وبلاگ، ابزار CLI.
- کاربر Composer ندارد یا نمی‌خواهد: هاست اشتراکی، محیط ایزوله، سادگی.
- بازبینی/ارتقای کد PHP موجود: امن‌سازی، مهاجرت نسخه، حذف وابستگی.
- مستندسازی صادقانه محدودیت‌ها بخشی از خروجی است — هرگز محدودیت را پنهان نکن.

## ۲. قیدهای سخت (نقض = خروجی مردود)

1. **Zero-Composer**: هیچ `composer.json`/`vendor/` تولید یا ارجاع نشود.
2. **Zero-Dependency**: فقط PHP 8.2+ و افزونه‌های مجاز (فهرست کامل: `docs/vision/02-pure-php-boundary.md`).
3. هر فایل PHP با `declare(strict_types=1);` + PHPDoc شامل `@package`, `@version`, `@security-level`.
4. هر کلاس تک‌فایل و خودکفا؛ اجزای تکراری از `scaffolds/` برداشته می‌شوند، نه بازنویسی.
5. همه کوئری‌ها PDO Prepared Statement (از `scaffolds/database.php`).
6. همه خروجی‌ها escape: `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` (از `View::e()`).
7. کوکی نشست: HttpOnly + Secure + SameSite=Strict (از `scaffolds/session.php`).
8. همه فرم‌ها توکن CSRF امضاشده HMAC (از `scaffolds/csrf.php`).
9. رمز عبور: `password_hash(PASSWORD_ARGON2ID)`.
10. هیچ شکست خاموشی: لاگ یا rethrow با context.
11. هدرهای امنیتی: CSP، HSTS، X-Content-Type-Options، X-Frame-Options، Referrer-Policy.
12. توابع ممنوع: `eval()`, `exec()`, `shell_exec()`, `system()`, `passthru()`, `proc_open()`, `popen()` — مگر با مستندسازی صریح و sandbox.
13. **بدون توهم API**: پیش از تولید کد برای API کتابخانه خارجی، ابتدا Context7 MCP
    با `resolve-library-id` سپس `query-docs` (زبان PHP، نسخه صریح). نبود آن = اعلام
    محدودیت، نه حدس. جزئیات: `MCP.md`.
14. **نسخه PHP**: اگر نامشخص است از **8.3** استفاده کن (8.2 در 2026-12-31 EOL)؛
    8.4/8.5 فقط با تأیید صریح کاربر. راهنما: `docs/modern-php/05-php-8.6-detection.md`.

## ۳. گردش کار تولید

### گام ۰ — انتخاب پروفایل

| پروفایل | برای | فازها | سقف Worker هم‌زمان |
|---------|------|-------|--------------------|
| **solo** | پروژه کوچک (سایت، ابزار، وبلاگ) | F1,F2,F4,F5,F6,F9,F10 | ۷ |
| **full** | پروژه بزرگ (فروشگاه، پلتفرم) | F1..F10 | بدون سقف |

### گام ۱ — فازها (۱۰ فاز، خلاصه)

| فاز | نام | خروجی | حداقل حضور |
|-----|-----|--------|------------|
| F1 | تحلیل نیاز | `requirements.md` | ۱ سطح ۱ |
| F2 | طراحی اولیه | `design-draft.md` + ADR | ۲ سطح ۱ |
| F3 | تأیید طراحی | `design-approved.md` | ۵ سطح ۰ |
| F4 | پیاده‌سازی | کد + PHPDoc (از scaffolds) | ۱ سطح ۳ |
| F5 | بازبینی کد | `review.md` | ۱ سطح ۲ |
| F6 | تست | `test-report.md` + smoke/security | ۱ سطح ۱ |
| F7 | بازبینی امنیتی | `security-signoff.md` | ۱ سطح ۰ |
| F8 | مستندسازی | `docs/*` + README | ۱ سطح ۳ |
| F9 | آماده‌سازی انتشار | `RELEASE.md` + `VERSION` | ۲ سطح ۱ + ۱ سطح ۰ |
| F10 | انتشار | Git tag + CHANGELOG | ۴ سطح ۰ |

فقط نقش‌های لازم فاز جاری فعال می‌شوند؛ شرح کامل فازها و وظایف هر فاز:
`docs/org/atomic-tasks/by-phase/F1..F10.md`.

### گام ۲ — استخدام Worker (چرخه ۸ گامی)

هر نقش = یک Worker با قرارداد ۸ بخشی (Identity / Task / Authority /
Relationships / Budget / DoD / Escalation / Lifecycle) — قالب:
`scaffolds/workers/_template.md`، نمونه‌های تکمیل‌شده: `scaffolds/workers/T-*.md`
(۷۹ نقش). چرخه: Requirement → Hiring → Contract → Onboarding → Execution →
Review → Dissolution → Retrospective (`docs/org/08-lifecycle.md`).

### گام ۳ — دروازه‌های شورا (نقض‌ناپذیر)

- وتوی امنیتی **مطلق** است (Council Security — T-001).
- تأیید انتشار = ۲ امضا (RM + ۱ شورا)؛ اجرای انتشار = ۵ امضا (۴ شورا + RM).
- هر تصمیم معماری یک ADR با امضای Council Architect دارد (قالب: `ADR/0000-template.md`).

## ۴. نقشه بسته (Progressive Disclosure)

| نیاز | مسیر |
|------|------|
| قواعد ایجنت و شمارش سازمانی | `RULES.json` (منبع حقیقت) + `AGENTS.md` |
| مشورت مستندات API | `MCP.md` (Context7، Tier-1/2، سرور MCP PHP) |
| کامپوننت آماده | `scaffolds/` — جدول پایین |
| معماری و انتخاب | `docs/architecture/` (۱۷ فایل؛ راهنمای انتخاب: 16) |
| امنیت | `docs/security/` (۸) + `docs/elliptic-curve/` (۵) |
| دیتابیس | `docs/database/` (۵) |
| PHP مدرن | `docs/modern-php/` (۵) |
| استقرار | `docs/deployment/` (۵) + `docs/runtime/` (۲) + `docs/infrastructure/` (۴) |
| عملکرد | `docs/performance/` (۵) |
| ابزار dev | `docs/tooling/` (۶) |
| فرانت‌اند | `docs/frontend/` (۷) |
| کاربردها | `docs/use-cases/` (۶) |
| محدودیت‌ها | `docs/vision/` (۳) — همیشه صادقانه |
| مهندسی معکوس | `docs/reverse-eng/` (۴) |
| مدل سازمانی | `docs/org/` (۱۲ فایل + زیرپوشه‌ها) |
| کاتالوگ مخازن تأییدشده | `docs/catalog.md` (بازبینی سه‌ماهه: `scripts/check-links.sh`) |
| سند مبدأ کامل | `SPEC.md` |

### scaffolds/ (۲۵ اسکلت + tools + workers)

| دسته | فایل‌ها |
|------|---------|
| هسته | `autoloader.php`، `router.php`، `container.php`، `event-dispatcher.php`، `command-bus.php`، `query-bus.php`، `pipeline.php`، `hmvc-dispatcher.php` |
| داده | `database.php`، `event-store.php`، `db-migrate.php`، `graph.php` |
| امنیت | `session.php`، `csrf.php`، `validator.php`، `crypto.php` |
| نمایش | `view.php` |
| عملکرد/زیرساخت | `cache.php`، `rate-limiter.php`، `http-client.php`، `tracing.php`، `serverless-handler.php` |
| مشاهده/خطا | `logger.php`، `error-handler.php` |
| MCP | `mcp-server.php` + `tools/hello.php` |
| سازمان | `workers/` (۷۹ قرارداد + `_template.md`) |

## ۵. درگاه‌های کیفیت (پیش از هر تحویل)

- [ ] هیچ `composer.json` / `vendor/` وجود ندارد
- [ ] همه فایل‌ها `declare(strict_types=1);` دارند
- [ ] همه کوئری‌ها Prepared؛ همه خروجی‌ها escape؛ همه فرم‌ها CSRF
- [ ] `session_regenerate_id()` پس از login؛ کوکی‌ها امن؛ Argon2id
- [ ] هدرهای امنیتی ۵گانه تنظیم شده‌اند
- [ ] بدون توابع ممنوع؛ کد vendored با لایسنس ثبت‌شده
- [ ] Domain از PDO/HTTP جدا (اگر DDD/Hexagonal)
- [ ] ADR برای هر تصمیم معماری؛ DAG بدون چرخه
- [ ] تست‌ها سبز: `php tests/smoke-test.php` و `php tests/security-audit.php`
- [ ] محدودیت‌های اعمال‌نشده در README ذکر شده‌اند

چک‌لیست کامل پذیرش: `SPEC.md` بخش ۱۴.

## ۶. راستی‌آزمایی بسته

```bash
php tests/smoke-test.php        # دود همه اسکلت‌ها (exit 0 = PASS)
php tests/security-audit.php    # ممیزی ایستا
python3 scripts/verify_org.py   # گارد ۷۹/۳۷۲/۴۲ و SoD
bash scripts/check-links.sh     # بازبینی منابع (هر ۳ ماه)
```

## ۷. نسخه‌گذاری و بازبینی

- نسخه مهارت: **0.0.1** (این فایل، `VERSION`، `CHANGELOG.md` و همه مستندات یکدست).
- SemVer + Keep a Changelog + Git tag در هر انتشار (Release Manager — T-005).
- منابع کاتالوگ هر ۳ ماه با `scripts/check-links.sh` بازبینی می‌شوند؛ ادعاهای
  عددی فقط با برچسب «خوداظهاری» می‌مانند.
- شمارش‌های سازمانی همیشه از `RULES.json` تولید می‌شوند — هرگز در متن هاردکد نشوند
  (درس L-001 در `lessons-learned.md`).

> این مهارت یک «پرامپت زنده» است: مخزن جدید → کاتالوگ؛ درس جدید →
> `lessons-learned.md`. همیشه در حال تکامل — اما هرگز به سمت وابستگی خارجی.
