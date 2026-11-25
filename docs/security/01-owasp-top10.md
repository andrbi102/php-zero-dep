# OWASP Top 10 — نگاشت به کنترل‌های مهارت

| ریسک | کنترل در این مهارت |
|------|---------------------|
| A01 Broken Access Control | RBAC + مسیرها فقط از Router |
| A02 Cryptographic Failures | scaffolds/crypto.php (Argon2id، sodium) |
| A03 Injection | Prepared Statement + Validator + View::e() |
| A04 Insecure Design | مدل سازمانی فاز F1..F10 + ADR |
| A05 Security Misconfiguration | ErrorHandler بدون افشای خطا |
| A06 Vulnerable Components | صفر وابستگی + بازبینی سه‌ماهه منابع |
| A07 Auth Failures | SecureSession + RateLimiter ورود |
| A08 Integrity Failures | امضای HMAC (CSRF) + Ed25519 |
| A09 Logging Failures | Logger با redact |
| A10 SSRF | HttpClient با timeout و اعتبارسنجی URL |

### ✅ Checklist

- [ ] هر ردیف OWASP در چک‌لیست پذیرش (بخش ۱۴ SPEC) بسته شده
- [ ] تأیید نهایی با security-signoff فاز F7

### ❌ Anti-patterns
- بستن OWASP فقط با تست نفوذ پایانی
- اتکا به obscurity به‌جای کنترل

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
