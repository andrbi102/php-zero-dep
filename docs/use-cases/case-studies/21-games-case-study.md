# مطالعهٔ موردی — بازی و سرگرمی (دسته 21)

> نسخه مهارت: **0.0.2** | دسته: [`21-games.md`](../21-games.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**بازی کلمه (بازی مرورگری فارسی)** — بازی کلمه‌سازی چندنفره با امتیاز و رتبه‌بندی.
- **کاربران:** بازیکنان، مدیر بازی
- **محدودیت کلیدی:** نوبت‌گردانی سبک + ضد تقلب
- **پروفایل پیشنهادی:** team | **معماری:** HMVC + Event-Driven

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
بازی کلمه (بازی مرورگری فارسی) به ما مراجعه کرد: بازی کلمه‌سازی چندنفره با امتیاز و رتبه‌بندی. کاربران هدف بازیکنان، مدیر بازی هستند
و محدودیت کلیدی «نوبت‌گردانی سبک + ضد تقلب» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به نوبت‌گردانی سبک + ضد تقلب، معماری **HMVC + Event-Driven** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `hmvc-dispatcher`, `event-store`, `cache`, `rate-limiter`, `session`. دلیل انتخاب: پوشش مستقیم
نیازهای «نوبت با cache TTL + اعتبارسنجی کلمه + ضد تقلب» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **games, turns, words, scores**. ویژگی امضای این پروژه: نوبت با cache TTL + اعتبارسنجی کلمه + ضد تقلب. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
صفحه بازی با design-tokens + انیمیشن سبک با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای بازی و پیام‌های تشویق») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
تولید کلمه از چند منظر (سختی/نادر بودن). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: session + CSRF + RateLimiter روی نوبت + View::e() + ضد replay. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ بازی و سرگرمی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای بازی و پیام‌های تشویق») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «نمایش نخست»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **VPS + PHP-FPM**. migration با روش «از مرورگر». نگهداری: بایگانی بازی تمام‌شده + پایش تقلب.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `hmvc-dispatcher`, `event-store`, `cache`, `rate-limiter`, `session`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`21-games.md`](../21-games.md)
