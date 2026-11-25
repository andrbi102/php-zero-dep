# کاربرد: زیرساخت، پلتفرم و دانلود

> نسخه مهارت: **0.0.2** | کد دسته: **۱۶** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ زیرساخت/پلتفرم، کاربردهای ارائهٔ زیرساخت به‌عنوان سرویس: دانلود، SaaS،
API، پلتفرم ابری، میزبانی، CDN، پشتیبان‌گیری، مانیتورینگ، ثبت دامنه، SSL.
این دسته خودش میزبان است؛ امنیت و دسترس‌پذیری بالاترین اولویت هستند.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 16.1 | دانلود نرم‌افزار | کاتالوگ + نسخه + URL امضاشده + آمار | router, database, view, crypto, rate-limiter |
| 16.2 | دانلود فایل | 12.4 (اشتراک فایل) + انقضا | router, database, crypto, rate-limiter |
| 16.3 | نرم‌افزار تحت وب | چندابزار + احراز هویت + نقش | router, database, session, csrf |
| 16.4 | سرویس SaaS | چندکاربری + اشتراک + ایزوله داده | router, database, session, crypto, csrf |
| 16.5 | API آنلاین | REST + کلید + rate limit + مستندات | router, database, crypto, rate-limiter, http-client |
| 16.6 | پلتفرم ابری | منبع + سهمیه + صورت‌حساب | router, database, session, crypto, logger |
| 16.7 | میزبانی وب | حساب + فایل + آمار + پشتیبان | router, database, session, crypto |
| 16.8 | دامنه و هاست | جستجو + ثبت (API رجیسترار) + مدیریت | http-client, database, session, crypto |
| 16.9 | مدیریت محتوا | 16.10 | router, database, session, csrf, view |
| 16.10 | سیستم مدیریت محتوا | محتوای پویا + قالب + نقش + افزونه | router, database, session, csrf, view, cache |
| 16.11 | CDN | توزیع + cache + URL امضاشده | router, cache, crypto, rate-limiter |
| 16.12 | پشتیبان‌گیری آنلاین | زمان‌بندی + ذخیره + رمزنگاری + بازیابی | database, crypto, logger, session |
| 16.13 | مانیتورینگ سرویس | جمع‌آوری + آستانه + هشدار | http-client, database, rate-limiter, logger |
| 16.14 | ثبت دامنه | 16.8 + فرآیند رسمی + WHOIS | http-client, database, session, crypto |
| 16.15 | SSL | صدور (API) + نصب + تمدید | http-client, database, crypto, session |

## الگوی مشترک

- **چندکاربری ایزوله (SaaS):** هر tenant شناسه؛ همه کوئری با `WHERE tenant_id=?`؛
  کلید tenant در session؛ leak بین tenant = نقض امنیتی بحرانی.
- **API (16.5):** کلید API از env نه هاردکد؛ rate limit per-key؛ مستندات
  از `MCP.md` تولید نه حدس؛ ورودی اعتبارسنجی.
- **URL دانلود امضاشده:** HMAC با انقضا (anti-hotlink/leech)؛ آپلود امن (T-031).
- **پشتیبان‌گیری:** رمزنگاری در حال استراحت (libsodium)؛ نسخه‌بندی؛ بازیابی
  آزمایش‌شده (DR drill).
- **مانیتورینگ:** جمع‌آوری با interval؛ هشدار با آستانه؛ نه polling مداوم
  سنگین.

## ✅ Checklist

- [ ] ایزوله‌سازی tenant در همه کوئری
- [ ] کلید API از env + rate limit per-key
- [ ] URL دانلود امضاشده HMAC + انقضا
- [ ] پشتیبان رمزنگاری‌شده + بازیابی آزمایش‌شده
- [ ] مانیتورینگ با آستانه + هشدار
- [ ] audit log از عملیات حساس (ثبت دامنه، SSL)

## ❌ Anti-patterns

- leak tenant (co-tenancy بدون WHERE tenant_id)
- کلید API هاردکد
- URL دانلود عمومی (hotlink)
- پشتیبان بدون بازیابی آزمایش‌شده

## ارجاع

- API: [`docs/architecture/06-rest-api.md`](../architecture/06-rest-api.md)، [`MCP.md`](../../MCP.md)
- دستهٔ مرتبط: ۱۲ (ابزار تحت وب)، ۳ (SaaS فروش اشتراک)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
