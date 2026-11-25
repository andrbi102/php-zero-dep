# پروتکل مشورت (۷ گام + SLA)

> نسخه مهارت: **0.0.1** | آرشیو: consultation/log/

| گام | اقدام | مسئول | SLA |
|-----|-------|-------|-----|
| C1 | ثبت درخواست | Requester | فوری |
| C2 | تعیین Consultant | Requester / Manager | ۲ ساعت |
| C3 | تحلیل و پاسخ | Consultant | ۲۴ ساعت |
| C4 | بررسی ریسک | Consultant + Security (اگر امنیتی) | ۴۸ ساعت |
| C5 | تصمیم | Requester | ۲۴ ساعت |
| C6 | ثبت مخالفت | Consultant | — |
| C7 | آرشیو | Doc Writer | — |

**قواعد:**
- مشورت امنیتی برای `severity >= high` **اجباری** (SLA بحرانی: ۴ ساعت).
- مخالفت کتبی Consultant الزاماً در ADR ثبت می‌شود (گام C6).
- مشورت افقی (هم‌سطح) ثبت‌شده؛ عمودی آزاد (02-hierarchy.md).

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
