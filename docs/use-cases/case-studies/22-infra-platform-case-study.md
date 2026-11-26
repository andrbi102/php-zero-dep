# مطالعهٔ موردی — زیرساخت، پلتفرم و دانلود (دسته 22)

> نسخه مهارت: **0.0.2** | دسته: [`22-infra-platform.md`](../22-infra-platform.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**مخزن پکیج فارسی (میزان بسته)** — میزبان دانلود بسته با mirror و آمار.
- **کاربران:** توسعه‌دهندگان، مدیر مخزن
- **محدودیت کلیدی:** پهنای باند بالا + mirror ساده
- **پروفایل پیشنهادی:** full | **معماری:** Microservice + Cache

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
مخزن پکیج فارسی (میزان بسته) به ما مراجعه کرد: میزبان دانلود بسته با mirror و آمار. کاربران هدف توسعه‌دهندگان، مدیر مخزن هستند
و محدودیت کلیدی «پهنای باند بالا + mirror ساده» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به پهنای باند بالا + mirror ساده، معماری **Microservice + Cache** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `cache`, `http-client`, `rate-limiter`, `tracing`. دلیل انتخاب: پوشش مستقیم
نیازهای «هدایت mirror با cache + آمار دانلود» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **packages, versions, downloads, mirrors**. ویژگی امضای این پروژه: هدایت mirror با cache + آمار دانلود. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
صفحه بسته با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای نصب و توضیح بسته») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
انتخاب mirror از چند منظر (جغرافیا/بار/دسترس‌پذیری). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: RateLimiter روی دانلود + CSRF روی آپلود + Tracing + checksum. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ زیرساخت، پلتفرم و دانلود) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای نصب و توضیح بسته») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «دانلود نامحدود»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **سرور با پهنای باند بالا + nginx**. migration با روش «خط فرمان». نگهداری: هم‌گام‌سازی mirror + پاکسازی نسخه‌های قدیمی.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `cache`, `http-client`, `rate-limiter`, `tracing`
- Worker راهبر: **T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`22-infra-platform.md`](../22-infra-platform.md)
