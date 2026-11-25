# سیاست کد vendored

کد کپی‌شده (مانند sodium_compat — ISC) فقط با شرایط زیر:
۱) لایسنس سازگار با MIT خروجی (ISC/BSD/MIT بله؛ GPL-3.0 خیر)
۲) حفظ اعلان کپی‌رایت نویسنده در فایل
۳) ثبت در ADR + بازبینی Council Compliance (T-004-01)
۴) بدون composer.json — کپی مستقیم فایل در پوشه مشخص با README منبع.
کاتالوگ مرجع: paragonie/sodium_compat (github.com/paragonie/sodium_compat).

### ✅ Checklist

- [ ] لایسنس کد vendored در هر بازبینی سه‌ماهه کنترل می‌شود
- [ ] فایل‌های vendored جدا از کد پروژه‌اند
- [ ] منبع + نسخه + تاریخ کپی ثبت شده

### ❌ Anti-patterns
- کپی کد با لایسنس Copyleft در خروجی MIT
- ویرایش کد vendored بدون ثبت diff

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
