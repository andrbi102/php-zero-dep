# OPcache

پیکربندی حداقلی production: `opcache.enable=1`،
`opcache.validate_timestamps=0` (در production — ریست با deploy)،
`opcache.memory_consumption=128`، `opcache.max_accelerated_files=10000`.
اندازه‌گیری hit-rate با opcache_get_status() (OPcache Tuner T-037).
preloading فقط در FPM/CLI طولانی — نه در هاست اشتراکی.

### ✅ Checklist

- [ ] validate_timestamps=0 فقط در production با ریست deploy
- [ ] hit-rate پایش و در گزارش F6 ثبت می‌شود
- [ ] preloading با ADR

### ❌ Anti-patterns
- validate_timestamps=1 در production (perf ضربه)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
