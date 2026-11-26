# کاربرد: غذا، رستوران و خرید روزانه

> نسخه مهارت: **0.0.2** | کد دسته: **۲۰** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ غذا/خرید روزانه، کاربردهای غذا و خرید روزانه: رستوران، سفارش غذا، منو،
رزرو میز، دستور پخت، خرید مواد غذایی، فروشگاه محلی، تحویل سریع. واحد دادهٔ
«سفارش/منو/محصول» است؛ چالش اصلی زمان‌بندی تحویل و موجودی است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 20.1 | رستوران آنلاین | معرفی + منو + رزرو + سفارش | router, database, session, csrf, view |
| 20.2 | سفارش غذا | منو + سبد + آدرس + زمان تحویل + درگاه | router, database, session, csrf, http-client, rate-limiter |
| 20.3 | منوی آنلاین | دسته + غذا + قیمت + تصویر + qr | router, database, view, crypto |
| 20.4 | رزرو میز | 8.6 (رزرو رستوران) | router, database, session, csrf, validator |
| 20.5 | دستور پخت | دسته + مواد + مرحله + تصویر + رأی | router, database, view, cache, validator |
| 20.6 | خرید مواد غذایی | 3.1 + وزن/بسته + موجودی + تحویل | router, database, session, csrf, http-client |
| 20.7 | خرید روزانه | 20.6 + فهرست خرید + پیشنهاد | router, database, session, csrf |
| 20.8 | فروشگاه محلی | 9.4 + geo + موجودی + تحویل محلی | router, database, session, csrf, validator |
| 20.9 | تحویل سریع | سفارش + راننده + مکان + زمان | router, database, session, rate-limiter |
| 20.10 | سفارش تلفنی آنلاین | ثبت تلفنی + پیگیری + رسید | router, database, session, csrf, crypto |

## الگوی مشترک

- **منو/موجودی atomic:** کاهش موجودی با شرط (3 الگو)؛ if out-of-stock،
  غیرفعال خودکار + پیشنهاد جایگزین.
- **زمان تحویل:** محاسبه بر اساس آماده‌سازی + مسافت؛ slot با قفل؛
  یادآوری SMS/email.
- **تحویل سریع (20.9):** درخواست + تطبیق راننده + مکان (long-polling) +
  ردیابی؛ نه WebSocket در PHP خالص.
- **QR منو (20.3):** URL امضاشده HMAC (anti-tamper)؛ بدون نیاز نصب؛
  تصاویر با URL امضاشده.
- **دستور پخت (20.5):** محتوای مشارکتی + رأی + تصویر؛ cache صفحه.
- **idempotency سفارش:** کلید idempotency (3 الگو) جلوی سفارش دوبل با refresh.

## ✅ Checklist

- [ ] موجودی atomic با شرط
- [ ] زمان تحویل محاسبه + slot + یادآوری
- [ ] QR منو با URL امضاشده HMAC
- [ ] idempotency کلید سفارش
- [ ] تحویل سریع با long-polling (نه WebSocket تک‌نخی)
- [ ] rate limit روی سفارش (anti-spam)

## ❌ Anti-patterns

- موجودی در session (قابل دستکاری)
- بدون idempotency (سفارش دوبل با refresh)
- QR منو بدون امضا (قابل دستکاری قیمت)
- تحویل با WebSocket در PHP خالص (بن‌بست)

## ارجاع

- idempotency: [`docs/use-cases/09-ecommerce.md`](09-ecommerce.md)
- دستهٔ مرتبط: ۳ (فروشگاه)، ۸ (رزرو/تحویل)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/26-food-daily-case-study.md`](case-studies/26-food-daily-case-study.md).
