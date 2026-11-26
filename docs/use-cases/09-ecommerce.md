# کاربرد: تجارت الکترونیک و فروشگاه

> نسخه مهارت: **0.0.2** | کد دسته: **۳** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ تجارت الکترونیک، کاربردهای فروش کالا/خدمت/دیجیتال: فروشگاه،
بازارگاه چندفروشندگی، حراج، خرید گروهی، سبد خرید، کد تخفیف، مدیریت
مرجوعی. واحد دادهٔ اصلی «سفارش/اقلام» است؛ تفاوت‌ها در مدل موجودی،
پرداخت، و چندفروشندگی هستند. این دسته **full** است چون پرداخت + احراز
هویت + موجودی هم‌زمان لازم است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت php-zero-dep | scaffolds کلیدی |
|----|--------|--------------------------|------------------|
| 3.1 | فروشگاه اینترنتی | کاتالوگ + سبد + سفارش + درگاه پرداخت | router, database, session, csrf, http-client, crypto |
| 3.2 | فروشگاه تخصصی | 3.1 با فیلتر خاص دامنه (مثلاً قطعات) | router, database, validator |
| 3.3 | فروشگاه چندفروشندگی | 3.1 + حساب فروشنده + کمیسیون + پنل جدا | router, database, session, csrf, crypto |
| 3.4 | بازارگاه آنلاین | 3.3 + جستجوی cross-vendor + مقایسه | router, database, view, cache |
| 3.5 | حراج آنلاین | 3.1 + timer حراج + بالاترین پیشنهاد + قفل رکوردی | router, database, session, csrf, rate-limiter |
| 3.6 | خرید گروهی | 3.1 + حد نصاب + زمان پایان + فعال‌سازی خودکار | router, database, session, csrf |
| 3.7 | مقایسه قیمت | crawler سبک (`web-fetch.php`) + ذخیره + مقایسه | web-fetch, database, cache |
| 3.8 | کاتالوگ آنلاین | نمایش بدون سبد (فقط اطلاعات) | router, database, view, cache |
| 3.9 | سفارش آنلاین | 3.1 ساده + فرم سفارش | router, database, session, csrf |
| 3.10 | پیگیری سفارش | کد پیگیری + وضعیت + تاریخچه | router, database, view |
| 3.11 | سبد خرید آنلاین | session-based cart (نه DB تا checkout) | session, database, validator |
| 3.12 | فروش دیجیتال | 3.1 با تحویل فایل محافظت‌شده (توکن یک‌بارمصرف) | router, database, crypto, rate-limiter |
| 3.13 | فروش فایل | 3.12 ساده + watermark خریدار | router, database, crypto |
| 3.14 | فروش اشتراک | پرداخت دوره‌ای + مدیریت عضویت + انقضا | router, database, session, crypto, http-client |
| 3.15 | مدیریت عضویت | نقش + سطح دسترسی + تاریخچه اشتراک | router, database, session, crypto |
| 3.16 | کد تخفیف | کد + نوع (درصد/مبلغ) + سقف استفاده + انقضا | router, database, validator, crypto |
| 3.17 | کوپن آنلاین | 3.16 + QR/بارکد تولید | router, database, view |
| 3.18 | پیشنهاد ویژه | 3.16 + هدف‌گیری (کاربر/دسته) + زمان‌بندی | router, database, session, cache |
| 3.19 | مدیریت مرجوعی | درخواست مرجوعی + وضعیت + بازپرداخت | router, database, session, csrf, http-client |

## الگوی مشترک

- **idempotency سفارش:** کلید idempotency در جدول سفارش (جلوگیری از
  سفارش دوبل با refresh یا retry درگاه). درگاه پرداخت را با توکن idempotency
  فراخوانی کن.
- **موجودی atomic:** کاهش موجودی با `UPDATE products SET stock=stock-? WHERE
  id=? AND stock>=?` (شرط stock>=? جلوی فروش منفی را می‌گیرد)؛ بررسی
  affected rows.
- **درگاه پرداخت:** ابتدا MCP (`MCP.md`) برای مستندات درگاه؛ ارتباط فقط از
  `http-client.php`؛ پاسخ درگاه با `validator.php` اعتبارسنجی (هرگز blind
  trust)؛ کلید مرچنت از env.
- **سبد در session:** تا checkout، سبد در session نگه داشته می‌شود؛ در
  checkout به سفارش DB تبدیل.
- **کد تخفیف:** هش HMAC برای جلوگیری از حدس/تکرار؛ `hash_equals` در بررسی.

## ✅ Checklist

- [ ] idempotency کلید سفارش (جلوگیری از سفارش دوبل)
- [ ] موجودی atomic (شرط stock>=?)
- [ ] درگاه پرداخت: مستندات از MCP، کلید از env، پاسخ اعتبارسنجی‌شده
- [ ] کد تخفیف HMAC + سقف استفاده + انقضا
- [ ] تحویل فایل دیجیتال با توکن یک‌بارمصرف + path traversal بسته
- [ ] session_regenerate_id پس از checkout (جلوگیری از fixation)

## ❌ Anti-patterns

- موجودی در session (قابل دستکاری) — موجودی واقعی در DB
- اعتماد به وضعیت درگاه در URL (callback بدون راستی‌آزمایی سرور)
- کد تخفیف به‌صورت متن ساده قابل حدس
- مرجوعی بدون ثبت تاریخچه (قبض مالی)

## ارجاع

- امنیت پرداخت: [`MCP.md`](../../MCP.md)، [`docs/security/03-xss-csrf.md`](../security/03-xss-csrf.md)
- الگوی درگاه: [`docs/references/03-firecrawl.md`](../references/03-firecrawl.md) (نه مستقیم — برای crawler قیمت‌گذاری)
- دستهٔ مرتبط: ۴ (مالی/پرداخت)، ۱۶ (فروش فایل = SaaS)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/09-ecommerce-case-study.md`](case-studies/09-ecommerce-case-study.md).
