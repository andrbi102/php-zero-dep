# امنیت MCP

سرور MCP دسترسی فایل‌سیستم دارد — محدود به مسیر مجاز (path
traversal گارد، الگوی VibeShell)؛ ورودی هر Tool با نوع/محدودیت بررسی
شود (InvalidArgumentException → خطای -32602)؛ کلید API هرگز در لاگ
(Leger REDACT)؛ remote فقط با HTTPS و Bearer از env؛ لاگ فراخوانی
ابزار برای حسابرسی.

### ✅ Checklist

- [ ] مسیر مجاز سرور صریح و بسته
- [ ] اعتبارسنجی ورودی هر Tool
- [ ] کلیدها از env نه هاردکد

### ❌ Anti-patterns
- سرور MCP با دسترسی کل فایل‌سیستم

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
