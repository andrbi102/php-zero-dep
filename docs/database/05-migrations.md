# Migration — scaffolds/db-migrate.php

فایل‌های .sql مرتب (001_create_users.sql و...)؛ ثبت نام در جدول
migrations؛ قابل اجرا از CLI و از مرورگر (هاست اشتراکی بدون CLI).
هر migration یکبار‌اجراست: idempotent (IF NOT EXISTS) و بدون داده
تست در seed. Rollback نوشته می‌شود اما به‌صورت فایل معکوس دستی.

### ✅ Checklist

- [ ] migration صعودی + rollback مستند
- [ ] اجرای از مرورگر محافظت‌شده (توکن env)
- [ ] تست migration روی dialect هدف

### ❌ Anti-patterns
- DROP TABLE در migration بدون backup
- seed داده production در migration

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
