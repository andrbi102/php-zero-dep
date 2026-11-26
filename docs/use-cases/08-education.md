# کاربرد: آموزش و دانش

> نسخه مهارت: **0.0.2** | کد دسته: **۲** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **solo→full** (بسته به مقیاس)

## مرور

دستهٔ آموزش، کاربردهایی که دانش را منتقل یا ارزیابی می‌کنند: LMS، دوره،
آزمون، وبینار، کتابخانه دیجیتال، ویکی، فلش‌کارت. واحد دادهٔ اصلی
«درس/دوره/آزمون» است؛ تفاوت‌ها در مدل پیشرفت کاربر و ارزیابی هستند.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت php-zero-dep | scaffolds کلیدی |
|----|--------|--------------------------|------------------|
| 2.1 | سایت آموزشی | دوره‌ها + درس‌ها + پیشرفت کاربر | router, database, session, view |
| 2.2 | LMS | 2.1 + ثبت‌نام/پرداخت دوره + گواهی + نقش معلم/دانشجو | router, database, session, csrf, crypto |
| 2.3 | آموزشگاه آنلاین | 2.2 + تقویم کلاس + مدیریت اساتید | router, database, session, validator |
| 2.4 | دوره آنلاین | 2.1 تک‌دورسه + دسترسی پس از پرداخت | router, database, session, http-client |
| 2.5 | وبینار | ثبت‌نام + لینک دسترسی زمان‌دار + remind ایمیل | router, database, session, crypto |
| 2.6 | کلاس مجازی | 2.5 + چت متنی (WebSocket جایگزین: long-polling) | router, database, session, rate-limiter |
| 2.7 | آزمون آنلاین | بانک سؤال + زمان‌سنج + نمره‌دهی خودکار | router, database, session, validator, crypto |
| 2.8 | آزمون استخدامی | 2.7 + احراز هویت کاندیدا + ضدتقلب (timer، یک سؤال در لحظه) | router, database, session, crypto, rate-limiter |
| 2.9 | سایت دانشگاه | پورتال دانشجو/استاد + ثبت‌نام درس + نمره | router, database, session, csrf, validator |
| 2.10 | سایت مدرسه | 2.9 ساده‌شده + حضورغیاب | router, database, session, view |
| 2.11 | کتابخانه دیجیتال | فهرست قابل‌جستجو + دانلود محافظت‌شده (توکن یک‌بارمصرف) | router, database, crypto, rate-limiter |
| 2.12 | فروش کتاب الکترونیکی | 3.13 (فروش فایل) + DRM سبک (watermark با خریدار) | router, database, crypto, http-client |
| 2.13 | دانشنامه | مقالات مرجع + ویرایش مشارکتی + تاریخچه | router, database, session, csrf, view |
| 2.14 | ویکی | 2.13 + markup ساده + diff نسخه | router, database, session, csrf |
| 2.15 | جزوه آنلاین | محتوای درس + دانلود PDF (اگر ext-dom موجود) | router, view, database |
| 2.16 | فلش‌کارت | کارت‌ها + الگوریتم تکرار فاصله‌دار (SRS) | router, database, session |
| 2.17 | تمرین آنلاین | سؤال + بررسی پاسخ + آمار | router, database, session, validator |
| 2.18 | آموزش زبان | 2.16 + صوت تلفظ (embed) | router, database, view |
| 2.19 | آموزش مهارت | 2.1 با مسیر مهارت (درخت پیش‌نیاز) | router, database, session, view |
| 2.20 | مشاوره تحصیلی | نوبت‌دهی (8.3) + پرونده دانشجو | router, database, session, csrf |

## الگوی مشترک

- **پیشرفت کاربر:** `progress(user_id, lesson_id, status, completed_at)`؛
  درخت پیش‌نیاز با `Graph::hasCycle()` اعتبارسنجی (درس A پیش‌نیاز درس B).
- **آزمون:** سؤال‌ها به‌صورت shuffle؛ timer سمت سرور (نه client-trusted)؛
  یک سؤال در لحظه برای ضدتقلب.
- **گواهی:** PDF تولید با ext-dom (اگر موجود) یا HTML قابل‌چاپ؛ امضای
  دیجیتال گواهی با `crypto.php` (Ed25519) برای جلوگیری از جعل.
- **دسترسی:** دسترسی به درس پس از پرداخت با بررسی `purchase` رکورد.

## ✅ Checklist

- [ ] پیشرفت کاربر پس از تکمیل درس ثبت و قابل‌بازگشت است
- [ ] timer آزمون سمت سرور (server-side) — نه JavaScript client
- [ ] گواهی امضاشده (Ed25519) — قابل راستی‌آزمایی
- [ ] دانلود فایل محافظت‌شده با توکن یک‌بارمصرف + path traversal بسته
- [ ] دسترسی بر اساس نقش (دانشجو/استاد/مدیر)

## ❌ Anti-patterns

- اعتماد به timer سمت کلاینت (قابل دستکاری)
- ذخیرهٔ پاسخ آزمون در localStorage بدون ثبت سرور
- بدون نقش — همه همه‌چیز را ویرایش می‌کنند

## ارجاع

- الگوی مسیر/پیش‌نیاز: [`docs/data-structures/01-graph.md`](../data-structures/01-graph.md)
- دستهٔ مرتبط: ۱ (نشر)، ۳ (فروش کتاب = 3.13)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/08-education-case-study.md`](case-studies/08-education-case-study.md).
