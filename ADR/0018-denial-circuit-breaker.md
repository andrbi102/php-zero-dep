# ADR-0018: Denial Circuit Breaker

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Council Security (T-001-01)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
بدون یک circuit breaker، یک Worker که سه بار پی‌درپی توسط بازبین رد می‌شود،
می‌تواند چهارمین تلاش با همان ورودی را امتحان کند — حلقه‌ای که هیچ چیزی
یاد نمی‌گیرد و منابع را مصرف می‌کند. hermes-agent این را با
`DenialCircuitBreaker` (tools/approval.py: ۳ رد متوالی → hard-stop) حل کرده است.

## Decision (تصمیم)
هر مسیر/Worker ردهای متوالی را ردیابی می‌کند: **۳ رد متوالی → hard-stop**.
history-invariant: تاریخچهٔ رد در یک session برای سوءاستفاده در session
بعد قابل‌استفاده نیست. در ما، ۳ `request_changes` پشت‌سرهم → کارت `block` و
مشورت شورا اجباری (ثبت در `consultation/`). پیاده‌سازی در
`DenialCircuitBreaker` (`scaffolds/worker-orchestrator.php`).

## Consequences (پیامدها)
+ جلوی حلقه‌های بی‌پایان retry با همان ورودی.
+ وادار به مشورت شورا پس از شکست مکرر (نه تلاش چهارم).
+ history-invariant: جلوی سوءاستفاده از تاریخچه.
− در موارد نادر ممکن است یک تلاش چهارم مشروع را مسدود کند؛ راه خروج =
  مشورت شورا و reset در session جدید.
− فقط در سطح Worker track می‌شود، نه cross-session ( sandbox ریست).
