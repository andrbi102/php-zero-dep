# Context7 MCP

ابزارها (ترتیب اجباری): `resolve-library-id` ← سپس `query-docs`
با پرسش مشخص و زبان PHP؛ نسخه‌محور (مستندات همان نسخه).
Local (نیازمند Node.js): `npx -y @upstash/context7-mcp` — روش پیشنهادی
جدید: `npx ctx7 setup`.
Remote (بدون Node.js): `https://mcp.context7.com/mcp` با هدر
`Authorization: Bearer <API_KEY>` — کلید رایگان از context7.com/dashboard؛
بدون کلید، محدودیت نرخ شدید دارد.

### ✅ Checklist

- [ ] اول resolve-library-id مگر شناسه /owner/repo داده شده
- [ ] query-docs با نسخه صریح
- [ ] در نبود Context7: اعلام محدودیت، نه حدس API

### ❌ Anti-patterns
- تولید کد API کتابخانه بدون مشورت

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
