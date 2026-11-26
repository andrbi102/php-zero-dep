# مطالعهٔ موردی — مدیریت کسب‌وکار و بازاریابی (دسته 20)

> نسخه مهارت: **0.0.2** | دسته: [`20-business.md`](../20-business.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**گروه تجارت آریا (شرکت متوسط)** — CRM سبک با سرنخ، فرصت و گزارش فروش.
- **کاربران:** فروش، مدیر فروش
- **محدودیت کلیدی:** یکپارچه‌سازی با ایمیل بدون SaaS
- **پروفایل پیشنهادی:** team | **معماری:** MVC + CQRS

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
گروه تجارت آریا (شرکت متوسط) به ما مراجعه کرد: crm سبک با سرنخ، فرصت و گزارش فروش. کاربران هدف فروش، مدیر فروش هستند
و محدودیت کلیدی «یکپارچه‌سازی با ایمیل بدون SaaS» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-053 Content Writer**.

### فاز ۲ — معماری (Architecture)
با توجه به یکپارچه‌سازی با ایمیل بدون SaaS، معماری **MVC + CQRS** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `database`, `command-bus`, `query-bus`, `cache`. دلیل انتخاب: پوشش مستقیم
نیازهای «قیف فروش با گزارش زنده (query-bus)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **leads, opportunities, contacts, activities**. ویژگی امضای این پروژه: قیف فروش با گزارش زنده (query-bus). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
داشبورد فروش با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («قالب ایمیل فروش و یادآوری») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-053 Content Writer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
امتیازدهی سرنخ از چند سیگنال. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF + RateLimiter روی ایمیل + hashing + View::e(). خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ مدیریت کسب‌وکار و بازاریابی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («قالب ایمیل فروش و یادآوری») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «افزایش ۲ برابری فروش»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با SSL + FileCache**. migration با روش «از مرورگر». نگهداری: بایگانی سرنخ سرد + بازبینی گزارش.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `database`, `command-bus`, `query-bus`, `cache`
- Worker راهبر: **T-053 Content Writer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`20-business.md`](../20-business.md)
