#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""fix_worker_contracts.py — تکمیل قراردادهای Worker برای v0.0.2.

دو کار روی هر یک از ۷۹ قرارداد scaffolds/workers/T-NNN-*.md انجام می‌دهد:
  1. bump «نسخه مهارت: 0.0.1» → «0.0.2» (الگوی bump اصلی متن ساده را نگرفته بود).
  2. افزودن/بازنویسی «## 9. Hermes-derived orchestration primitives» —
     stub نقش‌آگاه: delegate بر اساس سطح (سطح ۰ council و ۱ senior = orchestrator
     ⇒ مجاز؛ سطح ۲-۴ = leaf ⇒ مسدود). مدل delegation در RULES.json صریح نیست،
     پس از سلسله‌مراتب استنتاج می‌شود.

Idempotent: بخش ۹ همواره با وضعیت صحیح delegate بازنویسی می‌شود.

اجرا:  python3 scripts/fix_worker_contracts.py
"""
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
RULES = ROOT / "RULES.json"
CONTRACTS_DIR = ROOT / "scaffolds" / "workers"

SECTION9_REF = (
    "> منبع الگو: [`docs/references/01-hermes-agent.md`](../../docs/references/01-hermes-agent.md)"
    " | [`docs/org/12-worker-orchestration.md`](../../docs/org/12-worker-orchestration.md)"
    " | پیاده‌سازی: [`scaffolds/worker-orchestrator.php`](../worker-orchestrator.php)"
)


def delegate_line(has_delegate: bool) -> str:
    if has_delegate:
        return ("**delegate:** مجاز — می‌تواند Worker فرزند با scope محدودتر spawn کند "
                "(toolsets فرزند همواره زیرمجموعهٔ toolsets والد).")
    return ("**delegate:** مسدود (نقش برگ‌ی) — این نقش فرزند استخدام نمی‌کند؛ "
            "خودش مجری مستقیم وظایف اتمیک است.")


def section9_stub(has_delegate: bool) -> str:
    return (
        "\n\n## 9. Hermes-derived orchestration primitives\n\n"
        f"{SECTION9_REF}\n\n"
        f"- {delegate_line(has_delegate)}\n"
        "- **kanban:** claim روی کارت‌های وظیفهٔ این نقش، heartbeat پیش از هر خروجی، "
        "`request_review` برای تأیید بازبینی.\n"
        "- **review:** بازبینی خروجی توسط بازبین مستقل (SoD-5: نویسنده ≠ بازبین) پیش از پذیرش.\n"
        "- **moa:** برای تصمیمات پیچیدهٔ چندمنظری، مشورت ترکیبی چندمشاوره (تجمیع، نه رأی‌گیری).\n"
        "- **estop:** تنها T-000/T-001 استاپ سراسری می‌زنند؛ این نقش تابع است.\n"
    )


def main() -> int:
    rules = json.loads(RULES.read_text(encoding="utf-8"))
    roles = rules["org_model"]["roles"]
    if len(roles) != 79:
        sys.exit(f"FATAL: roles={len(roles)} (expected 79)")

    bumped = 0
    section9_added = 0
    delegate_roles = []
    problems = []

    for role in roles:
        rid = role["id"]
        matches = sorted(CONTRACTS_DIR.glob(f"{rid}-*.md"))
        if len(matches) != 1:
            problems.append(f"{rid}: contract files found = {len(matches)}")
            continue
        path = matches[0]
        text = path.read_text(encoding="utf-8")
        original = text

        # 1) bump نسخه در هدر
        text = re.sub(r"نسخه مهارت:\s*0\.0\.1", "نسخه مهارت: 0.0.2", text)

        # 2) بازنویسی بخش ۹ (با وضعیت صحیح delegate بر اساس سطح)
        #    سطح ۰ (council) و ۱ (senior) = orchestrator ⇒ delegate مجاز؛
        #    سطح ۲-۴ (specialist/executor/atomic-worker) = leaf ⇒ مسدود.
        level_name = role.get("level_name", "")
        has_delegate = level_name in ("council", "senior")
        if has_delegate:
            delegate_roles.append(rid)
        # هرچه بخش ۹ موجود است را (از اولین «## 9.» تا پایان فایل) حذف کن، سپس stub درست را بزن.
        m9 = re.search(r"^## 9\.", text, re.MULTILINE)
        if m9:
            text = text[: m9.start()].rstrip()
        text = text.rstrip() + section9_stub(has_delegate)
        section9_added += 1

        if text != original:
            path.write_text(text, encoding="utf-8")
            if "نسخه مهارت: 0.0.2" in text and "نسخه مهارت: 0.0.1" not in text:
                bumped += 1

    print(f"contracts processed: {len(roles) - len(problems)}/79")
    print(f"version bumped to 0.0.2: {bumped}")
    print(f"section 9 (re)written: {section9_added}")
    print(f"roles WITH delegate permission ({len(delegate_roles)}): {', '.join(delegate_roles)}")
    if problems:
        print("PROBLEMS:")
        for p in problems:
            print("  -", p)
        return 1
    print("PASS: worker contracts complete for v0.0.2")
    return 0


if __name__ == "__main__":
    sys.exit(main())
