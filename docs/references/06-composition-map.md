# نقشهٔ ترکیب ۵ مرجع — چگونه با هم می‌نشینند

> نسخه مهارت: **0.0.2** | این سند در اصلاحات پس از انتشار v0.0.2 افزوده شد.
>
> پنج سند مرجع پیشین (`01`..`05`) هرکدام یک مخزن را به‌تنهایی معرفی می‌کنند.
> این سند نشان می‌دهد چگونه **الگوهای آن‌ها در یک جریان واحد ترکیب می‌شوند** تا
> مهارت `php-zero-dep` را بسازند — کدام الگو در کدام فاز وارد می‌شود و کجا به
> کدام دیگر متصل می‌شود.

## جریان ترکیب (فاز به فاز)

```
┌─────────────────────────────────────────────────────────────────────┐
│  F1-F3  نیاز → معماری → نقش‌ها                                         │
│   └─ hermes-agent (۰۱): مدل Worker و سلسله‌مراتب ۷۹ نقش               │
│       └─ هر Worker قرارداد scaffolds/workers/_template.md (§۹)        │
├─────────────────────────────────────────────────────────────────────┤
│  F4-F5  پیاده‌سازی scaffold‌ها                                          │
│   ├─ ui-ux-pro-max (۰۴): design-tokens.php (primitive→semantic→     │
│   │   component) → خروجی فرانت‌اند                                     │
│   └─ firecrawl (۰۳): web-fetch.php → واکشی/پاکسازی محتوای وب          │
├─────────────────────────────────────────────────────────────────────┤
│  F6     ارکستراسیون اجرا                                              │
│   └─ hermes-agent (۰۱): worker-orchestrator.php                       │
│       delegate → kanban → review → moa → estop                        │
│       ├─ review (SoD-5) → بازبین مستقل                                 │
│       └─ moa → برای تصمیمات چندمنظری، مشاورت ترکیبی                    │
├─────────────────────────────────────────────────────────────────────┤
│  F7     انسانی‌سازی خروجی متنی                                         │
│   └─ humanizer (۰۲): humanizer.php روی متن UI تولیدشده                │
│       └─ claimCheck: ادعاهای واقعی حفظ، کلیشهٔ AI حذف                  │
├─────────────────────────────────────────────────────────────────────┤
│  F8-F9  بازبینی و انتشار                                              │
│   └─ skillspector (۰۵): skill_score.py — ممیزی امتیازی ۶ درگاهی       │
│       └─ content-completeness: راستی‌آزمایی پیوند مراجع↔ADR↔scaffold   │
└─────────────────────────────────────────────────────────────────────┘
```

## نقش هر مرجع در ترکیب

| مرجع | لایهٔ ترکیب | artifact پیاده‌شده | به چه چیز متصل می‌شود |
|------|-----------|-------------------|----------------------|
| **hermes-agent** (۰۱) | اسکلت سازمانی | `worker-orchestrator.php`، `_template.md` §۹، `docs/org/12-worker-orchestration.md` | همهٔ ۷۹ قرارداد Worker؛ capability token به ADR-0024 |
| **humanizer** (۰۲) | پالایش خروجی | `humanizer.php`، `docs/frontend/08-humanizer-prose.md` | خروجی متنی هر Worker (F7)؛ ADR-0019 |
| **firecrawl** (۰۳) | ورودی محتوا | `web-fetch.php`، `examples/10-web-fetch-demo.php` | use-caseهای crawler (دسته ۰۹، ۱۷، ۱۸)؛ ADR-0020 |
| **ui-ux-pro-max** (۰۴) | خروجی بصری | `design-tokens.php`، `docs/frontend/09-design-tokens.md` | فرانت‌اند هر پروژه؛ ADR-0021 |
| **skillspector** (۰۵) | خودارزیابی | `skill_score.py`، `verify_org.py`، `security-audit.php` | هماهنگ‌کنندهٔ F8؛ ADR-0022 |

## سه مسیر نمونه (چگونه الگوها زنجیر می‌شوند)

**مسیر A — سایت شرکتی ساده (پروفایل solo):**
`hermes` (یک Worker T-055 Forms) → `ui-ux-pro-max` (design-tokens برای صفحه‌بندی) →
`humanizer` (پالایش متن about) → `skillspector` (skill_score قبل از تحویل).
*firecrawl و moa در solo غیرفعال‌اند.*

**مسیر B — crawler مقالات علمی:**
`hermes` (T-014 + T-053) → `firecrawl` (web-fetch + filterLinks + robotsAllows) →
`ui-ux-pro-max` (نمایش نتایج) → `humanizer` (خلاصه‌سازی) → `skillspector` (بازبینی).

**مسیر C — تصمیم معماری پیچیده (پروفایل full):**
`hermes` (moa با چند مشاور) → تصمیم → `ui-ux-pro-max`/`firecrawl` حسب نیاز →
`skillspector` (gate) → انتشار با `humanizer` روی CHANGELOG/RELEASE.

## قاعدهٔ ترکیب

۱. **هر مرجع مستقل قابل‌حذف است** — اگر یک مخزن مرجع در آینده منسوخ شد، artifact
   آن (scaffold/doc) باقی می‌ماند چون به PHP خالص بازنویسی شده؛ لایسنس منتقل نیست.
۲. **پیوندها صریح‌اند** — هر artifact در سند مرجع‌اش مسیر فایل دقیق دارد؛ این
   سند فقط *نقشهٔ کلی* است، منبع حقیقت اسناد `01`..`05` هستند.
۳. **skillspector حلقهٔ بازخورد است** — `skill_score.py` پیوستگی کل ترکیب را
   (درگاه `content-completeness`) راستی‌آزمایی می‌کند: هر قرارداد بخش ۹ دارد، هر
   مرجع به ADR موجود لینک می‌دهد.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | سند مرتبط: [`00-index.md`](00-index.md)
