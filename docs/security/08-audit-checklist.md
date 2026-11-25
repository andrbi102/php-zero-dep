# چک‌لیست ممیزی امنیتی (فاز F7)

ممیاز مستقل: Security Auditor (T-075) + بازبینی Security Engineer
(T-011-02..09) + تأیید نهایی Council Security (T-001) با veto مطلق.
موارد: نبود composer/vendor؛ strict_types همه فایل‌ها؛ Prepared همه
کوئری‌ها؛ escape همه خروجی‌ها؛ CSRF همه فرم‌ها؛ Argon2id؛ کوکی امن؛
هدرهای ۵گانه؛ بدون توابع ممنوع (بخش ۱۵)؛ کد vendored با لایسنس ثبت‌شده.
ابزار خودکار: `php tests/security-audit.php`

### ✅ Checklist

- [ ] خروجی ممیزی = security-signoff.md امضاشده
- [ ] هر یافته با severity و اصلاح ردیابی می‌شود
- [ ] اجرا: php tests/security-audit.php (خروجی 0)

### ❌ Anti-patterns
- امضای security-signoff با یافته باز critical/high
- ممیزی فقط یک‌بار در ابتدای پروژه

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
