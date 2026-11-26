# مطالعهٔ موردی — هوش مصنوعی و تحلیل داده (دسته 19)

> نسخه مهارت: **0.0.2** | دسته: [`19-ai-data.md`](../19-ai-data.md)
> مطالعهٔ ۱۰-فازی concreet — کارفرمای فرضی + پیوند به scaffold/Worker/humanizer/skillspector.

## کارفرما

**خانهٔ داده (استارتاپ تحلیل داده)** — پلتفرم pipeline داده با داشبورد و export.
- **کاربران:** تحلیل‌گر، مدیر داده
- **محدودیت کلیدی:** پردازش دسته‌ای بدون Redis/queue
- **پروفایل پیشنهادی:** full | **معماری:** Pipeline + Event Sourcing

---

## ۱۰ فاز

### فاز ۱ — کشف (Discovery)
خانهٔ داده (استارتاپ تحلیل داده) به ما مراجعه کرد: پلتفرم pipeline داده با داشبورد و export. کاربران هدف تحلیل‌گر، مدیر داده هستند
و محدودیت کلیدی «پردازش دسته‌ای بدون Redis/queue» است. در این فاز نیازها و قیود ثبت و پروفایل
full انتخاب می‌شود. خروجی: سند نیازمندی‌ها با قید صریح «هاست اشتراکی،
بدون Composer». نقش راهبر: **T-019 Data Engineer + T-010 Chief Architect**.

### فاز ۲ — معماری (Architecture)
با توجه به پردازش دسته‌ای بدون Redis/queue، معماری **Pipeline + Event Sourcing** و پروفایل **full**
انتخاب شد. فازهای F1..F10 به‌ترتیب اجرا می‌شوند. در پروفایل full، فازهای
غیرضروری (مانند moa اگر solo است) صرفه‌جویی می‌شوند. تصمیم معماری در ADR ثبت می‌شود.

### فاز ۳ — انتخاب اسکلت (Scaffold Selection)
از ۲۹ اسکلت موجود، موارد مرتبط با این دسته: `pipeline`, `command-bus`, `event-store`, `cache`, `tracing`. دلیل انتخاب: پوشش مستقیم
نیازهای «pipeline چندمرحله‌ای با Tracing + cache نتیجه» بدون افزودن وابستگی. هیچ اسکلت خارج‌از‌فهرست استفاده نمی‌شود.

### فاز ۴ — پیاده‌سازی هسته (Core Implementation)
مدل داده: **datasets, jobs, results, exports**. ویژگی امضای این پروژه: pipeline چندمرحله‌ای با Tracing + cache نتیجه. پیاده‌سازی
با `declare(strict_types=1)` و Prepared Statements (PDO). خروجی فاز: اسکلت‌های هسته +
migration اولیه.

### فاز ۵ — فرانت‌اند و توکن طراحی (ui-ux-pro-max)
داشبورد با design-tokens با `design-tokens.php` ساخته می‌شود (primitive→semantic→component،
صدور PHP/JSON/CSS از یک resolver). متن UI («تفسیر خودکار نتیجه (با حفظ دقت)») برای فاز ۷标记 می‌شود.

### فاز ۶ — ارکستراسیون (hermes-agent)
T-019 Data Engineer + T-010 Chief Architect با بدیههٔ delegate فرزندان لازم را spawn می‌کند (در پروفایل full).
kanban برای مدیریت کارت‌های وظیفه و review برای بازبینی مستقل (SoD-5). moa برای
انتخاب مدل تحلیل از چند منظر (دقت/هزینه/سرعت). هر Worker یک capability_token با پیشوند `hmac:` دریافت می‌کند.

### فاز ۷ — امنیت (Security)
تمرکز امنیتی این دسته: crypto برای دادهٔ حساس + RateLimiter روی API + Tracing + deny-by-default. خروجی‌ها با `View::e()` رندر می‌شوند و هیچ
`composer.json` تولید نمی‌شود. در موارد حساس (دادهٔ هوش مصنوعی و تحلیل داده) deny-by-default اعمال می‌شود.

### فاز ۸ — انسانی‌سازی متن (humanizer)
`humanizer.php` روی متن UI («تفسیر خودکار نتیجه (با حفظ دقت)») اجرا می‌شود: کلیشه‌های «بوی AI»
(powerful/seamless/leverage) حذف می‌شوند ولی ادعای واقعی «ادعای «دقت ۹۹٪» (باید مستند باشد)» با claimCheck
حفظ می‌شود (ok=true، lost خالی). هیچ ادعای جعل‌شدهٔ جدید اضافه نمی‌شود.

### فاز ۹ — بازبینی و انتشار (skillspector)
`skill_score.py` با ۶ درگاه (org/security/behavior/ingest-budget/version/content-completeness)
اجرا می‌شود. هدف: `SKILL SCORE: 0/100 [SAFE]` و `completeness: 100%`. review با بازبین
مستقل (SoD-5) پیش از پذیرش هر artifact.

### فاز ۱۰ — تحویل و نگهداری (Handoff/Maintenance)
استقرار در **VPS با RAM بالا**. migration با روش «خط فرمان». نگهداری: بازآفرینی نتیجه از رویداد + پایش pipeline.
دسترس‌پذیری و بازبینی دوره‌ای لینک‌ها با `check-links.sh` (سه‌ماهه).

---

## artifactهای این مطالعه

- اسکلت‌های کلیدی: `pipeline`, `command-bus`, `event-store`, `cache`, `tracing`
- Worker راهبر: **T-019 Data Engineer + T-010 Chief Architect**
- درگاه کیفی: `skill_score.py` (content-completeness)
- الگوی ترکیب: [`docs/references/06-composition-map.md`](../../references/06-composition-map.md)

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | دستهٔ متناظر: [`19-ai-data.md`](../19-ai-data.md)
