# دیباگ

ابزارهای dev مجاز: **vardumper** (michelphp — dump/dd/btrace،
Zero-dependency)، **ddless-engine** (دیباگر AST-based)، **filo**
(giacomomasseron — call tracer بدون افزونه)، Xdebug (فقط dev — روی
FrankenPHP musl نمی‌آید). dd فقط dev: خروجی تولیدی هرگز dump ندارد.

### ✅ Checklist

- [ ] دسترسی ابزار دیباگ به production صفر
- [ ] هر dd موقت پیش از merge حذف می‌شود

### ❌ Anti-patterns
- print_r در خروجی production
- رها کردن dd در مسیر API

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
