# EDA — معماری رویدادمحور

اجزا ناهمزمان با رویداد حرف می‌زنند. Dispatcher را دستی بنویسید (≈۲۰ خط):
`scaffolds/event-dispatcher.php`. مرجع حداقل‌وابستگی: snicco/event-dispatcher
(PSR-14). برای انتشار بین‌پردازشی، صف دیتابیس‌محور با Worker (بخش Actor).

### ✅ Checklist

- [ ] Dispatcher دست‌نویس یا PSR-14 vendored
- [ ] رویدادها readonly و بدون رفتار جانبی در construct
- [ ] خطای listener نشتی به فراخوان نمی‌کند

### ❌ Anti-patterns
- اجرای هم‌زمان کار سنگین داخل listener
- رویدادهای خوابیده (هر چیزی را event نکن)

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
