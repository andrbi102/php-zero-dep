# PDO Wrapper — scaffolds/database.php

قابلیت‌ها: prepared اجباری، تشخیص dialect (isSqlite/isMysql/isPgsql)،
تراکنش با rollback خودکار، fetch پیش‌فرض assoc.
قاعده Dialect: `JSONB` فقط PostgreSQL؛ در SQLite نوع JSON یعنی TEXT —
کد باید با `Database::isPgsql()` شاخه بزند (قاعده بخش ۸ SPEC).

### ✅ Checklist

- [ ] همه SQL از این wrapper عبور می‌کند
- [ ] هر متد خطرناک (exec خام) در ADR مستند
- [ ] اتصال singleton در Container

### ❌ Anti-patterns
- new PDO پراکنده در کد
- الحاق مقدار در SQL حتی «مطمئن»

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
