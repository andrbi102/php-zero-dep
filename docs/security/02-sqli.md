# SQL Injection — صفر گذشته، همیشه Prepared

همه کوئری‌ها از `scaffolds/database.php` با پارامتر:
`$db->query('SELECT ... WHERE id = ?', [$id])`.
قواعد: ۱) هرگز الحاق رشته در SQL؛ ۲) `PDO::ATTR_EMULATE_PREPARES => false`؛
۳) ORDER BY/LIMIT متغیر با لیست سفید (int cast یا in_array)؛
۴) LIKE با escape خودکار pdo (پارامتر bind).

### ✅ Checklist

- [ ] ATTR_EMULATE_PREPARES = false
- [ ] نام ستون/جدول متغیر فقط از لیست سفید
- [ ] تست هر کوئری با ورودی مخرب در F6

### ❌ Anti-patterns
- «این ورودی امن است» بدون Validator
- debug echo از کوئری نهایی با پارامترها

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
