# Serverless — اجرای تابع بدون سرور

ورودی/خروجی استاندارد با `scaffolds/serverless-handler.php`.
مرجع بهینه‌سازی: **NexusPHP** (خوداظهاری README: <3.5ms latency، <2.0MB memory).
محدودیت ساختاری: فایل‌سیستم پایدار وجود ندارد — نشست و کش باید
به‌صورت cookie-based (`php-storageless-sessions` در کاتالوگ) یا دیتابیس
بیرونی باشند؛ Cold Start در بودجه عملکرد لحاظ شود.

### ✅ Checklist

- [ ] هیچ وضعیتی در فایل‌سیستم محلی ذخیره نمی‌شود
- [ ]  Cold Start در بودجه latency محاسبه شده
- [ ] کد مسیر گرم (autoloader + کلاس‌های لازم) پیش‌بارگذاری شده

### ❌ Anti-patterns
- file_put_contents برای کش در runtime
- فرض ادامه‌دار بودن پردازش بین دو request

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
