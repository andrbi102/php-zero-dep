# FrankenPHP

باینری استاتیک با PHP 8.5 embedded — بدون FPM، با HTTP/3 و
worker mode. مناسب استقرار تک‌باینری روی VPS.
محدودیت مستندشده: buildهای musl نمی‌توانند Xdebug بارگذاری کنند
(بخش ۱۲ SPEC) — پروفایلینگ را روی build glibc یا محیط dev انجام دهید.

### ✅ Checklist

- [ ] worker mode برای عملکرد (شبیه RoadRunner)
- [ ] debug با Xdebug فقط روی build glibc
- [ ] تست image در CI قبل از انتشار

### ❌ Anti-patterns
- انتظار Xdebug روی alpine/musl
- فرض دسترسی shell در هاست اشتراکی

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
