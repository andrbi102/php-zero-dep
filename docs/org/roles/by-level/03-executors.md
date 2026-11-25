# سطح ۳ — مجریان (۱۸ نقش)

> نسخه مهارت: **0.0.1** | قرارداد کامل هر نقش: `scaffolds/workers/` | کارت وظایف هر نقش: `atomic-tasks/by-role/`

**این سطح چه نقشی دارد؟** سازنده‌های اصلی: اتولودر، روتر، DI،
دیتابیس، فرانت‌اند، مهاجرت و… — همان‌هایی که کد را با scaffolds می‌سازند.
خروجی‌شان هرگز بدون بازبینی Code Reviewer (T-074) پذیرفته نمی‌شود (SoD-5).

**راهنمای استفاده از جدول:** در فاز F4 بیشترین فعالیت همین سطح است؛ برای هر
کامپوننت، executor مربوطه را از جدول انتخاب و از `scaffolds/` پیاده‌سازی کن —
هیچ‌گاه scaffold را از صفر بازنویسی نکن (قید ۴ بخش B در `AGENTS.md`).


| ID | EN | فارسی | وظایف | مجوزها | کرسی شورا |
|----|----|-------|-------|--------|-----------|
| T-048 | Core Developer | توسعه‌دهنده هسته | 4 | read-architecture, write-core |  |
| T-049 | Router Developer | توسعه‌دهنده روتر | 4 | read-architecture, write-core |  |
| T-050 | DI Container Developer | توسعه‌دهنده کانتینر DI | 4 | read-architecture, write-core |  |
| T-051 | Database Developer | توسعه‌دهنده دیتابیس | 4 | read-database, write-database, write-core |  |
| T-052 | View Developer | توسعه‌دهنده View | 3 | read-frontend, write-frontend |  |
| T-053 | Session Developer | توسعه‌دهنده نشست | 3 | read-security, write-security |  |
| T-054 | Crypto Developer | توسعه‌دهنده رمزنگاری | 3 | read-crypto, write-crypto |  |
| T-055 | Forms Developer | توسعه‌دهنده فرم | 3 | read-frontend, write-frontend, write-security |  |
| T-056 | Event Infrastructure Developer | توسعه‌دهنده زیرساخت رویداد | 4 | read-architecture, write-core |  |
| T-057 | Queue Worker Developer | توسعه‌دهنده صف و Worker | 3 | read-architecture, write-core |  |
| T-058 | CLI Tool Developer | توسعه‌دهنده CLI | 3 | read-architecture, write-core |  |
| T-059 | Test Engineer | مهندس تست | 4 | read-quality, write-tests |  |
| T-060 | Documentation Developer | توسعه‌دهنده مستندات | 3 | read-all, write-docs |  |
| T-061 | Migration Developer | توسعه‌دهنده مهاجرت | 3 | read-database, write-database |  |
| T-062 | Example Developer | توسعه‌دهنده مثال | 3 | read-all, write-docs, write-core |  |
| T-063 | MCP Tool Developer | توسعه‌دهنده ابزار MCP | 4 | read-mcp, write-mcp |  |
| T-064 | Frontend Developer | توسعه‌دهنده فرانت‌اند | 4 | read-frontend, write-frontend |  |
| T-065 | Refactor Developer | توسعه‌دهنده بازآفرینی | 3 | read-all, write-core |  |

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
