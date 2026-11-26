# مطالعهٔ موردی — مسکن و خدمات منزل (دسته 25)

> نسخه مهارت: **0.0.2** | دسته: [`25-housing-services.md`](../25-housing-services.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**خدمات منزل پایدار** — درخواست خدمات منزل (نظافت، تعمیر) با رزرو و امتیاز.
- **کاربران:** مشتریان، ارائه‌دهنده خدمات
- **محدودیت کلیدی:** رزرو + امتیازدهی + پرداخت
- **پروفایل پیشنهادی:** team | **معماری:** MVC + Command Bus

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
خدمات منزل پایدار به ما مراجعه کرد: درخواست خدمات منزل (نظافت، تعمیر) با رزرو و امتیاز. کاربران هدف مشتریان، ارائه‌دهنده خدمات هستند
و محدودیت کلیدی «رزرو + امتیازدهی + پرداخت» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به رزرو + امتیازدهی + پرداخت، معماری **MVC + Command Bus** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `database`, `command-bus`, `rate-limiter`, `validator`. دلیل انتخاب: پوشش مستقیم
نیازهای «تطبیق ارائه‌دهنده + امتیازدهی با تأیید» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **services, providers, bookings, ratings**. ویژگی امضای این پروژه: تطبیق ارائه‌دهنده + امتیازدهی با تأیید. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
کارت خدمت با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («شرایط خدمت و سیاست لغو») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
تطبیق ارائه‌دهنده از چند سیگنال (امتیاز/فاصله/قیمت). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF + RateLimiter روی رزرو + hashing پرداخت + View::e(). خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ مسکن و خدمات منزل) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («شرایط خدمت و سیاست لغو») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «ارائه‌دهندهٔ تأییدشده»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با SSL**. migration با روش «از مرورگر». نگهداری: بازآوری امتیاز + گزارش روزانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `database`, `command-bus`, `rate-limiter`, `validator`
- Worker راهبر: **T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`25-housing-services.md`](../25-housing-services.md)
