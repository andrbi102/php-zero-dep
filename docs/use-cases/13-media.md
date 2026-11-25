# کاربرد: رسانه صوتی و تصویری

> نسخه مهارت: **0.0.2** | کد دسته: **۷** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ رسانه، کاربردهای اشتراک/پخش صوت/تصویر: اشتراک تصویر/ویدئو/موسیقی،
پادکست، رادیو، پخش زنده، استریم بازی، گالری، ویدئو کنفرانس. **نکتهٔ
مهم:** PHP خالص برای انکود/ترانسکد ویدئو مناسب نیست — رسانه سنگین روی
سرویس خارجی (CDN/object storage) و PHP فقط metadata + embed را مدیریت می‌کند.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 7.1 | اشتراک تصویر | آپلود امن + metadata + گالری + watermark | router, database, validator, crypto |
| 7.2 | اشتراک ویدئو | metadata + embed (هاست ویدئو جدا) + کیفیت | router, database, view, cache |
| 7.3 | اشتراک موسیقی | metadata + پخش (range request) + پلی‌لیست | router, database, view, rate-limiter |
| 7.4 | پادکست | RSS podcast + episode + metadata | router, database, view, cache |
| 7.5 | رادیو اینترنتی | استریم زنده (relay به سرویس) + برنامه | router, database, view |
| 7.6 | پخش زنده | embed + chat کنار + شمارش بیننده | router, database, session, rate-limiter |
| 7.7 | استریم بازی | 7.6 + متادیتا بازی + highlights | router, database, session |
| 7.8 | پادکست ویدئویی | 7.2 + 7.4 | router, database, view, cache |
| 7.9 | وبلاگ ویدئویی | 1.6 با خود میزبان embed | router, database, view |
| 7.10 | گالری آنلاین | 17.3 (گالری هنری) | router, database, view, cache |
| 7.11 | آلبوم آنلاین | مجموعه تصویر + اشتراک + ترتیب | router, database, session, crypto |
| 7.12 | ویدئو کنفرانس | سیگنالینگ WebRTC + اتاق + recording metadata | router, database, crypto, session |
| 7.13 | ضبط صفحه | metadata + ذخیره لینک (ضبط در کلاینت) | router, database, session |
| 7.14 | تدوین آنلاین ویدئو | رابط + صف کار (تدوین واقعی در سرویس خارجی) | router, database, session, rate-limiter |

## الگوی مشترک

- **هاست رسانه جدا:** فایل‌های بزرگ روی CDN/object storage؛ PHP فقط
  metadata + URL امضاشده (HMAC با انقضا) را می‌دهد.
- **آپلود تصویر امن (T-031):** MIME sniff (نه پسوند)، rename، resize با `ext-gd`
  (اگر موجود)، ذخیره بیرون webroot، watermark با `ext-gd`.
- **پخش محدوده‌ای (range request):** برای صوت/ویدئو، `Range` header را
  پشتیبانی کن (seek) — با `fseek` و `fpassthru`.
- **RSS podcast:** `ext-simplexml`؛ episode با `<enclosure url=…>`؛ کش هفتگی.
- **ویدئو کنفرانس:** WebRTC برای رسانه (PHP فقط سیگنالینگ با long-polling)؛
  recording metadata ثبت، فایل روی سرویس.

## ✅ Checklist

- [ ] رسانه سنگین روی CDN/object storage (نه هاست PHP)
- [ ] آپلود تصویر: MIME sniff + rename + resize + بیرون webroot
- [ ] URL رسانه امضاشده HMAC با انقضا (anti-hotlink)
- [ ] پخش محدوده‌ای (Range) برای صوت/ویدئو
- [ ] watermark خریدار برای فروش دیجیتال (3.12)
- [ ] rate limit روی آپلود/پخش

## ❌ Anti-patterns

- آپلود ویدئو مستقیم روی هاست اشتراکی (پهنای باند/حجم)
- انکود/ترانسکد در PHP (کند، نه مناسب)
- URL رسانه عمومی بدون امضا (hotlink)
- MIME از پسوند (قابل جعل)

## ارجاع

- آپلود امن: [`docs/security/06-file-upload.md`](../security/06-file-upload.md)
- دستهٔ مرتبط: ۱۷ (گالری)، ۳ (فروش دیجیتال)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
