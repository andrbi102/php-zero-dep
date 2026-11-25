# خروجی استاندارد GraphML و Cytoscape

GraphML (XML با namespace yEd) برای ابزارهای دسکتاپ؛ Cytoscape JSON
(elements: nodes/edges) برای وب. تولید هر دو با ext-simplexml/ext-json —
بدون کتابخانه رسم‌گر. نام گره‌ها id پایدار؛ برچسب جدا از id.

### ✅ Checklist

- [ ] id پایدار + label جدا
- [ ] اعتبارسنجی خروجی با بازخوانی
- [ ] نسخه فرمت در ریشه سند

### ❌ Anti-patterns
- HTML دست‌ساز به‌عنوان «فرمت گراف»

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
