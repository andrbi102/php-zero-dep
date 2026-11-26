# مطالعهٔ موردی — مالی، پرداخت و بیمه (دسته 10)

> نسخه مهارت: **0.0.2** | دسته: [`10-finance.md`](../10-finance.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**تعاونی اعتبار امید (مؤسسه مالی کوچک)** — مدیریت وام و اقساط با حسابرسی کامل.
- **کاربران:** اعضا، کارشناس وام، حسابرس
- **محدودیت کلیدی:** حسابرسی دقیق + رعایت محرمانگی
- **پروفایل پیشنهادی:** full | **معماری:** Event Sourcing + CQRS

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
تعاونی اعتبار امید (مؤسسه مالی کوچک) به ما مراجعه کرد: مدیریت وام و اقساط با حسابرسی کامل. کاربران هدف اعضا، کارشناس وام، حسابرس هستند
و محدودیت کلیدی «حسابرسی دقیق + رعایت محرمانگی» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-011 Security Engineer + T-014 DB Lead**.

### فاز ۲ — معماری (Architecture)
با توجه به حسابرسی دقیق + رعایت محرمانگی، معماری **Event Sourcing + CQRS** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `event-store`, `command-bus`, `query-bus`, `crypto`, `tracing`. دلیل انتخاب: پوشش مستقیم
نیازهای «محاسبه اقساط با ثبت رویداد هر تغییر (event-store)» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **loans, installments, events (audit log)**. ویژگی امضای این پروژه: محاسبه اقساط با ثبت رویداد هر تغییر (event-store). پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
داشبورد اقساط با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («نامه اقساط و اطلاع‌رسانی تأخیر») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-011 Security Engineer + T-014 DB Lead با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
ارزیابی ریسک وام از چند منظر (مالی/امنیت/انطباق). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: crypto libsodium برای دادهٔ حساس + Tracing برای هر تراکنش + RateLimiter روی API. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ مالی، پرداخت و بیمه) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («نامه اقساط و اطلاع‌رسانی تأخیر») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «بهره صفر»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **سرور اختصاصی با دیسک رمزشده**. migration با روش «خط فرمان + بازآفرینی event-store». نگهداری: بازیابی از رویدادها + حسابرسی ماهانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `event-store`, `command-bus`, `query-bus`, `crypto`, `tracing`
- Worker راهبر: **T-011 Security Engineer + T-014 DB Lead**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`10-finance.md`](../10-finance.md)
