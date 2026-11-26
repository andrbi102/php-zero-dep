# کاربرد: بازی و سرگرمی

> نسخه مهارت: **0.0.2** | کد دسته: **۱۵** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **solo→full**

## مرور

دستهٔ بازی/سرگرمی، کاربردهای سرگرمی تعاملی: بازی مرورگری، چندنفره، انجمن بازی،
پازل، قرعه‌کشی، مسابقه، خرید/اشتراک بازی. بازی‌های سبک turn-based یا
حالت‌پایه در PHP ممکن‌اند؛ بازی real-time چندنفره به WebSocket جداگانه نیاز دارد.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 15.1 | بازی آنلاین | حالت + ذخیره + امتیاز | router, database, session, crypto |
| 15.2 | بازی مرورگری | 15.1 سبک + تصویر/صدا (embed) | router, database, session, view |
| 15.3 | بازی چندنفره | turn-based + صف + حالت سمت سرور | router, database, session, rate-limiter |
| 15.4 | انجمن بازی | 5.1 تخصصی بازی + کلان + راهنما | router, database, session, csrf |
| 15.5 | بازی آموزشی | 15.2 + محتوای آموزشی + پیشرفت | router, database, session, view |
| 15.6 | پازل آنلاین | پازل + حل + زمان + رتبه | router, database, session, crypto |
| 15.7 | قرعه‌کشی | ثبت + وزن + قرعه رمزنگاری‌شده + نتیجه | router, database, crypto, session |
| 15.8 | مسابقه آنلاین | ثبت‌نام + سؤال + داوری + جایزه | router, database, session, csrf, crypto |
| 15.9 | استریم بازی | 7.7 | router, database, session, view |
| 15.10 | خرید بازی | 3.1 کلید بازی + تحویل رمز | router, database, crypto, http-client |
| 15.11 | اشتراک بازی | 3.14 (اشتراک) + دسترسی کتابخانه | router, database, session, crypto |

## الگوی مشترک

- **حالت سمت سربر (الزامی):** هر وضعیت بازی در DB؛ کلاینت فقط نمایش؛
  انتقال حالت با درخواست—هیچ منطق بازی در کلاینت قابل اعتماد نیست.
- **turn-based:** صف نوبت در DB + long-polling برای انتظار طرف مقابل.
- **قرعه‌کشی منصفانه (15.7):** seed عمومی قبل از قرعه + commit-reveal
  (libsodium)؛ نتیجه قابل راستی‌آزمایی مستقل.
- **anti-cheat:** timer سمت سرور؛ یک action در لحظه؛ رد حرکت غیرمجاز.
- **امتیاز/رتبه:** رمزنگاری امضا (HMAC) برای جلوگیری از دستکاری سمت کلاینت.

## ✅ Checklist

- [ ] حالت بازی در DB (نه کلاینت)
- [ ] timer سمت سرور
- [ ] قرعه‌کشی commit-reveal (منصفانه)
- [ ] امتیاز امضاشده HMAC
- [ ] rate limit روی action
- [ ] CSRF + session

## ❌ Anti-patterns

- منطق بازی در JavaScript (قابل تقلب)
- timer سمت کلاینت
- قرعه‌کشی بدون commit-reveal (قابل دستکاری)

## ارجاع

- رمزنگاری commit-reveal: [`scaffolds/crypto.php`](../../scaffolds/crypto.php)
- دستهٔ مرتبط: ۷ (استریم)، ۳ (خرید/اشتراک)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/21-games-case-study.md`](case-studies/21-games-case-study.md).
