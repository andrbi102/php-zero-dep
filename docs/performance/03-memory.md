# مدیریت حافظه

اندازه‌گیری: memory_get_usage(true) قبل/بعد؛ پروفایل Memory
Optimizer (T-039). الگوها: generator برای مجموعه بزرگ (yield به‌جای
array کامل)؛ unset صریح داده سنگین در حلقه؛ بدون کپی آرایه بزرگ در
پارامتر (pass by reference فقط با توجیه). مرجع خوداظهاری عملکرد:
NexusPHP (<2.0MB memory).

### ✅ Checklist

- [ ] generator برای مجموعه بزرگ
- [ ] اندازه‌گیری قبل از بهینه‌سازی
- [ ] حداکثر حافظه هاست اشتراکی لحاظ شود

### ❌ Anti-patterns
- array_map روی میلیون سطر به‌جای generator
- نگه‌داشتن کل resultset برای شمارش

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
