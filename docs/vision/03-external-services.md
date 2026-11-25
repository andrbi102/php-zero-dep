# سرویس‌های خارجی

ارتباط فقط از `scaffolds/http-client.php` (timeout + retry +
traceparent). قواعد: URL و کلید از env؛ پاسخ خارجی Validator-شده
(هرگز blind trust)؛ degradation روشن (اگر سرویس پایین، feature غیرفعال
با پیام، نه crash)؛ هزینه/نرخ سرویس در ADR.

### ✅ Checklist

- [ ] هر سرویس خارجی ADR دارد
- [ ] پاسخ اعتبارسنجی می‌شود
- [ ] degradation بدون کرش

### ❌ Anti-patterns
- حلقه retry بی‌پایان روی سرویس قطع

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
