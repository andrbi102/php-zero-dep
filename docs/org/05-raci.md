# ماتریس RACI

> نسخه مهارت: **0.0.1** | هر وظیفه کلیدی دقیقاً یک A (Accountable) دارد

| وظیفه | R (مسئول) | A (پاسخگو) | C (مشاور) | I (مطلع) |
|--------|-----------|------------|-----------|----------|
| انتخاب معماری | Chief Architect | Council Architect | همه متخصصان | همه |
| تأیید ADR | Council Architect | Council Architect | Security Engineer | Chief Architect |
| پیاده‌سازی Core | Core Developer | Chief Architect | DB Lead, Frontend Lead | QA Lead |
| بازبینی امنیتی | Security Engineer | Council Security | Security Auditor | همه |
| اجرای تست | Test Writer | QA Lead | Performance Lead | همه |
| انتشار | Release Manager | Council Architect | همه شورا | همه |
| مهاجرت دیتابیس | DB Migration Specialist | Database Lead | Query Optimizer | QA Lead |
| پیاده‌سازی رمزنگاری | Crypto Developer | Crypto Specialist | Security Engineer | QA Lead |
| یکپارچه‌سازی MCP | MCP Tool Developer | Integration Lead | MCP Integration Specialist | Security Engineer |
| مستندسازی | Documentation Developer | Doc Writer | Frontend Lead | همه |

RACI وظایف کلیدی دیگر در raci/tasks/ تکمیل می‌شود؛ تغییر ماتریس با مشورت
ثبت‌شده (07-consultation.md) انجام می‌شود.

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
