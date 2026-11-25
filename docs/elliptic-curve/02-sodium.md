# رویکرد sodium — اولویت ۱

اولویت‌بندی Zero-Dependency:
۱. `ext-sodium` فعال → `sodium_crypto_*` (واقعاً Zero-Dependency)
۲. نبود → `sodium_compat` به‌صورت **vendored** در `scaffolds/crypto/` (لایسنس ISC)
۳. `openssl` فقط برای ECDSA/ECDH با منحنی‌های NIST
ممنوع: `composer require` در runtime. پیاده‌سازی: `scaffolds/crypto.php`
(encrypt/decrypt با secretbox، sign/verify با Ed25519، Argon2id).

### ✅ Checklist

- [ ] extension_loaded('sodium') بررسی می‌شود
- [ ] Nonce هر بار random_bytes تازه
- [ ] کلید از getenv('APP_KEY') — هرگز هاردکد

### ❌ Anti-patterns
- استفاده از openssl برای چیزهایی که sodium دارد
- nonce ثابت یا قابل پیش‌بینی

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
