# امنیت نشست و کوکی

"scaffolds/session.php": کوکی HttpOnly + Secure + SameSite=Strict؛
session_regenerate_id(true) پس از login (زیربخش `SecureSession::regenerate`);
timeout سمت سرور (آخرین فعالیت در $_SESSION). گزینه بدون ذخیره‌سازی:
php-storageless-sessions (کوکی HMAC + رمزنگاری — در کاتالوگ امنیت).

### ✅ Checklist

- [ ] regenerate پس از هر تغییر سطح دسترسی
- [ ] مقایسه user agent اختیاری اما مستند
- [ ] destroy کامل در logout ($_SESSION = [])

### ❌ Anti-patterns
- session fixation با پذیرش id از query
- داده حساس در کوکی بدون رمزنگاری

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
