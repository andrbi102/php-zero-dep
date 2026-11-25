# چک‌لیست استقرار (بخش ۱۰ SPEC)

- [ ] مستندات Apache و Nginx جداگانه
- [ ] Dockerfile بدون Composer
- [ ] راهنمای هاست اشتراکی
- [ ] phpversion() guard
- [ ] FrankenPHP/RoadRunner/Podman در صورت استفاده
- [ ] Redis: ext-redis یا کش فایل‌محور
- [ ] Tracing دستی (بدون SDK)

محیط‌های خاص: runtime/ (FrankenPHP، RoadRunner) و
infrastructure/ (Podman، Nix، OpenTelemetry، Redis).

### ✅ Checklist

- [ ] هر محیط استقرار یک بخش راهنما دارد
- [ ] تست استقرار روی محیط هدف (نه فقط dev)

### ❌ Anti-patterns
- «روی سیستم من کار می‌کرد» بدون تست محیط

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
