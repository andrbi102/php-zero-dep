# ADR-0008: پشتیبانی از معماری Onion

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Chief Architect (T-010-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
پروژه‌های تولیدشده توسط مهارت ممکن است به الگوی **Onion** نیاز داشته باشند.
ایده اصلی: لایه‌های متحدالمرکز، وابستگی به داخل. مرجع Zero-Dep: final class + interface.

## Decision (تصمیم)
اسکلت‌ها و راهنمای این معماری را در `docs/architecture/` نگه می‌داریم و
پیاده‌سازی را از scaffolds خود مهارت برمی‌داریم. نکته اجباری ایجنت: با Hexagonal هم‌پوشانی دارد.

## Consequences (پیامدها)
+ انتخاب آگاهانه با راهنمای `docs/architecture/16-selection-guide.md`
+ بدون وابستگی جدید — الگو دست‌نویس یا از scaffolds
− برای الگوهای خاص (ES/CQRS) پیچیدگی بیشتر که فقط با نیاز واقعی توجیه می‌شود
