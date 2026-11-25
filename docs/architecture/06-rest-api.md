# REST API — منابع + متدهای HTTP + JSON

نسخه‌بندی از روز اول با پیشوند `/v1/`؛ خطاها همیشه با مدل JSON
یکسان و کد وضعیت صحیح (نه `200 OK` برای خطا).
ابزار مستندسازی: **ApexDocs** (anil/apexdocs — OpenAPI 3.1، وابستگی صفر در هسته)
و **Doctum**. برای مسیرها `scaffolds/router.php` + یک لایه thin controller
که خروجی را `json_encode` می‌کند (JSON_UNESCAPED_UNICODE).

### ✅ Checklist

- [ ] همه خطاها مدل JSON یکسان دارند (code/message/details)
- [ ] کد وضعیت HTTP با معنای واقعی (404/422/429/500)
- [ ] نسخه در URL: /v1/
- [ ] Pagination با cursor برای مجموعه‌های بزرگ

### ❌ Anti-patterns
- 200 OK با body خطا
- افشای stack trace در پاسخ خطا

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
