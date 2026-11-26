# مطالعهٔ موردی — تجارت الکترونیک و فروشگاه (دسته 09)

> نسخه مهارت: **0.0.2** | دسته: [`09-ecommerce.md`](../09-ecommerce.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**بازار دست‌ساز ایران (فروشگاه صنایع‌دستی)** — فروشگاه با درگاه پرداخت، سبد و سفارش idempotent.
- **کاربران:** خریداران، فروشندگان خرد، مدیر فروش
- **محدودیت کلیدی:** پرداخت واقعی + موجودی هم‌زمان
- **پروفایل پیشنهادی:** full | **معماری:** Modular + REST API + CQRS

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
بازار دست‌ساز ایران (فروشگاه صنایع‌دستی) به ما مراجعه کرد: فروشگاه با درگاه پرداخت، سبد و سفارش idempotent. کاربران هدف خریداران، فروشندگان خرد، مدیر فروش هستند
و محدودیت کلیدی «پرداخت واقعی + موجودی هم‌زمان» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer + T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به پرداخت واقعی + موجودی هم‌زمان، معماری **Modular + REST API + CQRS** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `database`, `crypto`, `rate-limiter`, `command-bus`, `event-store`. دلیل انتخاب: پوشش مستقیم
نیازهای «سفارش idempotent با کلید idempotency + تراکنش موجودی» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **products, cart, orders, payments, inventory**. ویژگی امضای این پروژه: سفارش idempotent با کلید idempotency + تراکنش موجودی. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
صفحه محصول + سبد با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («توضیح محصول و سیاست بازگشت») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer + T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
تصمیم معماری checkout (امنیت vs عملکرد vs UX). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: crypto برای توکن درگاه + RateLimiter روی checkout + CSRF + hashing کارت‌های ذخیره‌شده. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ تجارت الکترونیک و فروشگاه) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («توضیح محصول و سیاست بازگشت») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «ارسال ۲۴ ساعته»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با گواهی SSL + Redis-نداشته (FileCache)**. migration با روش «از مرورگر با محافظت». نگهداری: بازآوری موجودی از event-store + پایش نرخ تبدیل.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `database`, `crypto`, `rate-limiter`, `command-bus`, `event-store`
- Worker راهبر: **T-055 Forms Developer + T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`09-ecommerce.md`](../09-ecommerce.md)
