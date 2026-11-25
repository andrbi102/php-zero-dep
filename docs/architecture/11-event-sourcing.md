# Event Sourcing — ذخیره جریان رویداد

وضعیت از بازپخش رویدادها ساخته می‌شود. مرجع: **Dudulina**
(xprt64 — CQRS + ES)، **Event Machine** (proophsoftware).
پیاده‌سازی: `scaffolds/event-store.php` (جدول events با ایندکس aggregate_id).
فقط در Bounded Contextهایی که مدل DB کافی نیست (حسابرسی کامل/Time Travel).

### ✅ Checklist

- [ ] Event Store با PDO + جدول events، aggregate_id ایندکس‌شده
- [ ] بازپخش idempotent است
- [ ] Snapshot برای aggregateهای بلند
- [ ] محدود به Bounded Context خاص

### ❌ Anti-patterns
- ES برای CRUD کوچک
- رویداد نسخه‌بندی‌نشده (schema version در payload)

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
