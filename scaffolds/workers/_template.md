# Worker Contract — {ROLE_EN} ({ROLE_ID})

> قالب قرارداد استخدام Worker — نسخه مهارت 0.0.1 | ۸ بخش الزامی (SPEC بخش ۱۶٫۹)

## 1. Identity
- role_id: {ROLE_ID} | level: {LEVEL}
- worker_id: W-{run}-{task_id}
- reports_to: {REPORTS_TO}

## 2. Task (Atomic)
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| {TASK_ID} | {TITLE} | {PHASE} | {OUTPUT} | {DOD} |

## 3. Authority
- مجوزها: {PERMISSIONS}

## 4. Relationships
- can_consult: {CAN_CONSULT}
- must_consult: {MUST_CONSULT} (مشورت امنیتی برای severity>=high اجباری)

## 5. Budget
- هم‌زمان فقط ۱ وظیفه اتمیک (یک نقش = یک Worker، استخدام به‌ازای وظیفه)

## 6. DoD
- خروجی مطابق جدول بخش ۲ + تأیید مقام بازبینی مربوط

## 7. Escalation
- تعارض → سطح N-1 | امنیتی critical/high → Council Security (SLA ۴ ساعت)

## 8. Lifecycle
- Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective
