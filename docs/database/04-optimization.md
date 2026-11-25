# بهینه‌سازی کوئری

EXPLAIN قبل از بهینه‌سازی (تحلیل: Query Optimizer T-021)؛ ایندکس
مرکب برای الگوی WHERE/ORDER؛ بدون SELECT * در production؛ pagination با
cursor (WHERE id > ?) نه OFFSET بزرگ؛ کش نتایج سنگین با FileCache؛
نوشتن‌های انبوه در تراکنش واحد.

### ✅ Checklist

- [ ] EXPLAIN ضمیمه ADR بهینه‌سازی
- [ ] بدون SELECT * در مسیر production
- [ ] کوئری سنگین → کش با TTL

### ❌ Anti-patterns
- ایندکس روی هر ستون «بدون اندازه‌گیری»
- OFFSET 100000 برای صفحه ۱۰۰۰۰

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
