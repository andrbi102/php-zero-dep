# کاربرد: شبکه اجتماعی و ارتباطات

> نسخه مهارت: **0.0.2** | کد دسته: **۶** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ شبکه اجتماعی، کاربردهای ارتباط کاربر↔کاربر در مقیاس: شبکه عمومی/تخصصی،
چت، پیام‌رسان وب، تماس صوتی/تصویری، همسریابی. واحد دادهٔ «اتصال/پیام» است؛
چالش اصلی مقیاس و حریم خصوصی است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 6.1 | شبکه اجتماعی عمومی | پروفایل + پست + دنبال‌کردن + فید | router, database, session, csrf, rate-limiter, cache |
| 6.2 | شبکه اجتماعی تخصصی | 6.1 + فیلد دامنه + گروه موضوعی | router, database, session, csrf, validator |
| 6.3 | شبکه اجتماعی حرفه‌ای | 6.2 + رزومه + اتصال کاری + تأیید | router, database, session, csrf, crypto |
| 6.4 | چت آنلاین | long-polling + اتاق + تاریخچه | router, database, session, rate-limiter |
| 6.5 | پیام‌رسان وب | 6.4 + پیام خصوصی + وضعیت آنلاین | router, database, session, crypto, rate-limiter |
| 6.6 | تماس صوتی | سیگنالینگ WebRTC + recording metadata (صوت روی سرویس خارجی) | router, database, crypto |
| 6.7 | تماس تصویری | 6.6 + video (WebRTC) | router, database, crypto |
| 6.8 | ایمیل وب | کلاینت IMAP/SMTP (ext-imap لازم — بررسی مجاز) | http-client, crypto, session |
| 6.9 | همسریابی | پروفایل + الگوریتم تطبیق + درخواست + تأیید | router, database, session, csrf, crypto |
| 6.10 | دوستیابی | 6.9 ساده + geolocation (اختیاری) | router, database, session, csrf |
| 6.11 | شبکه‌سازی حرفه‌ای | 6.3 + رویداد + معرفی | router, database, session, csrf, view |
| 6.12 | گروه آنلاین | عضویت + نقش + بورد | router, database, session, csrf |
| 6.13 | کانال آنلاین | نشر یک‌طرفه + مشترک + notify | router, database, session, rate-limiter |
| 6.14 | انجمن محلی | 6.12 geo-bound + رویداد محلی | router, database, session, csrf |
| 6.15 | گفتگوی موضوعی | 5.1 با موضوع الزامی + thread | router, database, session, csrf |

## الگوی مشترک

- **فید:** ترکیب پست‌های دنبال‌شده با کش (`cache.php`)؛ بازسازی در بازه؛
  نه کوئری زنده در هر بار (N+1).
- **پیام خصوصی:** رمزنگاری end-to-end با libsodium (`crypto.php`) اگر حساس؛
  کلید در کلاینت، سرور فقط relay.
- **حریم خصوصی:** همه تنظیمات visibility در یک جدول `privacy(user_id, field, level)`؛
  بررسی در هر کوئری خواندن.
- **rate limit:** پست/پیام با پنجره لغزان؛ notify با token bucket.
- **آنلاین/آفلاین:** heartbeat با long-polling؛ آخرین تپش در cache با TTL.

## ✅ Checklist

- [ ] فید با کش (نه کوئری زنده N+1)
- [ ] پیام حساس E2E (libsodium)
- [ ] حریم خصوصی بررسی در هر کوئری خواندن
- [ ] rate limit روی پست/پیام/notify
- [ ] CSRF + session_regenerate_id
- [ ] آپلود تصویر پروفایل امن (T-031)

## ❌ Anti-patterns

- پیام در plaintext در DB (حریم خصوصی)
- فید با کوئری زنده (کندی در مقیاس)
- بدون حریم خصوصی (همه همه‌چیز را می‌بینند)

## ارجاع

- رمزنگاری E2E: [`docs/elliptic-curve/02-sodium.md`](../elliptic-curve/02-sodium.md)
- دستهٔ مرتبط: ۵ (انجمن)، ۱۲ (گروه/ابزار)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
