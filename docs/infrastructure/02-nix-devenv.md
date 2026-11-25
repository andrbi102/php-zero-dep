# Nix / devenv — فقط توسعه

محیط توسعه قابل بازتولید: نسخه دقیق PHP و افزونه‌ها در
flake/devenv.nix قفل می‌شود — «روی سیستم من کار می‌کرد» حذف می‌شود.
فقط dev است (قید ۱۵ بخش ۱۲): محیط production را Docker/Podman یا
باینری استاتیک تشکیل می‌دهد، نه NixOS اجباری.

### ✅ Checklist

- [ ] devenv.nix در repo با نسخه‌های قفل‌شده
- [ ] دستور direnv allow مستند
- [ ] production مستقل از Nix

### ❌ Anti-patterns
- استفاده Nix در production بدون اپراتور NixOS

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
