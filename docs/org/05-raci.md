# ماتریس RACI

> نسخه مهارت: **0.0.1** | هر وظیفه کلیدی دقیقاً یک A (Accountable) دارد

## خواندن ماتریس — چهار حرف یعنی چه؟

| حرف | فارسی | تعهد | اشتباه رایج |
|-----|-------|------|--------------|
| **R** — Responsible | مسئول اجرا | کار را «انجام می‌دهد» | فکر کردن R = تصمیم‌گیرنده (نه؛ A تصمیم می‌گیرد) |
| **A** — Accountable | پاسخ‌گو | نتیجه را «پاسخ می‌دهد»؛ دقیقاً یک نفر | دو A برای یک وظیفه (ممنوع — ابهام پاسخ‌گویی) |
| **C** — Consulted | مشاور | «دوسویه» مشورت می‌شود | تلقی C به‌عنوان تأیید (نه؛ C نظر می‌دهد، A می‌پذیرد) |
| **I** — Informed | مطلع | «یک‌سویه» در جریان گذاشته می‌شود | خواستن تأیید از I |

## ماتریس وظایف کلیدی

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

## نمونه خواندن یک ردیف — «انتخاب معماری»

برای انتخاب معماری: Chief Architect (T-010) کارِ مقایسه و پیشنهاد را
**انجام می‌دهد** (R)؛ اما نتیجه نهایی را Council Architect (T-000)
**پاسخ می‌دهد** (A) و بدون امضای او (قاعده ۱۳ AGENTS.md) انتخاب، رسمی
نیست. همه متخصصان **مشورت می‌شوند** (C) — مثلاً Crypto Specialist درباره
بار رمزنگاریِ هر گزینه اظهار نظر می‌کند — و همه **مطلع می‌شوند** (I).
اگر معماری انتخابی شکست بخورد، پرسش «چه کسی پاسخ‌گو بود؟» دقیقاً یک
پاسخ دارد: T-000.

RACI وظایف کلیدی دیگر در raci/tasks/ تکمیل می‌شود؛ تغییر ماتریس با مشورت
ثبت‌شده (07-consultation.md) انجام می‌شود.

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
