# کاربرد: مالی، پرداخت و بیمه

> نسخه مهارت: **0.0.2** | کد دسته: **۴** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ مالی، کاربردهای انتقال/ذخیره/مدیریت ارزش پولی: پرداخت، درگاه، کیف پول،
بانکداری، بیمه، بورس، حسابداری، رمزارز. این دسته **حساس‌ترین** است — هر
اشتباه = ضرر مالی. وتوی امنیتی (T-001) در این دسته مکرر فعال می‌شود.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت php-zero-dep | scaffolds کلیدی |
|----|--------|--------------------------|------------------|
| 4.1 | پرداخت آنلاین | درگاه + idempotency + verify سرور | http-client, crypto, validator, logger |
| 4.2 | درگاه پرداخت | واسط چنددرگاه + مسیریابی + fallback صریح | http-client, crypto, validator, logger |
| 4.3 | کیف پول الکترونیکی | موجودی atomic + تراکنش double-entry | database, crypto, session, csrf |
| 4.4 | بانکداری اینترنتی | احراز هویت چندعاملی + امضای تراکنش | crypto, session, csrf, rate-limiter, validator |
| 4.5 | بیمه آنلاین | فرم استعلام + قیمت‌گذاری + صدور | router, database, validator, crypto, http-client |
| 4.6 | بورس آنلاین | قیمت زنده + سفارش + تطبیق | http-client, database, rate-limiter, crypto |
| 4.7 | خدمات مالی | پورتفولیو + گزارش + نمودار | router, database, view, cache |
| 4.8 | حسابداری آنلاین | دفتر کل + تراکنش + گزارش مالی | database, validator, view, crypto |
| 4.9 | فاکتور آنلاین | صدور + شماره یکتا + PDF + پیگیری | router, database, view, crypto |
| 4.10 | مالیات آنلاین | محاسبه + اظهارنامه + آرشیو | database, validator, crypto |
| 4.11 | درخواست وام | فرم + استعلام اعتبار + پیگیری | router, database, validator, http-client |
| 4.12 | اعتبارسنجی | استعلام + امتیاز + گزارش | http-client, database, crypto, logger |
| 4.13 | رمزارز | آدرس + تراکنش + تأیید (ext-sodium برای کلید) | crypto, http-client, database |
| 4.14 | صرافی آنلاین | سفارش + تطبیق + تسویه | database, crypto, rate-limiter, http-client |
| 4.15 | مدیریت هزینه | ثبت هزینه + دسته‌بندی + گزارش | router, database, session, view |

## الگوی مشترک

- **double-entry bookkeeping:** هر تراکنش دو ردیف (بده/بستان) با مبلغ
  متضاد؛ مجموع همیشه صفر — قابل راستی‌آزمایی.
- **موجودی atomic:** `UPDATE wallet SET balance=balance+? WHERE id=?` در
  تراکنش DB با `SELECT ... FOR UPDATE` (یا retry در SQLite).
- **امضای تراکنش:** هر تراکنش حساس با Ed25519 (`crypto.php`) امضا می‌شود؛
  کلید در HSM/key custodian (T-026) — نه در کد.
- **idempotency:** کلید idempotency در هر درخواست مالی (جلوگیری از پرداخت دوبل).
- **لاگ:** هر تراکنش با context کامل لاگ می‌شود اما **هرگز** کلید/شماره کارت
  کامل (redact در `logger.php`).
- **MPC اجباری:** برای هر API مالی خارجی، ابتدا `resolve-library-id` سپس
  `query-docs` (`MCP.md`)؛ هرگز حدس امضای API.

## ✅ Checklist

- [ ] double-entry: مجموع تراکنش‌ها صفر
- [ ] موجودی atomic با شرط (جلوگیری از منفی)
- [ ] تراکنش‌های حساس امضاشده (Ed25519)
- [ ] idempotency در هر درخواست پرداخت
- [ ] کلید/شماره کارت در لاگ redact می‌شود
- [ ] احراز هویت چندعاملی برای بانکداری (4.4)
- [ ] وتوی T-001 در هر تصمیم امنیتی ثبت می‌شود

## ❌ Anti-patterns

- اعتماد به مبلغ در callback درگاه بدون verify سرور
- لاگ شامل شماره کارت کامل (PCI violation)
- موجودی در session
- کلید مرچنت در کد هاردکد

## ارجاع

- رمزنگاری: [`docs/elliptic-curve/`](../elliptic-curve/)، [`scaffolds/crypto.php`](../../scaffolds/crypto.php)
- منبع درگاه: [`MCP.md`](../../MCP.md)
- دستهٔ مرتبط: ۳ (فروشگاه/پرداخت)، ۱۴ (CRM مالی)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
