# مطالعهٔ موردی — تقویم، رزرو، سفر و حمل‌ونقل (دسته 14)

> نسخه مهارت: **0.0.2** | دسته: [`14-booking.md`](../14-booking.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**تورینو (آژانس مسافرتی کوچک)** — رزرو تور و بلیط با تقویم و موجودی صندلی.
- **کاربران:** مسافران، کارشناس فروش
- **محدودیت کلیدی:** رزرو هم‌زمان + انقضا رزرو موقت
- **پروفایل پیشنهادی:** team | **معماری:** MVC + Command Bus

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
تورینو (آژانس مسافرتی کوچک) به ما مراجعه کرد: رزرو تور و بلیط با تقویم و موجودی صندلی. کاربران هدف مسافران، کارشناس فروش هستند
و محدودیت کلیدی «رزرو هم‌زمان + انقضا رزرو موقت» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به رزرو هم‌زمان + انقضا رزرو موقت، معماری **MVC + Command Bus** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `command-bus`, `event-store`, `cache`, `validator`, `rate-limiter`. دلیل انتخاب: پوشش مستقیم
نیازهای «قفل صندلی با انقضا (cache TTL + command)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **tours, seats, reservations, expirations**. ویژگی امضای این پروژه: قفل صندلی با انقضا (cache TTL + command). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
تقویم با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («شرایط تور و سیاست لغو») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
قیمت‌گذاری پویا از چند سیگنال (فصل/پرشدگی/تقاضا). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: crypto برای پرداخت + CSRF + RateLimiter روی رزرو + idempotency. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ تقویم، رزرو، سفر و حمل‌ونقل) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («شرایط تور و سیاست لغو») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «تضمین最低 قیمت»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با SSL + FileCache**. migration با روش «خط فرمان». نگهداری: آزادسازی رزرو منقضی + گزارش روزانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `command-bus`, `event-store`, `cache`, `validator`, `rate-limiter`
- Worker راهبر: **T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`14-booking.md`](../14-booking.md)
