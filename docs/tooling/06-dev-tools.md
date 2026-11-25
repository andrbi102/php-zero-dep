# فهرست ابزارهای dev مجاز

PHPStan، PHP_CodeSniffer، PHPUnit، PHPBench، Xdebug، Playwright MCP،
mago (carthage-software)، Phan — همه فقط dev و ترجیحاً phar تک‌فایلی.
ابزارهای تحلیل: **PhpCodeArcheology** (معماری + dependency graph + MCP).
هیچ‌کدام در require خروجی نمی‌آیند؛ مستندات هر ابزار در همین پوشه.

### ✅ Checklist

- [ ] ابزار dev با phar مستقل
- [ ] فهرست ابزار در RULES.json (allowed_dev_tools)
- [ ] بازبینی سه‌ماهه همراه کاتالوگ

### ❌ Anti-patterns
- ابزار dev در Dockerfile production

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
