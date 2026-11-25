# CQRS — جداسازی Command از Query

مسیر نوشتن (Command) از مسیر خواندن (Query) جدا می‌شود.
`scaffolds/command-bus.php` و `scaffolds/query-bus.php` هر دو دست‌نویس‌اند.
مرجع: xprt64/dudulina. فقط در صورت نیاز واقعی (نامتقارنی شدید خواندن/نوشتن)
فعال شود؛ در غیر این صورت هزینه پیچیدگی بر منفعت می‌چربد.

### ✅ Checklist

- [ ] Command فقط یک بار اجرا می‌شود (idempotency key)
- [ ] Query هیچ اثری روی وضعیت ندارد
- [ ] Handlerها بدون وابستگی به هم

### ❌ Anti-patterns
- CQRS بدون نیاز واقعی
- Query که از طریق CommandBus اجرا می‌شود

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
