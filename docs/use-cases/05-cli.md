# کاربرد: ابزار CLI

اسکلت: PHP_SAPI === 'cli'، argv parse ساده، خروجی رنگی ANSI و
exit code معنار (۰ موفق، ۱ خطا، ۲ استفاده نادرست). نمونه‌های زنده:
`scaffolds/db-migrate.php` و `scaffolds/mcp-server.php` (هر دو CLI-ready).
PHPStan/phan از همین الگو استفاده می‌کنند.

### ✅ Checklist

- [ ] exit code استاندارد
- [ ] help با --help
- [ ] بدون تعامل در حالت غیر TTY

### ❌ Anti-patterns
- echo خطا بدون exit != 0

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
