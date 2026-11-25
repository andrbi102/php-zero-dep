# Podman — کانتینر rootless

جایگزین Docker در VPS بدون دسترسی root: rootless، بدون daemon،
سازگار با OCI. همان Dockerfile مهارت کار می‌کند (بخش deployment/03)
با فرمان‌های podman build/run. systemd user unit برای اجرای پایدار.

### ✅ Checklist

- [ ] اجرای rootless (بدون daemon)
- [ ] podman generate systemd برای سرویس
- [ ] حجم‌ها با :Z برای SELinux

### ❌ Anti-patterns
- فرض docker.sock (وجود ندارد)
- اجرای container با --privileged

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
