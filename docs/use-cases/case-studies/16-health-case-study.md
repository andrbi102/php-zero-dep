# مطالعهٔ موردی — سلامت و سبک زندگی (دسته 16)

> نسخه مهارت: **0.0.2** | دسته: [`16-health.md`](../16-health.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**کلینیک سلامت برتر** — نوبت‌دهی و پروندهٔ بیمار با حریم خصوصی سخت‌گیرانه.
- **کاربران:** بیماران، پذیرش، پزشک
- **محدودیت کلیدی:** محرمانگی دادهٔ پزشکی + رعایت انطباق
- **پروفایل پیشنهادی:** full | **معماری:** Modular + Event Sourcing برای پرونده

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
کلینیک سلامت برتر به ما مراجعه کرد: نوبت‌دهی و پروندهٔ بیمار با حریم خصوصی سخت‌گیرانه. کاربران هدف بیماران، پذیرش، پزشک هستند
و محدودیت کلیدی «محرمانگی دادهٔ پزشکی + رعایت انطباق» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-001 Council Security + T-011 Security Engineer**.

### فاز ۲ — معماری (Architecture)
با توجه به محرمانگی دادهٔ پزشکی + رعایت انطباق، معماری **Modular + Event Sourcing برای پرونده** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `crypto`, `event-store`, `session`, `rate-limiter`, `tracing`. دلیل انتخاب: پوشش مستقیم
نیازهای «رمزگذاری libsodium پرونده + audit log رویداد» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **patients, appointments, records (encrypted)**. ویژگی امضای این پروژه: رمزگذاری libsodium پرونده + audit log رویداد. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
نوبت‌دهی با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («یادآوری نوبت و توضیح procedure») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-001 Council Security + T-011 Security Engineer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
ارزیابی ریسک افشای داده از چند منظر. هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: crypto libsodium + Tracing هر دسترسی + session کوتاه + RateLimiter + deny-by-default. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ سلامت و سبک زندگی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («یادآوری نوبت و توضیح procedure») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «تأیید FDA» (هرگز — جلو است)» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **سرور اختصاصی رمزشده + گواهی**. migration با روش «خط فرمان با رمزگشایی». نگهداری: چرخش کلید + حسابرسی دسترسی هفتگی.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `crypto`, `event-store`, `session`, `rate-limiter`, `tracing`
- Worker راهبر: **T-001 Council Security + T-011 Security Engineer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`16-health.md`](../16-health.md)
