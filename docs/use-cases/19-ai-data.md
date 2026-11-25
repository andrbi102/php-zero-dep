# کاربرد: هوش مصنوعی و تحلیل داده

> نسخه مهارت: **0.0.2** | کد دسته: **۱۳** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ AI/داده، کاربردهایی که مدل هوش مصنوعی را به‌عنوان سرویس مصرف/میان‌فریم
می‌کنند: چت‌بات، تولید متن/تصویر/ویدئو، تحلیل داده، داشبورد، گزارش‌ساز،
تشخیص گفتار، ترجمه، خلاصه‌سازی. **نکته:** PHP خودش مدل را اجرا نمی‌کند —
به API خارجی وصل می‌شود (با MCP برای مستندات).

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 13.1 | هوش مصنوعی آنلاین | رابط + API + تاریخچه | http-client, database, session, crypto, logger |
| 13.2 | چت‌بات | مکالمه + context + ذخیره | http-client, database, session, rate-limiter |
| 13.3 | تولید متن | فرم + prompt + خروجی + ذخیره | http-client, database, session, validator |
| 13.4 | تولید تصویر | prompt + API + ذخیره (آپلود امن) | http-client, database, crypto |
| 13.5 | تولید ویدئو | 13.4 با ویدئو (metadata + embed) | http-client, database, view |
| 13.6 | تحلیل داده | آپلود CSV + پردازش + نمودار | router, database, validator, view |
| 13.7 | داشبورد آنلاین | ویجت + منبع داده + refresh | router, database, view, cache |
| 13.8 | گزارش‌ساز | قالب + منبع + خروجی (PDF/CSV) | router, database, view, crypto |
| 13.9 | بینایی ماشین | آپلود تصویر + API خارجی + نتیجه | http-client, database, crypto, validator |
| 13.10 | تشخیص گفتار | آپلود صوت + API + متن | http-client, database, crypto |
| 13.11 | ترجمه هوشمند | 12.10 با مدل ترجمه | http-client, cache, validator |
| 13.12 | خلاصه‌سازی محتوا | متن/URL + API + خلاصه | http-client, web-fetch, database |
| 13.13 | دستیار هوشمند | چندابزار + orchestration + تاریخچه | http-client, database, session, crypto |

## الگوی مشترک

- **API خارجی (الزامی MCP):** برای هر مدل AI، ابتدا `resolve-library-id` سپس
  `query-docs` (`MCP.md`)؛ هرگز حدس امضای API. ارتباط از `http-client.php`
  با timeout + retry + traceparent.
- **کلید API:** از env (`getenv`)؛ هرگز در کد/لاگ (`logger.php` redact).
- **هزینه/سهمیه:** ردیابی token/cost در هر درخواست؛ سهمیه به‌ازای کاربر با
  rate limit.
- **context window:** مکالمه با خلاصه‌سازی خودکار وقتی context پر شد؛ نگه‌داشتن
  خلاصه در DB نه تاریخچهٔ کامل.
- **خروجی:** هرگز blind trust — اعتبارسنجی ساختار پاسخ با `validator.php`.
- **web-fetch (13.12):** برای خلاصه‌سازی URL، از `scaffolds/web-fetch.php`
  (پاکسازی HTML → markdown) سپس ارسال به API.

## ✅ Checklist

- [ ] API از MCP مستند شده (نه حدس)
- [ ] کلید از env، در لاگ redact
- [ ] سهمیه/token به‌ازای کاربر
- [ ] خلاصه‌سازی خودکار context
- [ ] اعتبارسنجی ساختار پاسخ API
- [ ] rate limit + timeout + retry

## ❌ Anti-patterns

- کلید API در کد هاردکد
- blind trust پاسخ API (اعتبارسنجی نشده)
- تاریخچهٔ کامل مکالمه در context (هزینه/کندی)
- بدون سهمیه (هزینهٔ ناگهانی)

## ارجاع

- MCP: [`MCP.md`](../../MCP.md)
- HTTP client: [`scaffolds/http-client.php`](../../scaffolds/http-client.php)
- web-fetch (برای خلاصه‌سازی URL): [`scaffolds/web-fetch.php`](../../scaffolds/web-fetch.php)
- دستهٔ مرتبط: ۱۲ (ابزار)، ۱۴ (اتوماسیون)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
