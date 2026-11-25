# نقش‌ها (۷۹)

> نسخه مهارت: **0.0.1** | فهرست کامل هر سطح در roles/by-level/ — قرارداد کامل هر نقش در scaffolds/workers/ — کارت وظایف هر نقش در atomic-tasks/by-role/

| سطح | تعداد | فایل |
|------|-------|------|
| ۰ — شورا | 10 | roles/by-level/00-council.md |
| ۱ — مدیران ارشد | 8 | roles/by-level/01-seniors.md |
| ۲ — متخصصان | 30 | roles/by-level/02-specialists.md |
| ۳ — مجریان | 18 | roles/by-level/03-executors.md |
| ۴ — کارگران اتمیک | 8 | roles/by-level/04-atomic-workers.md |
| Cross-cutting | 5 | roles/by-level/05-cross-cutting.md |

شناسه‌گذاری: `T-NNN` به تفکیک سطح — T-000..009 شورا، T-010..017 ارشد،
T-018..047 متخصص، T-048..065 مجری، T-066..073 اتمیک، T-074..078 cross-cutting.

## سه نما برای هر نقش — کدام را کِی باز کنی؟

| نمایش | فایل | چه می‌دهد |
|--------|------|-----------|
| نمای سازمانی | `roles/by-level/` | نقش‌های هم‌سطح، تعداد وظایف، مجوزها، کرسی شورا |
| قرارداد کامل | `scaffolds/workers/T-NNN-*.md` | ۸ بخش قرارداد برای استخدام (Identity تا Lifecycle) |
| کارت وظایف | `atomic-tasks/by-role/T-NNN.md` | جدول وظایف اتمیک با فاز/خروجی/DoD + مجوزها — مرور سریع |

**نکته:** شمارشِ نقش‌ها و مجوزهای هر سطح از `RULES.json` تولید می‌شود
(`python3 scripts/verify_org.py`)؛ جداول این پوشه نمای همان داده‌اند.

## نقش‌های پرکاربرد — نقشه ذهنی سریع

- **همیشه در پروژه:** Chief Architect (T-010)، Code Reviewer (T-074)، Release Manager (T-005)
- **امنیت:** Council Security (T-001)، Security Engineer (T-011)، Security Auditor (T-075)
- **داده:** Database Lead (T-014)، DB Migration Specialist (T-022)، Migration Developer (T-061)
- **هسته اجرایی (F4):** Core (T-048)، Router (T-049)، DI Container (T-050) Developers
- **کیفیت/تست:** QA Lead (T-012)، Test Writer (T-077)
- **مستندسازی/انتشار:** Doc Writer (T-076)، Release Manager (T-005)

فهرست کامل ۷۹ نقش با جزئیات: فایل‌های by-level بالا.

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
