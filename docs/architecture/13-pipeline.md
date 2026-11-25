# Pipeline / Middleware — زنجیره قابل ترکیب

آسان‌ترین الگو برای دست‌نویس: هر stage یک callable است که payload و
next را می‌گیرد. `scaffolds/pipeline.php` با `callable` خالص زیر ۳۰ خط.
مرجع: onion middleware در NexusPHP. کاربردها: هدرهای امنیتی، لاگ،
اعتبارسنجی، rate limit به‌صورت middleware در `scaffolds/router.php`.

### ✅ Checklist

- [ ] Stageها بی‌حالت‌اند
- [ ] ترتیب stages مستند و تست‌شده
- [ ] هر stage خطای خودش را با context رد می‌کند

### ❌ Anti-patterns
- Stage که next را صدا نمی‌زند بدون دلیل مستند
- منطق کسب‌وکار داخل middleware

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
