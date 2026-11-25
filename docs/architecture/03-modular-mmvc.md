# Modular (MMVC) — هر ماژول یک اپ مینیاتوری

هر ماژول MVC کامل خودش را دارد (Controller/Model/View/ routes).
مرجع: **upMVC** (MMVC + AI Agent Pack داخلی)، **Ace Framework**، Trongate.
ساختار پیشنهادی: `modules/<name>/{controller,model,view,config}.php` — با
اتولودر `scaffolds/autoloader.php` که پیشوند `Modules\` را به این پوشه می‌نگارد.

### ✅ Checklist

- [ ] هر ماژول جداگانه تست و مستقر می‌شود
- [ ] وابستگی بین ماژول‌ها فقط از طریق رابط عمومی
- [ ] config هر ماژول مستقل است

### ❌ Anti-patterns
- ویرایش مستقیم فایل ماژول دیگر
- کپی‌شدن کد مشترک در چند ماژول به‌جای ماژول shared

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
