# Worker Contract — Release Manager (T-005)

> نسخه مهارت: 0.0.2 | قالب: scaffolds/workers/_template.md | سطح ۰ — شورا

## 1. Identity
- role_id: **T-005** — Release Manager / مدیر انتشار
- level: 0 (سطح ۰ — شورا)
- worker_id: `W-<run>-<task_id>`
- reports_to: — (سطح ۰؛ رئیت جلسات شورا)

## 2. Task (Atomic) — 8 وظیفه
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| T-005-01 | تعیین نسخه | F9 | VERSION | SemVer منطقی |
| T-005-02 | نوشتن CHANGELOG | F9 | CHANGELOG.md | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-03 | آماده‌سازی RELEASE.md | F9 | RELEASE.md | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-04 | تأیید بودجه انتشار | F9 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-05 | اجرای انتشار | F10 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-06 | ایجاد Git tag | F10 | Git tag | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-07 | اعلام انتشار | F10 | سند/کد ثبت‌شده در مخزن مهارت | خروجی تولید و توسط مقام بازبینی تأیید شده |
| T-005-08 | آرشیو نسخه | F10 | VERSION | خروجی تولید و توسط مقام بازبینی تأیید شده |

## 3. Authority (مجوزها)
`read-all`, `write-release`, `approve-release`, `exec-release`

## 4. Relationships
- can_consult: همه نقش‌های هم‌فاز (ثبت در consultation/ طبق C1..C7)
- must_consult: Council Security — برای هر موضوع با severity>=high (SLA ۴ ساعت)

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

- **delegate:** مجاز — می‌تواند Worker فرزند با scope محدودتر spawn کند (toolsets فرزند همواره زیرمجموعهٔ toolsets والد).
- **kanban:** claim روی کارت‌های وظیفهٔ این نقش، heartbeat پیش از هر خروجی، `request_review` برای تأیید بازبینی.
- **review:** بازبینی خروجی توسط بازبین مستقل (SoD-5: نویسنده ≠ بازبین) پیش از پذیرش.
- **moa:** برای تصمیمات پیچیدهٔ چندمنظری، مشورت ترکیبی چندمشاوره (تجمیع، نه رأی‌گیری).
- **estop:** تنها T-000/T-001 استاپ سراسری می‌زنند؛ این نقش تابع است.
