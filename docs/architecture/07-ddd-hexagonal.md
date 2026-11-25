# DDD / Hexagonal / Clean — جداسازی Domain از Infrastructure

Domain هیچ ارجاعی به PDO/HTTP/Session ندارد؛ وابستگی‌ها با interface
به سمت Domain تزریق می‌شوند. مرجع: **yceruto/php-ddd-demo** (DDD + Hexagonal + CQS).
ساختار پیشنهادی: `src/Domain/` (Entity/ValueObject/DomainService — بدون هیچ import
زیرساختی)، `src/Application/` (UseCase)، `src/Infrastructure/` (PDO و پیاده‌سازی‌ها).

### ✅ Checklist

- [ ] پوشه Domain هیچ use از PDO/Http/Curl ندارد
- [ ] Value Objectها readonly و با assert درvariants
- [ ] UseCase از طریق interface به زیرساخت دست می‌زند

### ❌ Anti-patterns
- Entity با وابستگی مستقیم به Database wrapper
- منطق کسب‌وکار در Controller

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
