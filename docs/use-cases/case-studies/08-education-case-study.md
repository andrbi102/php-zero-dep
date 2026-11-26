# مطالعهٔ موردی — آموزش و دانش (دسته 08)

> نسخه مهارت: **0.0.2** | دسته: [`08-education.md`](../08-education.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**آکادمی کدنویس (موسسه آموزش برنامه‌نویسی)** — پلتفرم دوره با آزمون، گواهی و پیشرفت دانشجو.
- **کاربران:** دانشجویان، مدرس‌ها، مدیر آموزش
- **محدودیت کلیدی:** بازدید هم‌زمان بالا در زمان آزمون
- **پروفایل پیشنهادی:** team | **معماری:** HMVC (ماژول دوره/آزمون/گواهی)

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
آکادمی کدنویس (موسسه آموزش برنامه‌نویسی) به ما مراجعه کرد: پلتفرم دوره با آزمون، گواهی و پیشرفت دانشجو. کاربران هدف دانشجویان، مدرس‌ها، مدیر آموزش هستند
و محدودیت کلیدی «بازدید هم‌زمان بالا در زمان آزمون» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-018 API Designer**.

### فاز ۲ — معماری (Architecture)
با توجه به بازدید هم‌زمان بالا در زمان آزمون، معماری **HMVC (ماژول دوره/آزمون/گواهی)** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `hmvc-dispatcher`, `session`, `csrf`, `rate-limiter`, `event-store`. دلیل انتخاب: پوشش مستقیم
نیازهای «آزمون زمان‌دار با جلوگیری از تقلب (RateLimiter + session lock)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **courses, lessons, quizzes, enrollments, certificates**. ویژگی امضای این پروژه: آزمون زمان‌دار با جلوگیری از تقلب (RateLimiter + session lock). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
داشبورد پیشرفت با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («معرفی دوره و بازخورد خودکار») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-018 API Designer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
طراحی ساختار سؤالات آزمون از چند منظر (سختی/پوشش). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: session_regenerate پس از ورود + CSRF روی ثبت‌نام + RateLimiter روی ارسال آزمون. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ آموزش و دانش) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («معرفی دوره و بازخورد خودکار») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «۹۰٪ گذر»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **VPS با PHP-FPM + nginx**. migration با روش «خط فرمان با db-migrate». نگهداری: نگهداری رویدادها در event-store برای تحلیل افت.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `hmvc-dispatcher`, `session`, `csrf`, `rate-limiter`, `event-store`
- Worker راهبر: **T-018 API Designer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`08-education.md`](../08-education.md)
