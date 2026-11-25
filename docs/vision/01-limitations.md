# محدودیت‌های مستند (۱۶ مورد بخش ۱۲)

۱) CV با PHP خالص عملی نیست (php-opencv خارج از Zero-Ext) —
۲) Playwright PHP نیازمند Composer؛ MCP سازگارتر — ۳) sodium_compat
فقط vendored — ۴) گراف دستی — ۵) ویژگی DB به نسخه وابسته — ۶) Nginx
بدون FPM نه — ۷) هاست اشتراکی بدون CLI — ۸) REA مجوز — ۹) serverless
بدون FS پایدار — ۱۰) Actor واقعی نه؛ Queue+Worker — ۱۱) MCP: ابزار
توسعه نه وابستگی runtime — ۱۲) FrankenPHP musl بدون Xdebug —
۱۳) RoadRunner نیاز به باینری rr — ۱۴) Podman روی هاست اشتراکی نیست —
۱۵) Nix فقط dev — ۱۶) OpenTelemetry Zero-Code نه؛ Manual Tracing.

### ✅ Checklist

- [ ] هر محدودیت در README محصول ذکر می‌شود
- [ ] راه‌حل جایگزین برای هر مورد موجود است

### ❌ Anti-patterns
- پنهان کردن محدودیت از کاربر نهایی

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
