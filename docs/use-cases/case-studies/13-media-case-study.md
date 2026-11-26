# مطالعهٔ موردی — رسانه صوتی و تصویری (دسته 13)

> نسخه مهارت: **0.0.2** | دسته: [`13-media.md`](../13-media.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**رادیو پادکست فارسی** — میزبان پادکست با پخش جریانی و فهرست‌بندی اپیزود.
- **کاربران:** شنوندگان، تولیدکننده، مدیر
- **محدودیت کلیدی:** پهنای باند محدود + streaming بدون CDN اختصاصی
- **پروفایل پیشنهادی:** team | **معماری:** MVC + cache لایه‌ای

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
رادیو پادکست فارسی به ما مراجعه کرد: میزبان پادکست با پخش جریانی و فهرست‌بندی اپیزود. کاربران هدف شنوندگان، تولیدکننده، مدیر هستند
و محدودیت کلیدی «پهنای باند محدود + streaming بدون CDN اختصاصی» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به پهنای باند محدود + streaming بدون CDN اختصاصی، معماری **MVC + cache لایه‌ای** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `cache`, `http-client`, `rate-limiter`, `view`. دلیل انتخاب: پوشش مستقیم
نیازهای «پخش با Range request + cache manifest» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **podcasts, episodes, play_history**. ویژگی امضای این پروژه: پخش با Range request + cache manifest. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
پخش‌کننده با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («توضیح اپیزود و معرفی میزبان») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
پیشنهاد اپیزود مرتبط از چند منظر. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: RateLimiter روی پخش + CSRF + hotlink protection با .htaccess. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ رسانه صوتی و تصویری) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («توضیح اپیزود و معرفی میزبان») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «کیفیت HD»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با پهنای باند بالا + nginx**. migration با روش «از مرورگر». نگهداری: پاکسازی کش قدیمی + پایش پهنای باند.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `cache`, `http-client`, `rate-limiter`, `view`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`13-media.md`](../13-media.md)
