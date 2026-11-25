# پروفایلینگ

ابزار Zero-Dep: **PerformanceMeter** (InitPHP — تک‌فایلی) یا
پروفایلر دستی با hrtime(true) در middleware. Xdebug فقط dev
(و روی FrankenPHP musl نمی‌آید). روش: پروفایل مسیر داغ ← تغییر واحد ←
مقایسه قبل/بعد (Profiling Specialist T-038) — عدد بدون baseline بهینه‌سازی نیست.

### ✅ Checklist

- [ ] هر بهینه‌سازی با عدد قبل/بعد
- [ ] پروفایل در محیط شبیه production
- [ ] ابزار تک‌فایلی یا دستی — بدون Composer

### ❌ Anti-patterns
- بهینه‌سازی حدس‌محور بدون پروفایل

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
