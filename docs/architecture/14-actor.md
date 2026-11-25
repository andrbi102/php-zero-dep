# Actor Model — همزمانی بدون حافظه مشترک

PHP فیبر (Fiber) دارد اما اسکدیولر بومی ندارد؛ رویکرد واقع‌بینانه:
**Queue + Worker** — صف دیتابیس‌محور (جدول jobs با قفل اتمیک SELECT...FOR UPDATE
یا claim با UPDATE ... LIMIT 1 در SQLite/MySQL) و یک Worker loop با
`scaffolds/` صف. مرجع الگو: Innmind/actors (مفهومی).

### ✅ Checklist

- [ ] هر Job idempotent است
- [ ] Dead-letter برای Jobهای شکست‌خورده
- [ ] Worker با pcntl یا cron محرک می‌شود — بدون daemon اجباری

### ❌ Anti-patterns
- Actor با حافظه مشترک بین پردازش‌ها
- Job بدون timeout

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
