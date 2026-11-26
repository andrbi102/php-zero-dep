# کاربرد: تقویم، رزرو، سفر و حمل‌ونقل

> نسخه مهارت: **0.0.2** | کد دسته: **۸** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ رزرو، کاربردهای زمان‌بندی و تخصیص منبع زمانی: تقویم، رزرو نوبت/هتل/بلیط/
رستوران، سفارش غذا، گردشگری، نقشه، مسیریابی، تاکسی، پارکینگ. واحد دادهٔ
«slot/رزرو» است؛ چالش اصلی **قفل هم‌زمانی** روی slot است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 8.1 | تقویم آنلاین | رویداد + تکرار + یادآوری | router, database, session, csrf, view |
| 8.2 | مدیریت رویداد | 8.1 + ثبت‌نام + вместимость + بلیط | router, database, session, csrf, validator |
| 8.3 | رزرو نوبت | slot + قفل رکوردی + یادآوری | router, database, session, csrf, validator |
| 8.4 | رزرو هتل | اتاق + تاریخ + موجودی + قیمت | router, database, session, csrf, http-client |
| 8.5 | رزرو بلیط | صندلی + قفل + پرداخت + QR | router, database, session, csrf, crypto |
| 8.6 | رزرو رستوران | میز + زمان + اندازه گروه | router, database, session, csrf, validator |
| 8.7 | سفارش غذا | منو + سبد + آدرس + زمان تحویل | router, database, session, csrf, rate-limiter |
| 8.8 | گردشگری | تور + قیمت + رزرو + گالری | router, database, view, cache |
| 8.9 | نقشه آنلاین | embed (OSM/Google) + نقطه | router, view, database |
| 8.10 | مسیریابی | API خارجی (MCP) + cache مسیر | http-client, cache, database |
| 8.11 | تاکسی اینترنتی | درخواست + مکان + تطبیق راننده + قیمت | router, database, session, rate-limiter |
| 8.12 | حمل‌ونقل | ردیابی + زمان‌بندی + مسیر | router, database, view, cache |
| 8.13 | پارکینگ آنلاین | مکان + زمان + پرداخت + تمدید | router, database, session, csrf, http-client |
| 8.14 | شارژ خودرو | ایستگاه + وضعیت + رزرو + پرداخت | router, database, session, csrf |
| 8.15 | برنامه سفر | روز/فعالیت + نقشه + بودجه | router, database, session, view |

## الگوی مشترک

- **قفل slot (مهم):** `UPDATE slots SET status='held' WHERE id=? AND status='free'`
  با affected rows؛ اگر ۰ → تلاش مجدد یا اعلام. hold با TTL (مثلاً ۵ دقیقه)؛
  اگر پرداخت نشد، آزاد خودکار.
- **idempotency:** کلید idempotency در رزرو (جلوگیری از رزرو دوبل با refresh).
- **یادآوری:** cron/email (یا صف)؛ نه در request.
- **تقویم تکرار:** rule-based (RRULE ساده)؛ تولید نمونه‌ها در بازه درخواستی.
- **مسیریابی:** API خارجی با MCP (`MCP.md`)؛ cache مسیر پراستفاده.
- **QR بلیط:** تولید با تصویر (`ext-gd`) + امضای HMAC (anti-jift).

## ✅ Checklist

- [ ] slot با قفل رکوردی (atomic) + TTL
- [ ] idempotency کلید رزرو
- [ ] یادآوری خارج از request (cron/صف)
- [ ] QR بلیط امضاشده HMAC
- [ ] مسیریابی از MCP + cache
- [ ] CSRF + اعتبارسنجی تاریخ/زمان

## ❌ Anti-patterns

- رزرو بدون قفل (double-booking با هم‌زمانی)
- یادآوری در request (کند)
- QR بدون امضا (قابل جعل)

## ارجاع

- قفل هم‌زمانی: [`docs/database/04-optimization.md`](../database/04-optimization.md)
- دستهٔ مرتبط: ۳ (پرداخت)، ۲۰ (غذا/رستوران)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/14-booking-case-study.md`](case-studies/14-booking-case-study.md).
