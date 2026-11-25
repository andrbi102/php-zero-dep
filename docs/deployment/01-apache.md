# استقرار با Apache

DocumentRoot به public/؛ همه درخواست‌ها به index.php با mod_rewrite.
در هاست اشتراکی `.htaccess` تنها اهرم است:

```apacheconf
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

هدرهای امنیتی نیز در .htaccess (Header always set) قابل تنظیم‌اند.

### ✅ Checklist

- [ ] DocumentRoot = public/ (کد بیرون از root وب)
- [ ] .htaccess برای rewrite و هدرها
- [ ] AllowOverride در vhost فعال

### ❌ Anti-patterns
- قرار دادن index.php در ریشه کنار کد

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
