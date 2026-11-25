# MVC — Model / View / Controller

جداسازی داده (Model)، نمایش (View) و کنترل جریان (Controller). مرجع Zero-Dep:
**Trongate** (PHP 8، صفر Composer) و **MicroMVC** (تک‌فایلی).
در این مهارت: `scaffolds/router.php` نقش ورودی Controller را می‌گیرد و
`scaffolds/view.php` با escape خودکار نقش View را؛ Model لایه‌ای است که
فقط از طریق `scaffolds/database.php` با PDO حرف می‌زند.

قاعده کلیدی: View هیچ‌گاه کوئری نمی‌زند و Controller هیچ HTML خام تولید نمی‌کند.

### ✅ Checklist

- [ ] View فقط با View::e() داده متغیر را چاپ می‌کند
- [ ] Controller نازک است؛ منطق در Model/Service
- [ ] مسیرها فقط از Router عبور می‌کنند

### ❌ Anti-patterns
- کوئری SQL داخل فایل View
- echo مستقیم داده کاربر در Controller

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
