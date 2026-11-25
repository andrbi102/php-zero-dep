# مهندسی معکوس API

استخراج قرارداد API از ترافیک واقعی با **reverse-api-engineer**
(kalil0321) و کشف endpoint با **Noir** (OWASP — SAST در ۳۲ زبان).
**php-route-mapper** (0xShe/PHP-Code-Audit-Skill) روت‌ها را از سورس PHP
استخراج می‌کند. ترتیب کار: روت‌ها و پارامترها مستند شوند ← سپس کلاینت
با `scaffolds/http-client.php` نوشته شود.

### ✅ Checklist

- [ ] ابتدا مستندسازی endpoint، سپس کلاینت
- [ ] اعتبارسنجی پاسخ‌ها با Validator
- [ ] نرخ فراخوانی با RateLimiter محدود شود

### ❌ Anti-patterns
- کلاینت بازسازی‌شده بدون timeout/retry
- فرض پایداری قرارداد API مبدأ

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
