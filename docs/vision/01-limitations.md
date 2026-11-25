# محدودیت‌های مستند (۱۶ مورد بخش ۱۲)

> نسخه مهارت: **0.0.2** | قاعده بنیادین: مهارتِ خوب محدودیت‌هایش را می‌شناسد
> و به کاربر می‌گوید — پنهان‌کردن محدودیت، نقض قاعده صادقانه‌بودن است.

۱۶ محدودیت، هرکدام با «چرا» و «راه جایگزین»:

| # | محدودیت | چرا | راه جایگزین |
|---|----------|-----|-------------|
| ۱ | CV با PHP خالص عملی نیست | php-opencv خارج از مرز افزونه‌های مجاز است | سرویس CV بیرونی از `http-client.php` یا ابزار dev جدا |
| ۲ | Playwright PHP نیازمند Composer | پکیج رسمی از Composer عبور می‌کند | Playwright MCP (Tier-2، فقط dev) |
| ۳ | sodium_compat فقط vendored | کتابخانه جایگزین ext-sodium خودش وابستگی است | ext-sodium (مجاز) یا openssl از هسته |
| ۴ | گراف دستی | پیاده‌سازی graph سبک است اما الگوریتم‌های سنگین نه | `scaffolds/graph.php` (BFS + چرخه) برای نیازهای متعارف |
| ۵ | ویژگی DB به نسخه وابسته | UPSERT/RETURNING بین MySQL/MariaDB/Postgres تفاوت دارد | dialect-aware در `scaffolds/database.php` |
| ۶ | Nginx بدون FPM نه | Nginx به‌خودی‌خود PHP اجرا نمی‌کند | php-fpm یا Apache module روی هاست اشتراکی |
| ۷ | هاست اشتراکی بدون CLI | migration خط فرمان ممکن نیست | مهاجرت از مرورگر با توکن یک‌بارمصرف (T-061) |
| ۸ | REA مجوز | مهندسی معکوس محدودیت حقوقی دارد | فقط کد با مجوز سازگار؛ ثبت منبع (docs/reverse-eng/) |
| ۹ | serverless بدون FS پایدار | فایل بین درخواست‌ها حذف می‌شود | `serverless-handler.php` + state بیرونی |
| ۱۰ | Actor واقعی نه | PHP اشتراک حافظه سبک ندارد | الگوی Queue + Worker (ADR-0014) |
| ۱۱ | MCP: ابزار توسعه نه وابستگی runtime | سرور MCP فرایند جداگانه است | تولید کدِ مشورت‌محور؛ خروجی بدون MCP اجرا می‌شود |
| ۱۲ | FrankenPHP musl بدون Xdebug | بیلد musl دیباگر را حذف می‌کند | ردیابی با `scaffolds/tracing.php` و logger |
| ۱۳ | RoadRunner نیاز به باینری rr | باینری Go خارج از PHP است | runtime استاندارد FPM/FrankenPHP |
| ۱۴ | Podman روی هاست اشتراکی نیست | کانتینر به دسترسی هسته نیاز دارد | Docker/Podman فقط در dev (docs/infrastructure/) |
| ۱۵ | Nix فقط dev | محیط بازتولیدپذیر ابزار توسعه است نه runtime | راهنمای dev در docs/tooling/ |
| ۱۶ | OpenTelemetry Zero-Code نه | auto-instrumentation به افزونه/پکیج وابسته است | Manual Tracing با `scaffolds/tracing.php` (سازگار W3C) |

### ✅ Checklist

- [ ] هر محدودیت در README محصول ذکر می‌شود
- [ ] راه‌حل جایگزین برای هر مورد موجود است

### ❌ Anti-patterns
- پنهان کردن محدودیت از کاربر نهایی
- «بعداً حلش می‌کنیم» بدون ثبت در مستندات

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
