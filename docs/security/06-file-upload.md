# امنیت آپلود فایل

اعتبارسنجی: MIME با ext-fileinfo (نه پسوند)، حجم سقف، لیست سفید
پسوند. ذخیره: خارج از webroot (یا .htaccess deny)، نام تصادفی
(bin2hex(random_bytes(16)))، ایندکس لینک در دیتابیس. اسکن اختیاری
ClamAV از طریق exec ممنوع — با HTTP API سرویس خارجی (HttpClient).

### ✅ Checklist

- [ ] mime از finfo، نه $_FILES['type']
- [ ] نام فایل بازتولید تصادفی
- [ ] خروجی از webroot یا deny مستقیم

### ❌ Anti-patterns
- اجازه اجرای PHP در پوشه آپلود
- تصمیم بر اساس پسوند فایل

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
