#!/usr/bin/env python3
"""generate_role_cards.py — تولید کارت کامل نقش‌ها از منبع حقیقت.

هر نقش دو منبع دارد:
  - RULES.json (org_model.roles): شناسه، نام، سطح، کرسی شورا، تعداد وظایف، مجوزها
  - scaffolds/workers/T-NNN-*.md: جدول وظایف کامل + reports_to + مشورت‌ها

این اسکریپت هر دو را می‌خواند و docs/org/atomic-tasks/by-role/T-NNN.md را
به‌صورت «کارت کامل نقش» بازتولید می‌کند — ایجنت با باز کردن یک فایل،
تصویر کامل نقش را می‌گیرد بدون نیاز به جست‌وجو بین فایل‌ها.

راستی‌آزمایی داخلی:
  - همه ۷۹ نقش پردازش شوند
  - جمع ردیف‌های وظایف == total_atomic_tasks در RULES.json (372)
  - تعداد وظایف هر قرارداد == شمارنده atomic_tasks همان نقش در RULES.json

اجرا:  python3 scripts/generate_role_cards.py
"""
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
RULES = ROOT / "RULES.json"
CONTRACTS_DIR = ROOT / "scaffolds" / "workers"
OUT_DIR = ROOT / "docs" / "org" / "atomic-tasks" / "by-role"

LEVEL_FA = {
    "council": "سطح ۰ — شورا",
    "senior": "سطح ۱ — مدیران ارشد",
    "specialist": "سطح ۲ — متخصصان",
    "executor": "سطح ۳ — مجریان",
    "atomic-worker": "سطح ۴ — کارگران اتمیک",
    "cross-cutting": "Cross-cutting (عرضی)",
}


def parse_contract(path: Path):
    """استخراج جدول وظایف، reports_to، must_consult و escalation از قرارداد."""
    text = path.read_text(encoding="utf-8")

    # جدول وظایف: ردیف‌های شروع‌شونده با | T-NNN-NN
    task_rows = []
    for line in text.splitlines():
        stripped = line.strip()
        if re.match(r"^\|\s*T-\d{3}-\d{2}\s*\|", stripped):
            cells = [c.strip() for c in stripped.strip("|").split("|")]
            if len(cells) == 5:
                task_rows.append(cells)

    def grab(pattern, default=""):
        m = re.search(pattern, text, re.MULTILINE)
        return m.group(1).strip() if m else default

    reports_to = grab(r"^- reports_to:\s*(.+)$", "—")
    must_consult = grab(r"^- must_consult:\s*(.+)$", "—")
    can_consult = grab(r"^- can_consult:\s*(.+)$", "—")
    # خط ارجاع: اولین خط فهرست در بخش 7 که «→» دارد
    escalation = grab(r"^## 7\. Escalation\s*$\n+(?:^>.*\n+)*^- (.+?)\s*$", "")

    # سطح از خط level قرارداد (نمایش فارسی قرارداد معتبرتر از سطح عددی است)
    level_line = grab(r"^- level:\s*(.+)$", "")
    return {
        "task_rows": task_rows,
        "reports_to": reports_to,
        "must_consult": must_consult,
        "can_consult": can_consult,
        "escalation": escalation,
        "level_line": level_line,
    }


def main():
    rules = json.loads(RULES.read_text(encoding="utf-8"))
    org = rules["org_model"]
    roles = org["roles"]
    total_expected = org["total_atomic_tasks"]

    if len(roles) != 79:
        sys.exit(f"FATAL: roles={len(roles)} (expected 79)")

    OUT_DIR.mkdir(parents=True, exist_ok=True)
    total_rows = 0
    generated = 0
    problems = []

    for role in roles:
        rid = role["id"]
        matches = sorted(CONTRACTS_DIR.glob(f"{rid}-*.md"))
        if len(matches) != 1:
            problems.append(f"{rid}: contract files found = {len(matches)}")
            continue
        contract = parse_contract(matches[0])

        n_rows = len(contract["task_rows"])
        n_declared = role["atomic_tasks"]
        if n_rows != n_declared:
            problems.append(
                f"{rid}: table rows={n_rows} but RULES.json atomic_tasks={n_declared}"
            )
        total_rows += n_rows

        seat = role.get("council_seat")
        seat_fa = "کرسی متغیر" if seat == "variable" else "—"
        level_display = contract["level_line"] or LEVEL_FA.get(
            role["level_name"], role["level_name"]
        )

        rows_md = "\n".join(
            "| " + " | ".join(r) + " |" for r in contract["task_rows"]
        )
        perms = ", ".join(f"`{p}`" for p in role["permissions"])
        has_delegate = role["level_name"] in ("council", "senior")
        delegate_fa = (
            "مجاز — می‌تواند Worker فرزند با scope محدودتر spawn کند"
            if has_delegate else
            "مسدود (نقش برگ‌ی — مجری مستقیم وظایف اتمیک)"
        )

        card = f"""# کارت نقش — {role['fa']} ({rid})

> نسخه مهارت: 0.0.2 | منبع حقیقت: `RULES.json` | قرارداد کامل: `scaffolds/workers/{matches[0].name}`

## شناسنامه

| فیلد | مقدار |
|------|-------|
| شناسه نقش | **{rid}** |
| نام | {role['en']} / {role['fa']} |
| سطح | {level_display} |
| گزارش به | {contract['reports_to']} |
| کرسی شورا | {seat_fa} |
| تعداد وظایف اتمیک | {n_declared} |

## وظایف اتمیک ({n_declared})

| Task ID | وظیفه | فاز | Output | DoD |
|---------|-------|-----|--------|-----|
{rows_md}

## مجوزها (از ۴۲ مجوز در `docs/org/04-permissions.md`)

{perms}

## مشورت‌ها

- **must_consult:** {contract['must_consult']}
- **can_consult:** {contract['can_consult']}

پروتکل کامل: `docs/org/07-consultation.md`

## ارجاع (Escalation)

- {contract['escalation']}

## چرخه استخدام این نقش

Requirement → Hiring → Contract → Onboarding → Execution → Review → Dissolution → Retrospective
راهنمای کامل با نمونه: `docs/org/08-lifecycle.md` | قالب قرارداد: `scaffolds/workers/_template.md`

## ارکستراسیون (بخش ۹ قرارداد کامل)

- **delegate:** {delegate_fa}
- چهار بدیههٔ دیگر — `kanban` / `review` / `moa` / `estop` — در قرارداد کامل بخش ۹ آمده‌اند.
- الگوی کامل: `docs/references/01-hermes-agent.md` | `docs/org/12-worker-orchestration.md`

→ قرارداد کامل: `scaffolds/workers/{matches[0].name}` § ۹

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
"""
        out = OUT_DIR / f"{rid}.md"
        out.write_text(card, encoding="utf-8")
        generated += 1

    print(f"generated: {generated}/79 role cards")
    print(f"total task rows: {total_rows} (RULES.json expects {total_expected})")

    if problems:
        print("PROBLEMS:")
        for p in problems:
            print("  -", p)
        sys.exit(1)
    if total_rows != total_expected:
        sys.exit(f"FATAL: total rows {total_rows} != expected {total_expected}")
    print("PASS: cards consistent with RULES.json")


if __name__ == "__main__":
    main()
