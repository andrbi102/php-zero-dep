# RoadRunner

سرور اپلیکیشن Go با Workerهای long-lived PHP — باینری rr مستقل از
Composer. کاربرد: API پرترافیک با نگه‌داشتن state در حافظه worker.
الگوی سازگار با این مهارت: PSR-7-like request/response یا bridge سبک —
کد اپ همان می‌ماند، فقط بوت‌استرپ فرق می‌کند (اپ در حلقه worker اجرا
می‌شود، پس هر state سراسری بین requestها ریست شود).

### ✅ Checklist

- [ ] باینری rr روی سرور موجود است (بخش ۱۲ قید ۱۳)
- [ ] state between requests صریح ریست می‌شود
- [ ] worker restart پس از deploy

### ❌ Anti-patterns
- session/files بین چند worker بدون قفل
- فرض اجرای یک‌باره اسکریپت در worker mode

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
