# سطح ۰ — شورا (۱۰ نقش: ۶ ثابت + ۴ کرسی متغیر)

> نسخه مهارت: **0.0.2** | قرارداد کامل هر نقش: `scaffolds/workers/` | کارت وظایف هر نقش: `atomic-tasks/by-role/`

**این سطح چه نقشی دارد؟** شورا داوران نهایی مدل است: وتو، تأیید انتشار و
امضای ADR. ۶ عضو ثابت همیشه هستند؛ ۴ کرسی متغیر (دیتابیس/رمزنگاری/فرانت‌اند/یکپارچه‌سازی)
توسط سرپرستان حوزه (سطح ۱) بر اساس موضوع جلسه پر می‌شوند. مکانیزم‌های رأی: `06-council.md`.

**راهنمای استفاده از جدول:** ستون «وظایف» = تعداد وظایف اتمیک همان نقش در `RULES.json`؛
ستون «مجوزها» = مجوزهای همان نقش از ۴۲ مجوز. برای استخدام، قرارداد کامل را از
`scaffolds/workers/T-NNN-*.md` بردار؛ برای مرور سریع وظایف، `atomic-tasks/by-role/T-NNN.md`.
نکته: وتوی امنیتی T-001 مطلق است — هیچ سطح و هیچ فشاری آن را نمی‌شکند (قاعده ۵ AGENTS.md).


| ID | EN | فارسی | وظایف | مجوزها | کرسی شورا |
|----|----|-------|-------|--------|-----------|
| T-000 | Council Architect | معمار شورا | 8 | read-all, approve-architecture, veto-architecture, approve-migration, approve-release |  |
| T-001 | Council Security | امنیت شورا | 8 | read-all, approve-security, veto-security |  |
| T-002 | Council Quality | کیفیت شورا | 7 | read-all, approve-quality, veto-quality |  |
| T-003 | Council Performance | عملکرد شورا | 6 | read-all, approve-performance, veto-performance |  |
| T-004 | Council Compliance | انطباق شورا | 6 | read-all, veto-compliance |  |
| T-005 | Release Manager | مدیر انتشار | 8 | read-all, write-release, approve-release, exec-release |  |
| T-006 | Council Database Seat | کرسی دیتابیس شورا | 6 | read-all, read-database, approve-migration | متغیر |
| T-007 | Council Crypto Seat | کرسی رمزنگاری شورا | 6 | read-all, read-crypto, approve-security | متغیر |
| T-008 | Council Frontend Seat | کرسی فرانت‌اند شورا | 6 | read-all, read-frontend, approve-quality | متغیر |
| T-009 | Council Integration Seat | کرسی یکپارچه‌سازی شورا | 6 | read-all, read-mcp, approve-mcp | متغیر |

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
