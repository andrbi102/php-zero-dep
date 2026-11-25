# تحلیل ایستا (PHPStan / Phan)

هدف بخش ۱۴ SPEC: PHPStan level 8 (clean) و PSR-12 با
PHP_CodeSniffer — هر دو ابزار dev (phar تک‌فایلی بدون Composer در
runtime پروژه). اجرا در فاز F6 و گیت انتشار F9؛ یافته‌های level<max
در ADR مستند. Phan جایگزین برای تحلیل کلان‌داده‌ای.

### ✅ Checklist

- [ ] PHPStan level 8 در گیت انتشار
- [ ] phar تک‌فایلی، نصب بدون Composer
- [ ] baseline فقط با ADR و مهلت

### ❌ Anti-patterns
- خاموش کردن analyser برای «رفع» خطا

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
