# فرم‌ها

رندر با token CSRF (Csrf::field())، preserve مقدار ورودی پس از خطا،
برچسب for/id، متن خطا با role="alert" و aria-describedby.
اعتبارسنجی دو لایه: سمت کلاینت (UX) + سمت سرور با Validator (امنیت) —
سمت کلاینت به‌تنهایی هرگز کافی نیست.

### ✅ Checklist

- [ ] فرم بدون CSRF رندر نمی‌شود
- [ ] preserve input پس از validation error
- [ ] label/id/aria مربوط به هم

### ❌ Anti-patterns
- اعتماد به validate سمت کلاینت

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
