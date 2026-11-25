# سطح ۱ — مدیران ارشد (۸ نقش)

> نسخه مهارت: **0.0.2** | قرارداد کامل هر نقش: `scaffolds/workers/` | کارت وظایف هر نقش: `atomic-tasks/by-role/`

**این سطح چه نقشی دارد؟** سرپرستان حوزه‌ها: طراحی، هماهنگی، حل تعارض و
پاسخ‌گویی نهاییِ کارِ حوزه خود. Worker سطح ۳ و ۴ از همین سطح دستور می‌گیرد (قاعده N-1
در `02-hierarchy.md`). اعضای این سطح کرسی‌های متغیر شورا را هم پر می‌کنند.

**راهنمای استفاده از جدول:** برای هر حوزه (معماری، دیتابیس، امنیت، فرانت‌اند،
یکپارچه‌سازی، کیفیت، عملکرد) سرپرستش را از همین جدول پیدا کن؛ A ماتریس RACI
اکثر وظایف کلیدی، عضو همین سطح یا سطح ۰ است (`05-raci.md`).


| ID | EN | فارسی | وظایف | مجوزها | کرسی شورا |
|----|----|-------|-------|--------|-----------|
| T-010 | Chief Architect | معمار ارشد | 12 | read-all, write-architecture, write-docs |  |
| T-011 | Security Engineer | مهندس امنیت | 12 | read-security, write-security, read-all |  |
| T-012 | QA Lead | سرپرست تضمین کیفیت | 11 | read-quality, write-tests |  |
| T-013 | Performance Lead | سرپرست عملکرد | 11 | read-performance, write-performance |  |
| T-014 | Database Lead | سرپرست دیتابیس | 11 | read-database, write-database, exec-migration |  |
| T-015 | Frontend Lead | سرپرست فرانت‌اند | 11 | read-frontend, write-frontend |  |
| T-016 | Integration Lead | سرپرست یکپارچه‌سازی | 10 | read-mcp, read-all, write-mcp |  |
| T-017 | Crypto Specialist | متخصص رمزنگاری | 10 | read-crypto, write-crypto |  |

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
