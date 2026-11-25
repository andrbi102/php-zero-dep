# دارایی‌های استاتیک

نسخه‌گذاری در نام فایل (app.v7.css) برای کش مرورگر جاودانه؛
minify دستی/اختیاری (بدون webpack)؛ تصاویر با width/height صریح
(جلوگیری از layout shift) و loading="lazy" برای پایین صفحه؛
بودجه صفحه (وزن/درخواست) در ADR frontend تعیین و پایش شود.

### ✅ Checklist

- [ ] نسخه در نام فایل + cache-control طولانی
- [ ] ابعاد صریح تصاویر
- [ ]  بودجه دارایی تعریف و پایش

### ❌ Anti-patterns
- query string نسخه (?v=) با cache استاندارد مرورگر

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
