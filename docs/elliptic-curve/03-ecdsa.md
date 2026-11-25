# ECDSA و امضای دیجیتال

با ext-sodium: Ed25519 (sodium_crypto_sign_detached) — سریع، امن،
بدون پارامتر خطاخیز. با openssl: ECDSA روی P-256 (برای سازگاری TLS/WS).
هرگز ECDSA با k تصادفی دست‌ساز پیاده نشود — k تکراری کلید خصوصی را
لو می‌دهد (حمله SVG/PS3). امضا detached + verify با کلید عمومی.

### ✅ Checklist

- [ ] امضا همیشه با پیاده‌سازی مرجع (sodium/openssl)
- [ ] verify قبل از هر عمل حساس
- [ ] جفت‌کلید با sodium_crypto_sign_keypair تولید می‌شود

### ❌ Anti-patterns
- RSA با کلید ۱۰۲۴ بیتی
- امضا و رمزنگاری با یک کلید مشترک

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
