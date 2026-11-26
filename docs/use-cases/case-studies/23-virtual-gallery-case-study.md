# مطالعهٔ موردی — نمایشگاه، موزه و گالری مجازی (دسته 23)

> نسخه مهارت: **0.0.2** | دسته: [`23-virtual-gallery.md`](../23-virtual-gallery.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**گالری هنر معاصر** — نمایشگاه مجازی با تور راهنما و فروش اثر.
- **کاربران:** بازدیدکنندگان، گرداننده، هنرمند
- **محدودیت کلیدی:** تصاویر باکیفیت + بار سبک
- **پروفایل پیشنهادی:** team | **معماری:** MVC + HMVC

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
گالری هنر معاصر به ما مراجعه کرد: نمایشگاه مجازی با تور راهنما و فروش اثر. کاربران هدف بازدیدکنندگان، گرداننده، هنرمند هستند
و محدودیت کلیدی «تصاویر باکیفیت + بار سبک» است. در این فاز نیازها و قیود ثبت و پروفایل
team انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-053 Content Writer**.

### فاز ۲ — معماری (Architecture)
با توجه به تصاویر باکیفیت + بار سبک، معماری **MVC + HMVC** و پروفایل **team**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل team، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `view`, `cache`, `database`, `rate-limiter`. دلیل انتخاب: پوشش مستقیم
نیازهای «تور راهنما با cache + فروش اثر» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **artworks, rooms, tours, sales**. ویژگی امضای این پروژه: تور راهنما با cache + فروش اثر. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
نمایشگاه با design-tokens + Lazy load با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («توضیح اثر و بیوگرافی هنرمند») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-053 Content Writer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل team).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
توضیح اثر از چند منظر (تاریخی/زیبایی‌شناختی). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF روی خرید + RateLimiter روی تصاویر + hotlink protection. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ نمایشگاه، موزه و گالری مجازی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («توضیح اثر و بیوگرافی هنرمند») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «اصالت اثر»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست با SSL + CDN سبک**. migration با روش «از مرورگر». نگهداری: بهینه‌سازی تصویر + بازبینی فروش.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `view`, `cache`, `database`, `rate-limiter`
- Worker راهبر: **T-053 Content Writer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`23-virtual-gallery.md`](../23-virtual-gallery.md)
