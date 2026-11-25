# XSS و CSRF

XSS: هر خروجی متغیر با `View::e()` = htmlspecialchars(ENT_QUOTES |
ENT_SUBSTITUTE, UTF-8). خروجی JSON با json_encode (بدون raw echo).
CSRF: توکن HMAC-SHA256(session_id, APP_KEY) با `scaffolds/csrf.php`
+ کوکی SameSite=Strict + مقایسه hash_equals. برای فرم‌های AJAX، توکن
از meta tag خوانده و در هدر X-CSRF-Token ارسال شود.

### ✅ Checklist

- [ ] escape در لحظه خروجی، نه ورودی
- [ ] توکن CSRF در هر فرم POST
- [ ] Content-Type درستی برای JSON (بدون sniffing)

### ❌ Anti-patterns
- htmlspecialchars بدون ENT_QUOTES
- توکن CSRF در URL (لاگ می‌شود)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
