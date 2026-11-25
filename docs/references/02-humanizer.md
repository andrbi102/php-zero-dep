# ۰۲ — مرجع: blader/humanizer

> نسخه مهارت: **0.0.2** | لایسنس مرجع: **MIT** | زبان: Markdown (+ یک اعتبارسنج Python استاندارد-تنها)
> URL: <https://github.com/blader/humanizer>
> ADR متناظر: [0019](../../ADR/0019-humanizer-prose-polish.md)

## هویت

**توجه:** این *نبود* کتابخانهٔ معروف Humanizer (.NET/C# برای قالب‌بندی
رشته/تاریخ/عدد). این مخزن، یک **skill ایجنت** است (یک پرامپت Markdown) از
Siqi Chen که متن AI-صدا را به نثر انسانی بازنویسی می‌کند **بدون تغییر
 facts** — بر پایهٔ «Signs of AI writing» ویکی‌پدیا. تنها کد اجرایی‌اش یک
اعتبارسنج Python است که فقط stdlib استفاده می‌کند.

## ساختار

```
humanizer/
├── SKILL.md            # پرامپت اصلی (منبع حقیقت، ~۳۳KB)
├── README.md           # نصب + جدول الگوها
├── AGENTS.md           # چگونه skill را ویرایش کنیم
├── CHANGELOG.md
├── package.json        # manifest pi-package (pi.skills -> repo root)
├── agents/openai.yaml  # manifest سازگار OpenAI
├── .claude-plugin/     # plugin.json + marketplace.json
├── .cursor-plugin/     # plugin.json
└── scripts/validate-package.py   # اعتبارسنج no-deps
```

## آنچه آموختیم

۱. **کاتالوگ الگوی شماره‌گذاری‌شده با Before/After:** ۲۶ الگو در ۶ گروه
   (staging، rhythm، inflation، formatting، chat leftovers، wrong-reader) —
   هر الگو با Watch-for / Problem / Before / After. اصلاح «به نام»، نه «به حسب».
۲. **پایپلاین سه‌گانه:** mark (نشانه‌گذاری tells) → draft (بازنویسی:
   merge/split/reorder + add specifics) → **claim-preservation check** (facts,
   names, numbers, dates, citations باید زنده بمانند) → final.
۳. **درجه‌بندی شدت + قاعدهٔ هم‌وقوعی:** یک tell ضعیفِ تنها (مثلاً یک تثلث در
   فهرست ویژگی) اصلاح نمی‌شود؛ فقط وقتی tells خوشه‌بندی کنند اقدام کن.
۴. **تطبیق صدا با نمونه:** نمونهٔ نثر موجود پروژه را بپذیر و ریتم/نقطه‌گذاری/
   نرخ dash جدید را با آن هماهنگ کن تا رشته‌های جدید با codebase یکی شوند.
۵. **file-mode discipline:** کل فایل را بخوان، فقط نثر را ویرایش کن،
   code/paths/YAML/links را دست‌نزن — مستقیماً به «فقط رشته‌های انسانی‌خواندنی
   در یک فایل PHP را بازنویس کن» نگاشت می‌شود.

## آنچه الگوبرداری شد (با artifact)

| الگو | artifact ما |
|------|-------------|
| کاتالوگ الگوی شماره‌گذاری‌شده Before/After (۲۶ الگو در ۶ گروه) | [`docs/frontend/08-humanizer-prose.md`](../frontend/08-humanizer-prose.md) — کاتالوگ تطبیق‌یافته برای متن UI تولیدشده در PHP |
| پایپلاین mark→draft→claim-check | [`scaffolds/humanizer.php`](../../scaffolds/humanizer.php) — آشکارساز «بوی AI» مبتنی‌بر-قاعده (بدون LLM): نشانه‌گذاری tells در رشته‌های PHP |
| درجه‌بندی شدت + قاعدهٔ هم‌وقوعی | همان اسکلت: نمرهٔ ۰-۱۰۰ + آستانهٔ اقدام (تنها tell ضعیف = نادیده) |
| file-mode discipline (فقط رشته‌های انسانی) | مستند در `docs/frontend/08-humanizer-prose.md` بخش «file-mode» |
| validate-package no-deps | الگوبرداری در [`scripts/skill_score.py`](../../scripts/skill_score.py) (اعتبارسنج no-deps) |

## آنچه الگوبرداری *نشد*

| آنچه حذف شد | چرا |
|-------------|-----|
| بازنویسی واقعی نثر با LLM | مهارت ما، کد تولید می‌کند نه نثر ادبی؛ آشکارساز قاعده‌محور کافی است (و قابل‌ممیزی ایستا) |
| manifests چند-پلتفرمی (.claude/.cursor/openai) | مهارت php-zero-dep با ساختار خودش (SKILL.md frontmatter) توزیع می‌شود |
| voice-matching با نمونهٔ پروژه | پیچیدگی غیرضروری در v0.0.۲؛ در صورت نیاز در نسخهٔ بعدی |

## گارد سازگاری

- آشکارساز `humanizer.php` **بدون LLM** است — فقط regex/قواعد رشته‌ای، با
  `ext-mbstring`. هیچ وابستگی اضافه نمی‌شود (قید ۱-۲).
- هیچ کد از مخزن humanizer کپی نشده — فقط *ایدهٔ ساختاری کاتالوگ* الگوبرداری شد
  (لایسنس MIT اجازه می‌داد، اما قید «بدون کپی کد خارجی» محکم‌تر رعایت شد).

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: humanizer (MIT, Siqi Chen/blader)
