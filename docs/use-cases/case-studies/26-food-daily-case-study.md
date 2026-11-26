# مطالعهٔ موردی — غذا، رستوران و خرید روزانه (دسته 26)

> نسخه مهارت: **0.0.2** | دسته: [`26-food-daily.md`](../26-food-daily.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**سفره (سامانه سفارش غذا محلی)** — منو، سفارش و تحویل با موجودی لحظه‌ای.
- **کاربران:** مشتریان، رستوران، پیک
- **محدودیت کلیدی:** موجودی لحظه‌ای + ردیابی سفارش
- **پروفایل پیشنهادی:** team | **معماری:** MVC + Event-Driven

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
سفره (سامانه سفارش غذا محلی) به ما مراجعه کرد: منو، سفارش و تحویل با موجودی لحظه‌ای. کاربران هدف مشتریان، رستوران، پیک هستند
و محدودیت کلیدی «موجودی لحظه‌ای + ردیابی سفارش» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به موجودی لحظه‌ای + ردیابی سفارش، معماری **MVC + Event-Driven** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `event-store`, `cache`, `rate-limiter`, `command-bus`. دلیل انتخاب: پوشش مستقیم
نیازهای «موجودی لحظه‌ای با cache + ردیابی با event-store» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **restaurants, menu_items, orders, deliveries**. ویژگی امضای این پروژه: موجودی لحظه‌ای با cache + ردیابی با event-store. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
منو با design-tokens + سبد با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («توضیح غذا و سیاست تحویل») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
پیشنهاد غذا از چند منظر (امتیاز/زمان/فاصله). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF + RateLimiter روی سفارش + hashing پرداخت + View::e(). خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ غذا، رستوران و خرید روزانه) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («توضیح غذا و سیاست تحویل») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «تحویل ۳۰ دقیقه»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با SSL + FileCache**. migration با روش «از مرورگر». نگهداری: بازآوری موجودی + گزارش روزانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `event-store`, `cache`, `rate-limiter`, `command-bus`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`26-food-daily.md`](../26-food-daily.md)
