# ویژگی‌های مدرن SQL

JSON columns (PG: JSONB / MySQL: JSON / SQLite: TEXT+json1)،
Generated columns، CTE (WITH RECURSIVE)، Window Functions،
UPSERT (ON CONFLICT / ON DUPLICATE KEY)، RETURNING (PG، SQLite 3.35+).
ایجنت باید Dialect را تشخیص دهد و فقط ویژگی پشتیبانی‌شده همان موتور را
به کار ببرد (پیش از ۳.۳۵ RETURNING در SQLite نیست).

### ✅ Checklist

- [ ] feature-detection قبل از استفاده
- [ ] fallback برای dialect بدون ویژگی
- [ ] تست ویژگی روی هر سه dialect پشتیبانی‌شده

### ❌ Anti-patterns
- RETURNING در MySQL (پشتیبانی نمی‌شود)
- JSONB در SQLite

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
