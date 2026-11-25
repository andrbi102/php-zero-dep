# Worker Contract — {ROLE_EN} ({ROLE_ID})

> قالب قرارداد استخدام Worker — نسخه مهارت 0.0.1 | ۸ بخش الزامی (SPEC بخش ۱۶٫۹)
>
> **راهنمای پر کردن:** هر بخش دقیقاً یک هدف دارد؛ هیچ بخشی را خالی نگذار.
> مجوزها فقط از فهرست ۴۲گانه `RULES.json`؛ شمارش وظایف باید با همان فایل
> بخواند. نمونه تکمیل‌شده واقعی در انتهای همین فایل آمده است.

## 1. Identity
- role_id: {ROLE_ID} | level: {LEVEL}
- worker_id: W-{run}-{task_id}
- reports_to: {REPORTS_TO}

*چه می‌نویسم:* شناسه نقش از `RULES.json`؛ سطح از سلسله‌مراتب؛
worker_id یکتا در هر اجرا؛ reports_to نقش سطح بالاتر (سطح ۰: جلسات شورا).

## 2. Task (Atomic)
| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
| {TASK_ID} | {TITLE} | {PHASE} | {OUTPUT} | {DOD} |

*چه می‌نویسم:* هر ردیف = یک فعل قابل اتمام؛ خروجی، یک artifact مشخص؛
DoD معیاری است که بتوان با «بله/نه» قضاوتش کرد.

## 3. Authority
- مجوزها: {PERMISSIONS}

*چه می‌نویسم:* فقط مجوزهای ضروری برای وظایف بخش ۲؛ SoD-1..5 را نقض
نکن (نوشتن و تأییدِ همان چیز، هرگز با هم).

## 4. Relationships
- can_consult: {CAN_CONSULT}
- must_consult: {MUST_CONSULT} (مشورت امنیتی برای severity>=high اجباری)

*چه می‌نویسم:* can_consult نقش‌های هم‌فاز؛ must_consult حداقل Council
Security برای موضوعات high/critical (SLA ۴ ساعت).

## 5. Budget
- هم‌زمان فقط ۱ وظیفه اتمیک (یک نقش = یک Worker، استخدام به‌ازای وظیفه)

## 6. DoD
- خروجی مطابق جدول بخش ۲ + تأیید مقام بازبینی مربوط

## 7. Escalation
- تعارض → سطح N-1 | امنیتی critical/high → Council Security (SLA ۴ ساعت)

## 8. Lifecycle
- Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective

---

## نمونه تکمیل‌شده — Release Manager (T-005)

> این نمونه واقعی است و نسخه رسمی‌اش در `T-005-release-manager.md` است؛
> برای پر کردن قرارداد هر نقش دیگر، همین ساختار را با داده‌های آن نقش
> (از `RULES.json` و `docs/org/atomic-tasks/by-role/`) جایگزین کن.

# Worker Contract — Release Manager (T-005)

> نسخه مهارت: 0.0.1 | قالب: scaffolds/workers/_template.md | سطح ۰ — شورا

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

*نکته استثنای مستند:* هم‌ارزی `write-release` + `approve-release` طبق
مشورت C-001 مجاز است، چون SoD-2 امضای دولایه را الزامی می‌کند
(`docs/org/11-sod.md`).

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
