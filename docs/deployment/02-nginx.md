# استقرار با Nginx

Nginx `.htaccess` را پشتیبانی نمی‌کند و الزاماً PHP-FPM جداگانه لازم است:

```nginx
location / { try_files $uri $uri/ /index.php$is_args$args; }
location ~ \.php$ {
    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}
```

### ✅ Checklist

- [ ] try_files به index.php
- [ ] fastcgi_param کامل
- [ ] حظر دسترسی مستقیم به فایل‌های غیرعمومی

### ❌ Anti-patterns
- انتظار به‌کارگیری .htaccess در Nginx
- اجازه اجرای php در پوشه uploads

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
