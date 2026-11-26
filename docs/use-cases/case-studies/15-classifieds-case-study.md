# مطالعهٔ موردی — آگهی، املاک، خودرو و بازار کار (دسته 15)

> نسخه مهارت: **0.0.2** | دسته: [`15-classifieds.md`](../15-classifieds.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**جدول املاک (نیازمندی‌های محلی)** — ثبت آگهی املاک با جستجو و فیلتر پیشرفته.
- **کاربران:** مشتریان، مشاور املاک، مدیر
- **محدودیت کلیدی:** جستجوی سریع روی هزاران آگهی + ضد اسپم
- **پروفایل پیشنهادی:** team | **معماری:** MVC + Graph برای جستجو

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
جدول املاک (نیازمندی‌های محلی) به ما مراجعه کرد: ثبت آگهی املاک با جستجو و فیلتر پیشرفته. کاربران هدف مشتریان، مشاور املاک، مدیر هستند
و محدودیت کلیدی «جستجوی سریع روی هزاران آگهی + ضد اسپم» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-053 Content Writer**.

### فاز ۲ — معماری (Architecture)
با توجه به جستجوی سریع روی هزاران آگهی + ضد اسپم، معماری **MVC + Graph برای جستجو** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `database`, `graph`, `validator`, `rate-limiter`. دلیل انتخاب: پوشش مستقیم
نیازهای «جستجوی گرافی محله + فیلتر چندبُعدی» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **listings, agents, districts, filters**. ویژگی امضای این پروژه: جستجوی گرافی محله + فیلتر چندبُعدی. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
کارت آگهی با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای ثبت آگهی و قوانین») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-053 Content Writer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
دسته‌بندی خودکار آگهی از چند منظر. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF روی ثبت + RateLimiter روی آگهی‌های تکراری + View::e(). خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ آگهی، املاک، خودرو و بازار کار) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای ثبت آگهی و قوانین») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «بدون کمیسیون»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست اشتراکی + SQLite**. migration با روش «از مرورگر». نگهداری: بایگانی آگهی منقضی + بازنمایه‌سازی گراف.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `database`, `graph`, `validator`, `rate-limiter`
- Worker راهبر: **T-053 Content Writer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`15-classifieds.md`](../15-classifieds.md)
