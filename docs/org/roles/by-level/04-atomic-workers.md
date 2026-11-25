# سطح ۴ — کارگران اتمیک (۸ نقش، ۸ وظیفه — هر نقش دقیقاً ۱ وظیفه)

> نسخه مهارت: **0.0.2** | قرارداد کامل هر نقش: `scaffolds/workers/` | کارت وظایف هر نقش: `atomic-tasks/by-role/`

**این سطح چه نقشی دارد؟** کارگران تک‌وظیفه‌ای: هر نقش
دقیقاً یک فعل مشخص (فایل بساز، لینت بزن، tag بزن). هیچ تصمیم معماری — فقط اجرای
آمیک با DoD شفاف. مناسب صف‌های کوتاه و تکرارشدنی.

**راهنمای استفاده از جدول:** وظیفه کوچک و کاملاً مشخص داری؟ همین سطح؛
اگر برای انجام کار باید «تصمیم» بگیری، سطحِ اشتباهی است — بالاتر برو.


| ID | EN | فارسی | وظایف | مجوزها | کرسی شورا |
|----|----|-------|-------|--------|-----------|
| T-066 | File Creator | ایجادکننده فایل | 1 | read-architecture, write-docs |  |
| T-067 | File Modifier | ویرایشگر فایل | 1 | read-architecture, write-docs |  |
| T-068 | Test Runner | اجراکننده تست | 1 | read-all |  |
| T-069 | Lint Runner | اجراکننده Lint | 1 | read-all |  |
| T-070 | Link Checker | بررسی‌گر لینک | 1 | read-all |  |
| T-071 | Changelog Updater | به‌روزرسان CHANGELOG | 1 | read-architecture, write-docs |  |
| T-072 | Tag Creator | ایجادگر تگ | 1 | read-architecture, write-docs |  |
| T-073 | Logger | ثبت‌گر رویداد | 1 | read-architecture, write-docs |  |

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
