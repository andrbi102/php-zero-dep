# سرور MCP با PHP خالص

دو راه: ۱) `scaffolds/mcp-server.php` (JSON-RPC روی stdio؛ هر
Tool یک فایل در tools/ با name/description/input/execute و اعتبارسنجی
ورودی — نمونه: scaffolds/tools/hello.php)؛ ۲) **VibeShell**
(github.com/d3cline/VibeShell — تک‌فایلی PHP 7.4+، محافظت path
traversal، GPL-3.0). توجه لایسنس: GPL برای ابزار dev مشکلی ندارد،
کپی کد آن در خروجی MIT مجاز نیست.

### ✅ Checklist

- [ ] هر Tool اعتبارسنجی ورودی دارد
- [ ] اجرای سرور در فرایند جدا
- [ ] لایسنس سرور آماده بررسی شده

### ❌ Anti-patterns
- Tool بدون اعتبارسنجی args

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
