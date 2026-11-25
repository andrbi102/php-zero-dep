# سطح ۲ — متخصصان (۳۰ نقش)

> نسخه مهارت: **0.0.2** | قرارداد کامل هر نقش: `scaffolds/workers/` | کارت وظایف هر نقش: `atomic-tasks/by-role/`

**این سطح چه نقشی دارد؟** صاحب‌تخصص‌های فنی: رمزنگاری، کوئری،
مهاجرت دیتابیس، CSRF، عملکرد، فرانت‌اند و… . تصمیم فنیِ تخصصی در حوزه خودشان را
می‌گیرند و در مشورت‌ها (C1..C7) Consultant هستند.

**راهنمای استفاده از جدول:** وقتی وظیفه‌ای نیاز به تخصص عمیق دارد (نه صرفاً اجرا)،
نقش همین سطح است؛ مصداق: «طراحی رویکرد رمزنگاری» (T-017) برخلاف «پیاده‌سازی
X25519» (T-025). برای مصادیق، جدول وظایف هر نقش را در `atomic-tasks/by-role/` ببین.


| ID | EN | فارسی | وظایف | مجوزها | کرسی شورا |
|----|----|-------|-------|--------|-----------|
| T-018 | API Designer | طراح API | 5 | read-architecture, write-docs |  |
| T-019 | Routing Specialist | متخصص مسیریابی | 4 | read-architecture, write-architecture |  |
| T-020 | Schema Designer | طراح شِما | 5 | read-database, write-database |  |
| T-021 | Query Optimizer | بهینه‌ساز کوئری | 5 | read-database, read-performance |  |
| T-022 | DB Migration Specialist | متخصص مهاجرت دیتابیس | 5 | read-database, write-database, exec-migration |  |
| T-023 | Session Specialist | متخصص نشست | 4 | read-security, write-security |  |
| T-024 | Auth Specialist | متخصص احراز هویت | 5 | read-security, write-security |  |
| T-025 | Crypto Implementer | پیاده‌ساز رمزنگاری | 5 | read-crypto, write-crypto |  |
| T-026 | Key Custodian | نگهبان کلید | 4 | read-crypto |  |
| T-027 | Input Validation Specialist | متخصص اعتبارسنجی ورودی | 5 | read-security, write-security |  |
| T-028 | Output Escaping Specialist | متخصص گریز خروجی | 4 | read-security, write-frontend |  |
| T-029 | CSRF Specialist | متخصص CSRF | 5 | read-security, write-security |  |
| T-030 | Headers & CSP Specialist | متخصص هدر و CSP | 4 | read-security, write-security |  |
| T-031 | Upload Security Specialist | متخصص امنیت آپلود | 4 | read-security, write-security |  |
| T-032 | Template Engine Specialist | متخصص موتور قالب | 5 | read-frontend, write-frontend |  |
| T-033 | Forms & Validation UI Specialist | متخصص فرم و اعتبارسنجی UI | 4 | read-frontend, write-frontend |  |
| T-034 | Accessibility Specialist | متخصص دسترس‌پذیری | 4 | read-frontend, write-frontend |  |
| T-035 | Asset Pipeline Specialist | متخصص دارایی‌ها | 4 | read-frontend, write-frontend |  |
| T-036 | Cache Specialist | متخصص کش | 5 | read-performance, write-performance |  |
| T-037 | OPcache Tuner | تنظیم‌کننده OPcache | 4 | read-performance, write-performance |  |
| T-038 | Profiling Specialist | متخصص پروفایلینگ | 4 | read-performance, write-performance |  |
| T-039 | Memory Optimizer | بهینه‌ساز حافظه | 4 | read-performance, write-performance |  |
| T-040 | Event Sourcing Specialist | متخصص Event Sourcing | 5 | read-architecture, write-architecture |  |
| T-041 | CQRS Specialist | متخصص CQRS | 4 | read-architecture, write-architecture |  |
| T-042 | Event Dispatcher Specialist | متخصص دیسپچر رویداد | 4 | read-architecture, write-architecture |  |
| T-043 | Graph Specialist | متخصص گراف | 4 | read-graph, write-graph |  |
| T-044 | Mind Map Specialist | متخصص نقشه ذهنی | 4 | read-graph, write-graph |  |
| T-045 | MCP Integration Specialist | متخصص یکپارچه‌سازی MCP | 5 | read-mcp, write-mcp |  |
| T-046 | Tooling Specialist | متخصص ابزار | 4 | read-quality, write-docs, read-rea, write-rea |  |
| T-047 | Deployment Specialist | متخصص استقرار | 4 | read-deployment, write-deployment, exec-deploy |  |

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
