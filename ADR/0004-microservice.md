# ADR-0004: پشتیبانی از معماری Microservice

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Chief Architect (T-010-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
پروژه‌های تولیدشده توسط مهارت ممکن است به الگوی **Microservice** نیاز داشته باشند.
ایده اصلی: سرویس‌های کوچک مستقل. مرجع Zero-Dep: webrium/core.

## Decision (تصمیم)
اسکلت‌ها و راهنمای این معماری را در `docs/architecture/` نگه می‌داریم و
پیاده‌سازی را از scaffolds خود مهارت برمی‌داریم. نکته اجباری ایجنت: هر سرویس stateless باشد.

## Consequences (پیامدها)
+ انتخاب آگاهانه با راهنمای `docs/architecture/16-selection-guide.md`
+ بدون وابستگی جدید — الگو دست‌نویس یا از scaffolds
− برای الگوهای خاص (ES/CQRS) پیچیدگی بیشتر که فقط با نیاز واقعی توجیه می‌شود
