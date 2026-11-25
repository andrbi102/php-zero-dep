<div align="center">

# 🐘 php-zero-dep

**مهارت PHP بدون وابستگی — برای ایجنت‌های کدنویسی و انسان‌هایی که از Composer خسته شده‌اند**

[![Version](https://img.shields.io/badge/version-0.0.1-2ea44f)](https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/supported-versions.php)
[![Dependencies](https://img.shields.io/badge/dependencies-0-brightgreen)](#چرا-بدون-composer)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)
[![Files](https://img.shields.io/badge/%D9%81%D8%A7%DB%8C%D9%84-360-informational)](#نقشه-بسته)

</div>

---

## داستان این مخزن

بیشتر پروژه‌های PHP با صدها وابستگی شروع می‌شوند: `composer install`، سپس
`vendor/` با ده‌ها هزار فایل، سپس به‌روزرسانی‌های امنیتی بی‌پایان، و روزی که
هاست اشتراکی از نصب SSH خبر نمی‌دهد، همه‌چیز می‌خوابد.

این مخزن از یک پرسش ساده متولد شد:

> **اگر هیچ‌چیز نباید نصب می‌شد، یک مهارت PHP چه شکلی می‌گرفت؟**

`php-zero-dep` پاسخ است: یک skill کامل برای ایجنت‌های کدنویسی (Claude،
Cursor، Copilot و هر ایجنت دیگری که فایل راهنما می‌خواند) که کد PHP
**آماده production** تولید می‌کند — فقط با PHP 8.2+ و افزونه‌های هسته.
نه Composer، نه vendor، نه کتابخانه ثالث.

فلسفه ما یک جمله است: **بازآفرینی آگاهانه چرخ.** اتولودر، روتر، DI،
Event Bus — همه دست‌نویس، همه خودکفا، همه با استانداردهای امنیتی سخت‌گیرانه.
چرخ را دوباره اختراع می‌کنیم، اما این بار با دونده‌ای که می‌داند چرا هر
پره شکل خاصی دارد.

## چرا بدون Composer؟

چهار دلیل صادقانه:

1. **هاست اشتراکی واقعیت بازار است** — میلیون‌ها سایت روی هاستی هستند که
   SSH و Composer ندارند. کد این مخزن را با FTP کپی می‌کنید و اجرا می‌شود.
2. **زنجیره تأمین کوچک‌تر = حمله‌سطح کمتر** — وقتی وابستگی ندارید، خبری از
   `package:dist` آلوده، وابستگی ربوده‌شده و زنجیره ماژول مرده نیست.
3. **ایجنت AI کور وابستگی است** — مدل زبانی نسخه واقعی نصب‌شده را نمی‌بیند؛
   وقتی همه‌چیز در مخزن خودتان است، آنچه می‌بیند همان چیزی است که اجرا می‌شود.
4. **قابل‌خواندن برای انسان** — هر فایل یک کلاس، هر کلاس خودکفا؛ کد را
   می‌خوانید و می‌فهمید، نه این‌که از پشت پنج لایه abstraction عبور کنید.

و بله، معامله‌ای در میان است: نگه‌داری این اجزای دست‌نویس بر عهده شماست.
ما این را پنهان نمی‌کنیم — ۱۶ محدودیت مستندشده در
[`docs/vision/01-limitations.md`](docs/vision/01-limitations.md)
بیان می‌کنند دقیقاً کجاها این رویکرد جواب نمی‌دهد.

## امنیت به‌عنوان پیش‌فرض، نه افزونه

هر خط کدی که از این مهارت بیرون می‌آید، این قواعد را دارد (نقض = خروجی مردود):

| پیش‌فرض | اجرا |
|---------|------|
| کوئری دیتابیس | فقط PDO Prepared Statement — از `scaffolds/database.php` |
| خروجی HTML | `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` از `View::e()` |
| فرم | توکن CSRF امضاشده با HMAC — از `scaffolds/csrf.php` |
| رمز عبور | `password_hash(PASSWORD_ARGON2ID)` — هیچ الگوریتمی دیگر |
| کوکی نشست | `HttpOnly` + `Secure` + `SameSite=Strict` |
| هدرهای امنیتی | CSP، HSTS، X-Content-Type-Options، X-Frame-Options، Referrer-Policy |
| توابع ممنوع | `eval`، `exec`، `shell_exec`، `system`، `passthru`، `proc_open`، `popen` |
| خطا | هیچ شکست خاموشی — لاگ با context یا rethrow |
| کیفیت ایستا | `declare(strict_types=1)` در همه فایل‌ها + PHPDoc سه‌فیلدی |
| توهم API | ممنوع — پیش از تولید کد کتابخانه خارجی، مشورت اجباری با MCP (`MCP.md`) |

## شروع سریع

**راه ۱ — کلون مخزن:**

```bash
git clone https://github.com/andrbi102/php-zero-dep.git
```

**راه ۲ — دانلود بسته آماده (Release v0.0.1):**

از بخش
[Releases](https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1)
فایل `php-zero-dep-0.0.1.zip` را بگیرید — همان بسته‌ای که در CI این مخزن
راستی‌آزمایی شده است.

**سپس سه قدم:**

1. پوشه را به‌عنوان skill به ایجنت خود بدهید (یا فایل `SKILL.md` را مستقیم
   به پرامپت اضافه کنید).
2. پروژه PHP خود را توصیف کنید — سایت، فروشگاه، API، CMS، ابزار CLI؛ مهارت
   خودش فازها را از `SKILL.md` برمی‌دارد.
3. خروجی را با `php tests/smoke-test.php` و `php tests/security-audit.php`
   راستی‌آزمایی کنید.

اول `SKILL.md` خوانده می‌شود؛ عمق بعدی (`docs/<topic>/`، `scaffolds/`) فقط
وقتی باز می‌شود که واقعاً لازم است — مدل Progressive Disclosure، نه
اسلایُد اطلاعات.

## نقشه بسته

| مسیر | چه چیزی داخل است |
|------|------------------|
| `SKILL.md` | نقطه ورود مهارت: قیدهای سخت، گردش کار ۱۰ فازی، نقشه کشف تدریجی |
| `RULES.json` | منبع واحد حقیقت — شمارش‌های سازمانی از همین فایل تولید می‌شوند، هرگز هاردکد |
| `AGENTS.md` | ۱۵ قاعده سازمانی + قیدهای سخت برای ایجنت |
| `MCP.md` | پروتکل مشورت مستندات (Context7، Tier-1/2) — ضد توهم API |
| `SPEC.md` | سند مبدأ کامل — هر چیزی که بقیه خلاصه‌اش هستند |
| `docs/` | **۲۱۴ سند مرجع در ۱۷ دسته**: معماری (۱۷)، امنیت (۸)، منحنی بیضوی (۵)، دیتابیس (۵)، PHP مدرن (۵)، استقرار (۵)، عملکرد (۵)، ابزار (۶)، فرانت‌اند (۷)، کاربردها (۶)، مهندسی معکوس (۴)، ساختار داده (۴)، زیرساخت (۴)، runtime (۲)، MCP (۵)، چشم‌انداز (۳)، مدل سازمانی (۱۲۲) + [`catalog.md`](docs/catalog.md) |
| `scaffolds/` | **۲۶ فایل PHP خودکفا**: اتولودر، روتر، DI Container، Event Dispatcher، Command/Query Bus، Pipeline، Session، CSRF، Validator، Crypto، Cache، Rate Limiter، HTTP Client، Tracing، Logger، MCP Server و … |
| `scaffolds/workers/` | **۷۹ قرارداد Worker** کامل (T-001 تا T-079) + قالب ۸ بخشی |
| `examples/` | **۹ مثال قابل اجرا** — `php examples/01-router-demo.php` |
| `tests/` | smoke-test + security-audit (ممیزی ایستا: توابع ممنوع، strict_types، رد Composer) |
| `ADR/` | **۱۴ تصمیم معماری** مستند با قالب MADR + قالب خالی |
| `scripts/` | `verify_org.py` (گارد رگرسیون شمارش‌ها) + `check-links.sh` (بازبینی سه‌ماهه منابع) |
| `evals/` | سناریوهای سنجش کیفیت مهارت |
| `council-log.md` / `lessons-learned.md` | صورت‌جلسات شورا و درس‌های واقعی آموخته‌شده |

**جمع کل: ۳۶۰ فایل، ۳۱۷ سند Markdown، ۳۷ فایل PHP — همه خودکفا.**

## مثال‌ها — در یک نگاه

| فایل | چه چیزی نشان می‌دهد |
|------|---------------------|
| `01-router-demo.php` | روتر با پارامتر مسیر و متدهای HTTP |
| `02-form-csrf-demo.php` | فرم با توکن CSRF امضاشده HMAC |
| `03-pdo-crud-demo.php` | CRUD کامل با PDO Prepared Statement |
| `04-event-dispatcher-demo.php` | انتشار و شنود رویداد |
| `05-pipeline-demo.php` | زنجیره middleware |
| `06-command-bus-demo.php` | جداسازی فرمان از کوئری |
| `07-graph-bfs-demo.php` | پیمایش گراف BFS در PHP خالص |
| `08-session-login-demo.php` | نشست امن + `session_regenerate_id` پس از ورود |
| `09-mcp-tool-demo.php` | ابزار MCP در PHP بدون وابستگی |

```bash
php examples/03-pdo-crud-demo.php   # همین حالا اجرا کنید — نیاز به نصب ندارد
```

## مدل سازمانی — وقتی یک ایجنت کافی نیست

پروژه‌های بزرگ به ساختار نیاز دارند. این مهارت یک مدل سازمانی کامل داخل خودش
دارد — نه دکوراتیو، بلکه با قرارداد و گیت واقعی:

**۵ سطح | ۷۹ نقش | ۳۷۲ وظیفه اتمیک | ۴۲ مجوز | ۱۰ فاز | شورای ۶+۴**

- وتوی امنیتی **مطلق** است — هیچ مرحله‌ای بالاتر از آن نیست.
- تأیید انتشار: ۲ امضا (RM + ۱ شورا) — اجرای انتشار: ۵ امضا (۴ شورا + RM).
- هر تصمیم معماری یک ADR با امضای شورای معماری دارد.
- دو پروفایل آماده: **solo** (۷ فاز، تا ۷ Worker هم‌زمان) و **full** (۱۰ فاز، بی‌سقف).

شرح کامل: [`docs/org/`](docs/org/) — و شمارش‌ها را هرگز از متن باور نکنید؛
خودتان اجرا کنید:

```bash
python3 scripts/verify_org.py   # گارد رگرسیون: ۷۹/۳۷۲/۴۲ از RULES.json
```

## راستی‌آزمایی بسته

```bash
php tests/smoke-test.php        # دود همه اسکلت‌ها (exit 0 = PASS)
php tests/security-audit.php    # ممیزی ایستا: توابع ممنوع، strict_types، رد Composer
python3 scripts/verify_org.py   # شمارش‌های سازمانی از RULES.json (۷۹/۳۷۲/۴۲)
bash scripts/check-links.sh     # بازبینی HTTP 200 لینک‌های کاتالوگ (سه‌ماهه)
```

## محدودیت‌ها — صادقانه، همان‌طور که هست

یک مهارت خوب محدودیت‌های خود را می‌شناسد. ۱۶ محدودیت مستندشده در
[`docs/vision/01-limitations.md`](docs/vision/01-limitations.md) — از نبود
اعتبارسنجی سمت کلاینت در PHP خالص تا اجرا نشدن Xdebug روی FrankenPHP musl.
اگر پروژه شما به اکوسیستم Composer نیاز دارد، ما به شما می‌گوییم و
دست‌تان را هم نمی‌گیریم — صادق بودن بخشی از قرارداد است.

## برای چه کسانی؟

- توسعه‌دهنده‌ای که روی **هاست اشتراکی بدون SSH** کار می‌کند.
- تیمی که **زنجیره تأمین** را کوچک و قابل‌ممیزی می‌خواهد.
- سازنده‌ای که از ایجنت کدنویسی می‌خواهد **کد production امن** بدهد، نه اسکلت مک‌کی.
- کسی که PHP را به‌عنوان زبان جدی می‌بیند و از «PHP یعنی WordPress» خسته است.

## مشارکت

1. `SPEC.md` را بخوانید — منبع همه قواعد.
2. `lessons-learned.md` را از دست ندهید — درس‌های واقعی از اجرای واقعی.
3. شمارش‌های سازمانی را هاردکد نکنید (`RULES.json` منبع است — درس L-001).
4. مخزن جدید به کاتالوگ؟ PR بزنید؛ درس جدید؟ `lessons-learned.md`.

این مهارت یک «پرامپت زنده» است — همیشه در حال تکامل، اما هرگز به سمت
وابستگی خارجی.

## English TL;DR

**php-zero-dep** is a zero-dependency PHP skill for AI coding agents:
production-ready PHP with only PHP 8.2+ and core extensions — no Composer,
no vendor directory. 26 self-contained PHP scaffolds (autoloader, router,
DI, event bus, PDO layer, HMAC-signed CSRF, Argon2id, secure sessions),
214 reference docs in 17 categories, 79 AI-worker contracts, 14 architecture
decision records, 9 runnable examples, smoke + security tests. Security is
default, not a plugin. The docs are in Persian; the code speaks English.

## مجوز

[MIT](LICENSE) — استفاده، تغییر و انتشار آزاد. اگر این مخزن به کارتان آمد،
یک ستاره هم فراموش نشود.
