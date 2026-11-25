# راهنمای انتخاب معماری

```
CRUD کوچک                  → MVC / Modular
قواعد کسب‌وکار پیچیده       → DDD / Hexagonal / Onion
خواندن/نوشتن نامتقارن      → CQRS
حسابرسی کامل / Time Travel → Event Sourcing (Bounded Context)
همزمانی بالا               → Actor (واقع‌گرایانه: Queue + Worker)
تکرار سریع Feature         → Vertical Slice
اثرات جانبی real-time      → Event-Driven
```

پرسش‌های تصمیم: ۱) آیا Domain قواعد پیچیده دارد؟ ۲) آیا خواندن/نوشتن
نامتقارن است؟ ۳) آیا حسابرسی کامل لازم است؟ ۴) فاز استقرار چیست؟
پاسخ‌ها + ADR امضاشده توسط Council Architect (T-000-01).

### ✅ Checklist

- [ ] انتخاب با این راهنما + ADR ثبت‌شده
- [ ] برای پروژه کوچک، پروفایل solo (قاعده ۱۴ پرامپت اصلی)

### ❌ Anti-patterns
- Event Sourcing به‌خاطر «مدرن بودن»
- Microservice برای تیم تک‌نفره بدون توجیه

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
