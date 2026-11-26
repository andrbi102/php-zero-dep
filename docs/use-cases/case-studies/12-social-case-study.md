# مطالعهٔ موردی — شبکه اجتماعی و ارتباطات (دسته 12)

> نسخه مهارت: **0.0.2** | دسته: [`12-social.md`](../12-social.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**هما (شبکه اجتماعی محلی)** — فید، دنبال‌کردن و پیام خصوصی با اعلان لحظه‌ای.
- **کاربران:** کاربران، مدیر محتوا
- **محدودیت کلیدی:** اعلان Push بدون WebSocket (long-polling)
- **پروفایل پیشنهادی:** full | **معماری:** Event-Driven + HMVC

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
هما (شبکه اجتماعی محلی) به ما مراجعه کرد: فید، دنبال‌کردن و پیام خصوصی با اعلان لحظه‌ای. کاربران هدف کاربران، مدیر محتوا هستند
و محدودیت کلیدی «اعلان Push بدون WebSocket (long-polling)» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-016 Integration Lead**.

### فاز ۲ — معماری (Architecture)
با توجه به اعلان Push بدون WebSocket (long-polling)، معماری **Event-Driven + HMVC** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `event-dispatcher`, `event-store`, `cache`, `rate-limiter`, `hmvc-dispatcher`. دلیل انتخاب: پوشش مستقیم
نیازهای «اعلان با EventDispatcher (long-polling سبک)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **users, follows, posts, notifications**. ویژگی امضای این پروژه: اعلان با EventDispatcher (long-polling سبک). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
فید با design-tokens + Lazy load با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای حریم خصوصی و قوانین») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-016 Integration Lead با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
اولویت‌بندی فید از چند سیگنال. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: session سه‌پرچمی + RateLimiter روی پیام + CSRF + hashing رمز عبور Argon2. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ شبکه اجتماعی و ارتباطات) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای حریم خصوصی و قوانین») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «رمزگذاری end-to-end»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **VPS + PHP-FPM**. migration با روش «خط فرمان». نگهداری: فشرده‌سازی رویداد قدیمی + بازبینی کش.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `event-dispatcher`, `event-store`, `cache`, `rate-limiter`, `hmvc-dispatcher`
- Worker راهبر: **T-016 Integration Lead**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`12-social.md`](../12-social.md)
