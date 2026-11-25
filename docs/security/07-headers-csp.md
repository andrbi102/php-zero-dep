# هدرهای امنیتی HTTP و CSP

پنج هدر الزامی (قید ۲۰ پرامپت اصلی):
`Content-Security-Policy: default-src 'self'` (سختگیرانه، سپس شل‌تر),
`Strict-Transport-Security: max-age=31536000; includeSubDomains`,
`X-Content-Type-Options: nosniff`، `X-Frame-Options: DENY`،
`Referrer-Policy: strict-origin-when-cross-origin`. ارسال: middleware در
`scaffolds/router.php` یا `header()` در بوت‌استرپ قبل از هر خروجی.

### ✅ Checklist

- [ ] هر ۵ هدر در پاسخ همه صفحات
- [ ] CSP با report-only تست و سپس اعمال
- [ ] هدرها قبل از اولین echo

### ❌ Anti-patterns
- CSP با unsafe-inline دائمی
- حذف هدر برای «رفع مشکل» بدون ADR

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
