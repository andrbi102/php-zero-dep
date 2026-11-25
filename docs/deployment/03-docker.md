# Docker بدون Composer

Dockerfile نمونه — بدون `composer install`:

```dockerfile
FROM php:8.3-cli-alpine
COPY . /app
WORKDIR /app
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
```

برای production از فرانک‌ان‌جی‌ان (FrankenPHP) یا FPM+FPM supervisor
استفاده شود؛ حجم image با opcache و بدون dev فایل‌ها کم نگه داشته شود.

### ✅ Checklist

- [ ] هیچ لایه‌ای composer ندارد
- [ ] کد فقط‌خواندنی (read-only) در image
- [ ] پورت و env از ARG/ENV

### ❌ Anti-patterns
- COPY vendor/ (وجود ندارد!)
- اجرای root بدون نیاز

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
