# کاربرد صحیح رمزنگاری

رمز دلخواه: secretbox (sodium) + nonce تازه هر بار + کلید ۳۲ بایتی.
رمز عبور: password_hash(PASSWORD_ARGON2ID) — هرگز md5/sha1 برای رمز.
هش داده عمومی: hash('sha256') یا sodium_crypto_generichash (blake2b).
توکن: random_bytes(32) — نه md5(uniqid()) نه mt_rand.
جزئیات منحنی بیضوی: پوشه docs/elliptic-curve/.

### ✅ Checklist

- [ ] هرکاربرد رمز با ابزار درست انتخاب می‌شود (جدول بالا)
- [ ] کلیدها از env، طول با ثابت رسمی چک می‌شود
- [ ] مقایسه‌های امن با hash_equals

### ❌ Anti-patterns
- base64 به‌عنوان رمزنگاری
- کلید و داده در یک فایل

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
