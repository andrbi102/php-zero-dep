# کاربرد: انجمن، پرسش و پاسخ و پشتیبانی

> نسخه مهارت: **0.0.2** | کد دسته: **۵** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **solo→full**

## مرور

دستهٔ انجمن/پشتیبانی، کاربردهایی که تعامل متنی کاربر↔کاربر یا کاربر↔پشتیبانی
را پوشش می‌دهند: فروم، Q&A، تیکت، FAQ، چت زنده. واحد دادهٔ «پست/تیکت» است؛
تفاوت‌ها در مدل مالکیت و جریان پاسخ هستند.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 5.1 | انجمن گفتگو | بخش/موضوع/پست + نقش + گزارش | router, database, session, csrf, rate-limiter |
| 5.2 | فروم تخصصی | 5.1 + تگ + رتبه کاربر + پاسخ پذیرفته | router, database, session, csrf |
| 5.3 | پرسش و پاسخ | 5.2 با پذیرش پاسخ + امتیازدهی | router, database, session, csrf, validator |
| 5.4 | پشتیبانی آنلاین | تیکت + اختصاص agent + SLA | router, database, session, csrf, rate-limiter |
| 5.5 | سامانه تیکت | 5.4 + اولویت + تاریخچه + پیوست | router, database, session, csrf, validator |
| 5.6 | سامانه FAQ | مقالات راهنما + جستجو + رأی مفید | router, database, view, cache |
| 5.7 | مرکز راهنما | 5.6 + دسته‌بندی درختی + جستجوی full-text | router, database, view, cache |
| 5.8 | دانشنامه پشتیبانی | 5.7 + ویرایش مشارکتی + تاریخچه | router, database, session, csrf |
| 5.9 | بازخورد کاربران | فرم + رأی + تحلیل | validator, database, session, csrf |
| 5.10 | گزارش خطا | فرم + شدت + پیگیری + وضعیت | router, database, session, csrf, validator |
| 5.11 | گفتگوی زنده | long-polling + صف پیام + تاریخچه | router, database, session, rate-limiter |
| 5.12 | انجمن کاربران | 5.1 + گروه‌های کاربری + رویداد | router, database, session, csrf, view |

## الگوی مشترک

- **مدل داده:** `topics(id, forum_id, title, author_id, created_at)`،
  `posts(id, topic_id, body, author_id, created_at)`، `tickets(id, user_id, subject, status, priority, agent_id)`.
- **نقش:** کاربر/ناظر/مدیر/agent — قفل action با بررسی نقش.
- **rate limit:** پست‌سازی با `rate-limiter.php` (پنجره لغزان) — جلوی اسپم.
- **چت زنده:** long-polling (نه WebSocket در PHP خالص تک‌پردازشی)؛ پیام در
  صف DB؛ client هر N ثانیه poll.
- **پیوست تیکت:** آپلود امن (T-031): MIME sniff، rename، بیرون webroot.
- **escape:** هر پست با `View::e()` — حتی اگر Markdown (render سپس escape).

## ✅ Checklist

- [ ] نقش در هر action نوشتن بررسی می‌شود
- [ ] rate limit روی پست‌سازی (پنجره لغزان)
- [ ] CSRF در همه فرم‌ها
- [ ] پیوست با MIME sniff + rename + بیرون webroot
- [ ] چت زنده با long-polling (نه client-trusted)
- [ ] گزارش پست/تیکت برای ناظر

## ❌ Anti-patterns

- Markdown بدون escape (XSS در body)
- چت با WebSocket در PHP خالص تک‌نخی (بن‌بست)
- بدون rate limit (اسپم)

## ارجاع

- امنیت فرم: [`docs/frontend/02-forms.md`](../frontend/02-forms.md)
- دستهٔ مرتبط: ۶ (شبکه اجتماعی)، ۱۴ (CRM/تیکت)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/11-forums-case-study.md`](case-studies/11-forums-case-study.md).
