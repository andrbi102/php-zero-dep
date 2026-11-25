# ADR-0015: مدل ارکستراسیون Worker (الگوبرداری از hermes-agent)

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Chief Architect (T-010-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
مدل سازمانی v2 نقش‌ها (۷۹) و وظایف اتمیک (۳۷۲) را تعریف می‌کرد، اما
*فعل‌های ارکستراسیون* (واگذاری، هدایت میان‌اجرا، بازبینی مستقل، توقف
اضطراری) وجود نداشتند. بدون آن‌ها، هماهنگی بین Workerها در پروژه‌های full
به ad-hoc و غیرقابل‌ممیزی تبدیل می‌شد.

## Decision (تصمیم)
پنج بدیههٔ ارکستراسیون از NousResearch/hermes-agent (MIT) الگوبرداری و در
PHP خالص پیاده می‌شوند (`scaffolds/worker-orchestrator.php`):
`delegate {spawn,list,steer,stop,continue}`، `kanban {claim,…,complete}`،
`review` (بازبین مستقل)، `moa` (Mixture-of-Agents)، `estop` (sentinel
fail-safe). این فعل‌ها به‌عنوان بخش ۹ قالب `_template.md` در قرارداد هر
Worker می‌نشینند — **بدون افزودن نقش جدید** (شمارش ۷۹/۳۷۲/۴۲ دست‌نخورده).

## Consequences (پیامدها)
+ هماهنگی Workerها قابل‌ممیزی و دارای state machine صریح است.
+ هیچ نقش جدیدی اضافه نشد → `verify_org.py` سبز می‌ماند.
+ الگوی قابل‌تکرار برای پروژه‌های full.
− پیچیدگی بیشتر قرارداد Worker (۸ بخش + بخش ۹)؛ در solo پروفایل، بخش ۹
  اغلب استفاده نمی‌شود.
− پیاده‌سازی در حافظه است (state پایدار نداریم — sandbox ریست)؛ محدودیت
  مستند در `docs/org/12-worker-orchestration.md` بخش ۸.
