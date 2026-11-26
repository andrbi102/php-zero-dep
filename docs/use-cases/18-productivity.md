# کاربرد: بهره‌وری، همکاری و ابزار آنلاین

> نسخه مهارت: **0.0.2** | کد دسته: **۱۲** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ بهره‌وری/ابزار، کاربردهای ابزار تحت وب برای کار تیمی/فردی: مدیریت
پروژه، وظایف، اشتراک فایل، ویرایش سند، طراحی، ترجمه، نوت، تخته سفید، امضای
دیجیتال. واحد دادهٔ «پروژه/سند/وظیفه» است؛ چالش اصلی هم‌زمانی و نسخه‌بندی است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 12.1 | مدیریت پروژه | پروژه + وظیفه + milestone + Gantt | router, database, session, csrf, view |
| 12.2 | مدیریت وظایف | برد Kanban + نقل‌وضعیت + تخصیص | router, database, session, csrf |
| 12.3 | همکاری تیمی | تیم + نقش + نظر +提及 | router, database, session, csrf, rate-limiter |
| 12.4 | اشتراک فایل | آپلود امن + لینک امضاشده + انقضا | router, database, crypto, rate-limiter |
| 12.5 | ذخیره‌سازی ابری | فایل + نسخه + سهمیه + رمزنگاری | database, crypto, session, validator |
| 12.6 | ویرایش آنلاین سند | متن + نسخه + قفل هم‌زمانی | router, database, session, csrf, cache |
| 12.7 | ویرایش آنلاین تصویر | ابزار ساده با ext-gd + ذخیره نسخه | router, database, session, crypto |
| 12.8 | طراحی گرافیک آنلاین | بوم + لایه + خروجی (سبک) | router, database, session, view |
| 12.9 | تولید محتوا | الگو + متغیر + خروجی | router, database, session, view |
| 12.10 | ترجمه آنلاین | متن + API خارجی (MCP) + cache | http-client, cache, validator |
| 12.11 | تبدیل فایل | آپلود + تبدیل + دانلود (محدود ext) | router, database, session, rate-limiter |
| 12.12 | ابزار آنلاین | مجموعه ابزارهای کوچک | router, view, session |
| 12.13 | ماشین‌حساب آنلاین | ورودی + محاسبه (دقت با bcmath) | router, view, validator |
| 12.14 | نوت‌برداری | یادداشت + دسته + جستجو + sync | router, database, session, csrf |
| 12.15 | مدیریت زمان | تایمر + گزارش + هدف | router, database, session, view |
| 12.16 | تخته سفید آنلاین | بوم + ذخیره خودکار + هم‌زمانی | router, database, session, csrf, cache |
| 12.17 | امضای دیجیتال | کلید + امضا (Ed25519) + راستی‌آزمایی | crypto, database, session, logger |

## الگوی مشترک

- **قفل هم‌زمانی (هم‌زمانی خوش‌بینانه):** آخرین‌نویس-برنده با نسخه‌بندی؛
  هر ذخیره نسخه قبلی را نگه می‌دارد؛ کاربر در صورت تعارض merge می‌کند.
  قفل بدبینانه (SELECT FOR UPDATE) برای سند حساس.
- **نسخه‌بندی:** `versions(doc_id, n, body, author, created_at)`؛ بازگشت
  به نسخه همیشه ممکن.
- **اشتراک فایل:** URL امضاشده HMAC با انقضا (anti-hotlink)؛ حذف خودکار پس
  از انقضا.
- **امضای دیجیتال (12.17):** Ed25519 با libsodium؛ کلید خصوصی در key
  custodian؛ امضا قابل راستی‌آزمایی مستقل از سرویس.
- **تبدیل فایل:** محدود به ext موجود (ext-gd برای تصویر، ext-dom برای XML)؛
  تبدیل پیچیده (Office) به سرویس خارجی.

## ✅ Checklist

- [ ] نسخه‌بندی + بازگشت به نسخه
- [ ] اشتراک فایل با URL امضاشده + انقضا
- [ ] امضای دیجیتال Ed25519 با کلید در custodian
- [ ] قفل هم‌زمانی برای سند حساس
- [ ] حذف خودکار فایل منقضی
- [ ] CSRF + نقش در همه action

## ❌ Anti-patterns

- سند بدون نسخه (بازگشت ممکن نیست)
- URL اشتراک بدون امضا/انقضا (hotlink/نشست)
- کلید امضا در کد
- تبدیل پیچیده در PHP (کند، نامناسب)

## ارجاع

- امضای دیجیتال: [`docs/elliptic-curve/03-ecdsa.md`](../elliptic-curve/03-ecdsa.md)، [`scaffolds/crypto.php`](../../scaffolds/crypto.php)
- دستهٔ مرتبط: ۱۴ (CRM/امضای قرارداد)، ۱۶ (SaaS)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/18-productivity-case-study.md`](case-studies/18-productivity-case-study.md).
