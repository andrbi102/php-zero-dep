# کاربرد: REST API

/v1/ + Router + json_encode؛ اعتبارسنجی با Validator و خطای ۴۲۲
با ساختار یکسان؛ احراز هویت توکنی (random_bytes 32 در هدر Authorization)؛
rate limit با RateLimiter (۴۲۹ + Retry-After)؛ مستندات با ApexDocs
(OpenAPI 3.1). paginated با cursor؛ ETag برای کش مشروط.

### ✅ Checklist

- [ ] مدل خطای یکسان + 422 برای ورودی
- [ ] 429 با Retry-After
- [ ] OpenAPI با ApexDocs همگام کد

### ❌ Anti-patterns
- پیام خطای متفاوت در هر endpoint

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
