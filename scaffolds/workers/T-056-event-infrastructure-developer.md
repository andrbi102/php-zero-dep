# Worker Contract — Event Infrastructure Developer (T-056)

> نسخه مهارت: 0.0.2 | قالب: scaffolds/workers/_template.md | سطح ۳ — مجریان

## 1. Identity
- role_id: **T-056** — Event Infrastructure Developer / توسعه‌دهنده زیرساخت رویداد
- level: 3 (سطح ۳ — مجریان)
- worker_id: `W-<run>-<task_id>`
- reports_to: متخصص حوزه

## 2. Task (Atomic) — 4 وظیفه
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| T-056-01 | پیاده‌سازی event-dispatcher.php | F4 | scaffolds/event-dispatcher.php | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-056-02 | پیاده‌سازی event-store.php | F4 | scaffolds/event-store.php | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-056-03 | پیاده‌سازی command-bus.php | F4 | scaffolds/command-bus.php | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-056-04 | پیاده‌سازی query-bus.php | F4 | scaffolds/query-bus.php | خروجی تولید و توسط مقام بازبینی تأیید شده |

## 3. Authority (مجوزها)
`read-architecture`, `write-core`

## 4. Relationships
- can_consult: همه نقش‌های هم‌فاز (ثبت در consultation/ طبق C1..C7)
- must_consult: متخصص حوزه در صورت خروج از scope

## 5. Budget
- فقط ۱ وظیفه اتمیک هم‌زمان؛ اتمام = انحلال Worker (قاعده ۲ AGENTS.md)

## 6. DoD
- هر وظیفه: خروجی طبق جدول بخش ۲ + تأیید بازبینی مربوط (Code Reviewer T-074 / شورا)

## 7. Escalation
- بن‌بست فنی → سطح N-1 | وتوی امنیتی → غیرقابل نقض (T-001)

## 8. Lifecycle
- Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective

## 9. Hermes-derived orchestration primitives

> منبع الگو: [`docs/references/01-hermes-agent.md`](../../docs/references/01-hermes-agent.md) | [`docs/org/12-worker-orchestration.md`](../../docs/org/12-worker-orchestration.md) | پیاده‌سازی: [`scaffolds/worker-orchestrator.php`](../worker-orchestrator.php)

- **delegate:** مسدود (نقش برگ‌ی) — این نقش فرزند استخدام نمی‌کند؛ خودش مجری مستقیم وظایف اتمیک است.
- **kanban:** claim روی کارت‌های وظیفهٔ این نقش، heartbeat پیش از هر خروجی، `request_review` برای تأیید بازبینی.
- **review:** بازبینی خروجی توسط بازبین مستقل (SoD-5: نویسنده ≠ بازبین) پیش از پذیرش.
- **moa:** برای تصمیمات پیچیدهٔ چندمنظری، مشورت ترکیبی چندمشاوره (تجمیع، نه رأی‌گیری).
- **estop:** تنها T-000/T-001 استاپ سراسری می‌زنند؛ این نقش تابع است.
