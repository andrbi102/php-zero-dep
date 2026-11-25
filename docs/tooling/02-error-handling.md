# مدیریت خطا

"scaffolds/error-handler.php": خطاها به ErrorException تبدیل، لاگ
با context، پیام عمومی 500 در production و جزئیات فقط در debug.
قاعده قید ۱۱ پرامپت: هیچ شکست خاموشی نیست — یا لاگ یا rethrow با
context (code، فایل، خط، ردیابی کاربر).

### ✅ Checklist

- [ ] هر catch یا لاگ می‌کند یا rethrow با context
- [ ] پیام خطا بدون داده حساس
- [ ] shutdown handler برای fatalها

### ❌ Anti-patterns
- catch (Throwable) {} خالی
- echo پیام استثنا به کاربر نهایی

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
