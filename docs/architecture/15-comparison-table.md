# جدول جامع مقایسه معماری‌ها

| معماری | ایده اصلی | Zero-Dep مرجع | نکته ایجنت |
|---------|-----------|---------------|-----------|
| MVC | Model/View/Controller | Trongate، MicroMVC | View escape خودکار |
| HMVC | MVC سلسله‌مراتبی | DynaPort X، Trongate | فقط Dispatcher |
| Modular | هر ماژول یک اپ مینیاتوری | upMVC، Trongate، Ace | تست/استقرار ماژولار |
| Microservice | سرویس‌های کوچک مستقل | webrium/core | stateless |
| Serverless | تابع بدون سرور | NexusPHP | بدون FS پایدار |
| REST API | منابع + HTTP + JSON | Restler، Flight | نسخه‌بندی /v1/ |
| DDD/Hexagonal | جداسازی Domain | php-ddd-demo | Domain بدون PDO/HTTP |
| Onion | وابستگی به داخل | final class + interface | هم‌پوشانی Hexagonal |
| Vertical Slice | بر اساس Feature | دستی از اصول | هم‌پوشانی CQRS |
| EDA | رویداد ناهمزمان | snicco/event-dispatcher | Dispatcher ≈۲۰ خط |
| Event Sourcing | جریان رویداد | Dudulina، Event Machine | فقط Bounded Context |
| CQRS | Command/Query جدا | xprt64/dudulina | Bus دستی |
| Pipeline | زنجیره قابل ترکیب | NexusPHP | آسان‌ترین برای دست‌نویس |
| Actor | همزمانی مستقل | Innmind/actors | واقعیت: Queue + Worker |

### ✅ Checklist

- [ ] هر معماری انتخابی یک ADR دارد
- [ ] انتخاب با راهنمای 16-selection-guide

### ❌ Anti-patterns
- ترکیب شعاری چند معماری بدون ADR

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
