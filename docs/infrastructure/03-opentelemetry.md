# OpenTelemetry — Tracing دستی

بدون SDK اجباری (قید ۱۶ بخش ۱۲): tracing حداقلی با
`scaffolds/tracing.php` — ساخت trace-id/span-id سازگار W3C و تزریق
هدر traceparent به تماس‌های خروجی (HttpClient). صادر کردن spanها به
collector (OTLP over HTTP) اختیاری و با curl ممکن است.

### ✅ Checklist

- [ ] traceparent روی همه تماس‌های خروجی
- [ ] span-id تازه به‌ازای هر عملیات
- [ ] نمونه‌برداری (sampling) صریح

### ❌ Anti-patterns
- SDK PECL+Composer برای tracing ساده
- trace id قابل پیش‌بینی

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
