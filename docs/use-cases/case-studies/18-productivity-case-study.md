# مطالعهٔ موردی — بهره‌وری، همکاری و ابزار آنلاین (دسته 18)

> نسخه مهارت: **0.0.2** | دسته: [`18-productivity.md`](../18-productivity.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**تیم هم‌اکنون (ابزار همکاری تیم‌های کوچک)** — وظایف، یادداشت‌ها و اشتراک با هم‌زمانی سبک.
- **کاربران:** اعضای تیم، مدیر
- **محدودیت کلیدی:** هم‌زمانی بدون WebSocket (long-polling)
- **پروفایل پیشنهادی:** team | **معماری:** HMVC + Event-Driven

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
تیم هم‌اکنون (ابزار همکاری تیم‌های کوچک) به ما مراجعه کرد: وظایف، یادداشت‌ها و اشتراک با هم‌زمانی سبک. کاربران هدف اعضای تیم، مدیر هستند
و محدودیت کلیدی «هم‌زمانی بدون WebSocket (long-polling)» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به هم‌زمانی بدون WebSocket (long-polling)، معماری **HMVC + Event-Driven** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `hmvc-dispatcher`, `event-dispatcher`, `cache`, `session`, `rate-limiter`. دلیل انتخاب: پوشش مستقیم
نیازهای «حضور با long-polling + یادداشت هم‌زمان (cache lock)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **tasks, notes, members, presence**. ویژگی امضای این پروژه: حضور با long-polling + یادداشت هم‌زمان (cache lock). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
بورد کانبان با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای همکاری و میان‌برها») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
اولویت‌بندی وظایف از چند منظر. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: session سه‌پرچمی + CSRF + RateLimiter روی هم‌زمانی + View::e(). خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ بهره‌وری، همکاری و ابزار آنلاین) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای همکاری و میان‌برها») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «هم‌زمانی واقعی»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **VPS + PHP-FPM**. migration با روش «از مرورگر». نگهداری: فشرده‌سازی رویداد قدیمی + بازبینی کش.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `hmvc-dispatcher`, `event-dispatcher`, `cache`, `session`, `rate-limiter`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`18-productivity.md`](../18-productivity.md)
