# مطالعهٔ موردی — محلی، اجتماعی و مشارکتی (دسته 24)

> نسخه مهارت: **0.0.2** | دسته: [`24-local-community.md`](../24-local-community.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**محلهٔ ما (پلتفرم محلی)** — اعلان محلی، رویداد و مشارکت همسایگان.
- **کاربران:** همسایگان، مدیر محله
- **محدودیت کلیدی:** متن کوتاه + جستجوی محله
- **پروفایل پیشنهادی:** solo | **معماری:** MVC سبک

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
محلهٔ ما (پلتفرم محلی) به ما مراجعه کرد: اعلان محلی، رویداد و مشارکت همسایگان. کاربران هدف همسایگان، مدیر محله هستند
و محدودیت کلیدی «متن کوتاه + جستجوی محله» است. در این فاز نیازها و قیود ثبت و پروفایل
solo انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-055 Forms Developer**.

### فاز ۲ — معماری (Architecture)
با توجه به متن کوتاه + جستجوی محله، معماری **MVC سبک** و پروفایل **solo**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل solo، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `router`, `view`, `database`, `validator`, `csrf`. دلیل انتخاب: پوشش مستقیم
نیازهای «اعلان محله با فیلتر جغرافیایی سبک» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **announcements, events, members**. ویژگی امضای این پروژه: اعلان محله با فیلتر جغرافیایی سبک. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
فید محله با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («راهنمای مشارکت و قوانین») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-055 Forms Developer با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل solo).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
— (solo: moa غیرفعال). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: CSRF + RateLimiter + View::e() + session. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ محلی، اجتماعی و مشارکتی) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («راهنمای مشارکت و قوانین») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «همسایه‌ی تأییدشده»» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **هاست اشتراکی + SQLite**. migration با روش «از مرورگر». نگهداری: بایگانی اعلان قدیمی + پشتیبان روزانه.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `router`, `view`, `database`, `validator`, `csrf`
- Worker راهبر: **T-055 Forms Developer**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`24-local-community.md`](../24-local-community.md)
