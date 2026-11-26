# مطالعهٔ موردی — انجمن، پرسش و پاسخ و پشتیبانی (دسته 11)

> نسخه مهارت: **0.0.2** | دسته: [`11-forums.md`](../11-forums.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**انجمن داده‌دانان (جامعهٔ تخصصی)** — انجمن پرسش‌وپاسخ با رأی‌گیری، تگ و اعتبار.
- **کاربران:** اعضا، ناظران، مدیر
- **محدودیت کلیدی:** متن بلند فارسی + مهار اسپم
- **پروفایل پیشنهادی:** team | **معماری:** MVC + Pipeline middleware

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
انجمن داده‌دانان (جامعهٔ تخصصی) به ما مراجعه کرد: انجمن پرسش‌وپاسخ با رأی‌گیری، تگ و اعتبار. کاربران هدف اعضا، ناظران، مدیر هستند
و محدودیت کلیدی «متن بلند فارسی + مهار اسپم» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به متن بلند فارسی + مهار اسپم، معماری **MVC + Pipeline middleware** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `view`, `validator`, `rate-limiter`, `pipeline`. دلیل انتخاب: پوشش مستقیم
نیازهای «اعتبارسنجی پست + مهار اسپم با RateLimiter و فیلتر محتوا» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **threads, posts, votes, tags, reputation**. ویژگی امضای این پروژه: اعتبارسنجی پست + مهار اسپم با RateLimiter و فیلتر محتوا. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
نمایش رشته‌ای با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای استفاده و قوانین انجمن») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
رده‌بندی خودکار پست‌ها از چند منظر (موضوع/کیفیت). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF روی رأی و پست + RateLimiter روی ارسال + View::e() روی همهٔ خروجی. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ انجمن، پرسش و پاسخ و پشتیبانی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای استفاده و قوانین انجمن») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «پاسخ در ۱ ساعت»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست اشتراکی + opcode cache**. migration با روش «از مرورگر». نگهداری: بایگانی رشته‌های قدیمی + پاکسازی روزانهٔ اسپم.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `view`, `validator`, `rate-limiter`, `pipeline`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`11-forums.md`](../11-forums.md)
