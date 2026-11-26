# مطالعهٔ موردی — سازمانی، دولتی و خدمات شهری (دسته 17)

> نسخه مهارت: **0.0.2** | دسته: [`17-gov.md`](../17-gov.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**شهرداری محلهٔ نمونه** — سامانه خدمت شهری (گزارش معابر، پرداخت عوارض).
- **کاربران:** شهروندان، کارمند شهرداری
- **محدودیت کلیدی:** دسترس‌پذیری + بایگانی بلندمدت + انطباق
- **پروفایل پیشنهادی:** full | **معماری:** Modular + CQRS + Tracing

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
شهرداری محلهٔ نمونه به ما مراجعه کرد: سامانه خدمت شهری (گزارش معابر، پرداخت عوارض). کاربران هدف شهروندان، کارمند شهرداری هستند
و محدودیت کلیدی «دسترس‌پذیری + بایگانی بلندمدت + انطباق» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-004 Council Compliance + T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به دسترس‌پذیری + بایگانی بلندمدت + انطباق، معماری **Modular + CQRS + Tracing** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `command-bus`, `query-bus`, `tracing`, `event-store`, `rate-limiter`. دلیل انتخاب: پوشش مستقیم
نیازهای «ردیابی درخواست با Tracing (W3C) + بایگانی رویداد» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **tickets, payments, districts, audit**. ویژگی امضای این پروژه: ردیابی درخواست با Tracing (W3C) + بایگانی رویداد. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
فرم خدمت با design-tokens + دسترس‌پذیری با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای خدمت و پاسخ به پرسش‌های متداول») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-004 Council Compliance + T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
ارزیابی انطباق از چند منظر (قانون/امنیت/دسترس‌پذیری). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF + RateLimiter + Tracing + deny-by-default + بایگانی tamper-evident. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ سازمانی، دولتی و خدمات شهری) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای خدمت و پاسخ به پرسش‌های متداول») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «پاسخ فوری»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **مرکز دادهٔ دولتی + PHP-FPM**. migration با روش «خط فرمان + بایگانی رویداد». نگهداری: حفظ بایگانی ۷ ساله + بازبینی انطباق سالانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `command-bus`, `query-bus`, `tracing`, `event-store`, `rate-limiter`
- Worker راهبر: **T-004 Council Compliance + T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`17-gov.md`](../17-gov.md)
