# قالب قرارداد Worker

> نسخه مهارت: **0.0.2** | نسخه کامل با راهنمای پرکردن + نمونه تکمیل‌شده: scaffolds/workers/_template.md

```markdown
# Worker Contract — {ROLE_EN} ({ROLE_ID})
## 1. Identity      — role_id / worker_id / reports_to
## 2. Task (Atomic) — جدول: Task ID | وظیفه | فاز | Output | DoD
## 3. Authority     — مجوزها (از ۴۲ مجوز؛ SoD رعایت شود)
## 4. Relationships — can_consult / must_consult
## 5. Budget        — ۱ وظیفه هم‌زمان
## 6. DoD           — معیار پذیرش هر وظیفه
## 7. Escalation    — مسیر ارجاع تعارض
## 8. Lifecycle     — ۸ گام (Requirement → ... → Retrospective)
```

## داده هر بخش از کجا می‌آید؟

| بخش | منبع |
|------|------|
| 1. Identity | `RULES.json` → org_model.roles (شناسه، سطح) |
| 2. Task (Atomic) | قرارداد رسمی نقش در scaffolds/workers/T-NNN-*.md یا کارت نقش در atomic-tasks/by-role/T-NNN.md |
| 3. Authority | `RULES.json` (مجوزهای همان نقش) + بررسی SoD (`11-sod.md`) |
| 4. Relationships | قرارداد رسمی نقش + قاعده مشورت امنیتی (severity>=high → T-001) |
| 5. Budget | ثابت: ۱ وظیفه هم‌زمان (قاعده ۲ AGENTS.md) |
| 6. DoD | جدول وظایف + مقام بازبینی مربوط |
| 7. Escalation | الگوی واحد: تعارض → N-1 | امنیتی → T-001 |
| 8. Lifecycle | هشت گام — راهنما و نمونه: `docs/org/08-lifecycle.md` |

نمونه تکمیل‌شده (Release Manager — T-005): انتهای `scaffolds/workers/_template.md`
