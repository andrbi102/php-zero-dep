# کاربرد: CMS

مرجع بدون Composer: **Chyrp Lite** (وبلاگ/CMS سبک). ساختار:
محتوا در دیتابیس + قالب‌بندی View با escape؛ ویرایشگر متن با
sanitizer سمت سرور (لیست سفید تگ)؛ نقش‌ها RBAC (ادمین/نویسنده/بازدیدکننده)؛
بازسازی صفحه با کش. آپلود رسانه طبق security/06.

### ✅ Checklist

- [ ] sanitizer لیست سفید برای HTML کاربر
- [ ] RBAC با نقش‌های صریح
- [ ] پیش‌نمایش با داده escape شده

### ❌ Anti-patterns
- ذخیره HTML خام کاربر بدون sanitize

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
