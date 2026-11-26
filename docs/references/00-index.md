# مراجع الگوبرداری‌شده (v0.0.2)

> نسخه مهارت: **0.0.2** | این پوشه در v0.0.2 افزوده شد.
>
> **چرا این پوشه:** مهارت `php-zero-dep` از پنج مخزن مرجع الگوبرداری کرده است.
> این پوشه، برای هر مخزن: هویت، ساختار، آنچه آموختیم، آنچه الگوبرداری شد، و
> آنچه صادقانه الگوبرداری *نشد* را ثبت می‌کند. هر سند یک ADR متناظر در
> [`ADR/`](../../ADR/) دارد.

## فهرست مراجع

| # | مخزن | لایسنس | چه الگوبرداری شد | سند | ADR |
|---|------|--------|------------------|------|-----|
| ۰۱ | [NousResearch/hermes-agent](https://github.com/NousResearch/hermes-agent) | MIT | مدل ارکستراسیون Worker (delegate/kanban/review/moa/estop, IterationBudget, DenialCircuitBreaker, handoff, capability token) | [01-hermes-agent.md](01-hermes-agent.md) | ADR-0015, 0016, 0017, 0018, 0024 |
| ۰۲ | [blader/humanizer](https://github.com/blader/humanizer) | MIT | کاتالوگ الگوهای «بوی AI» + پایپلاین mark→draft→claim-check برای متن UI تولیدشده | [02-humanizer.md](02-humanizer.md) | ADR-0019 |
| ۰۳ | [firecrawl/firecrawl](https://github.com/firecrawl/firecrawl) | AGPL-3.0 (server) / MIT (SDKs) | واترفال موتور، حذف عناصر ناخواسته، robots.txt، فیلتر لینک، charset — در PHP خالص | [03-firecrawl.md](03-firecrawl.md) | ADR-0020 |
| ۰۴ | [nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) | MIT | معماری توکن سه‌لایه، صدور دوگانه (PHP/JSON/CSS)، persistence با guard، provenance | [04-ui-ux-pro-max.md](04-ui-ux-pro-max.md) | ADR-0021 |
| ۰۵ | [nvidia/skillspector](https://github.com/nvidia/skillspector) | Apache-2.0 | ممیزی امتیازی ۰-۱۰۰، completeness ledger، baseline suppression، قرارداد JSON | [05-skillspector.md](05-skillspector.md) | ADR-0022 |
| ۰۶ | *(ساختار داخلی)* | — | نقشهٔ ترکیب: چگونه ۵ مرجع در یک جریان فاز به فاز می‌نشینند | [06-composition-map.md](06-composition-map.md) | — |

## قاعدهٔ الگوبرداری (صداقت مهندسی)

۱. **آنچه الگوبرداری شد، در کد/سند پیاده شده است** — هر الگو یک artifact قابل‌راستی‌آزمایی دارد (scaffold، doc، یا script).
۲. **آنچه الگوبرداری نشد، مستند است** — هیچ ادعای «این قسمت از فلان مخزن آمده» بدون پیاده‌سازی واقعی در این پوشه نوشته نمی‌شود (درس L-002).
۳. **لایسنس رعایت شده** — هر مخزن با لایسنس خود؛ کدGPL/AGPL هرگز در خروجی MIT کپی نمی‌شود؛ فقط *الگو* (ایدهٔ ساختاری) الگوبرداری می‌شود.
۴. **هیچ وابستگی اضافه نمی‌شود** — الگوبرداری از ساختار است، نه از کدِ اجراییِ وابسته. قید ۱-۲ (`Zero-Composer`, `Zero-Dependency`) نقض‌ناپذیرند.
۵. **منبع هر الگو در سند متناظر با مسیر فایل دقیق ذکر می‌شود** — نه «به‌طور کلی از hermes الهام گرفته شد».

## بازبینی

منابع این پوشه با `scripts/check-links.sh` (بازبینی سه‌ماهه) و `scripts/skill_score.py`
(ممیزی امتیازی — الگوبرداری‌شده از skillspector) راستی‌آزمایی می‌شوند.

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
