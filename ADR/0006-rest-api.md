# ADR-0006: پشتیبانی از معماری REST API

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Chief Architect (T-010-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
پروژه‌های تولیدشده توسط مهارت ممکن است به الگوی **REST API** نیاز داشته باشند.
ایده اصلی: منابع + متدهای HTTP + JSON. مرجع Zero-Dep: Restler، Flight.

## Decision (تصمیم)
اسکلت‌ها و راهنمای این معماری را در `docs/architecture/` نگه می‌داریم و
پیاده‌سازی را از scaffolds خود مهارت برمی‌داریم. نکته اجباری ایجنت: نسخه‌بندی /v1/، کد وضعیت صحیح.

## Consequences (پیامدها)
+ انتخاب آگاهانه با راهنمای `docs/architecture/16-selection-guide.md`
+ بدون وابستگی جدید — الگو دست‌نویس یا از scaffolds
− برای الگوهای خاص (ES/CQRS) پیچیدگی بیشتر که فقط با نیاز واقعی توجیه می‌شود
