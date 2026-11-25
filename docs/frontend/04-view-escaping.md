# escape در View

قاعده طلایی: هر چاپ متغیر از View با `View::e()` — در attribute هم
(ENT_QUOTES). context های خاص: URL با urlencode، JS با json_encode،
CSS بدون مقدار متغیر. Escape لحظه خروجی است نه ورودی (تا داده خام
برای منطق دیگر باقی بماند).

### ✅ Checklist

- [ ] e() در هر چاپ متغیر، حتی «مطمئن»
- [ ] context درست برای URL/JS
- [ ] تست XSS با payload استاندارد در F6

### ❌ Anti-patterns
- «داده از دیتابیس است، امن است»

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
