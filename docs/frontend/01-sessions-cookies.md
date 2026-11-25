# نشست و کوکی در فرانت‌اند

کوکی نشست: HttpOnly + Secure + SameSite=Strict (scaffolds/session.php).
داده کوکی اضافی: فقط شناسه/پرچم، هرگز داده حساس؛ در صورت نیاز
رمزنگاری‌شده (php-storageless-sessions — کوکی HMAC). تست: ارسال
دو request پشت سر هم با cookie jar (curl -b/-c).

### ✅ Checklist

- [ ] همه کوکیها با پرچم‌های امن
- [ ] حجم کوکی زیر 4KB
- [ ] دامنه/مسیر کوکی صریح

### ❌ Anti-patterns
- ذخیره توکن دسترسی در localStorage

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
