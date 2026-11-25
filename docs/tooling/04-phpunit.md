# PHPUnit — فقط dev

PHPUnit نیازمند Composer است؛ بنابراین فقط ابزار dev است و هرگز
وابستگی runtime نمی‌شود (بخش ۱۲ SPEC). برای تست‌های داخلی مهارت،
`tests/smoke-test.php` و `tests/security-audit.php` با assert ساده و
exit code کار می‌کنند — CI بدون PHPUnit هم قابل اجراست.

### ✅ Checklist

- [ ] تست‌های مهارت با exit code استاندارد
- [ ] PHPUnit فقط در dev با phar
- [ ] هر اسکلت رفتار پایه تست دارد

### ❌ Anti-patterns
- درج phpunit در وابستگی‌های production

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
