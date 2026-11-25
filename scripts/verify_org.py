#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""verify_org.py — گارد رگرسیون شمارش‌های سازمانی (درس ۴۸۲/۳۷۲)

RULES.json را می‌خواند و بررسی می‌کند:
  - ۷۹ نقش، ۳۷۲ وظیفه اتمیک، ۴۲ مجوز
  - توزیع سطح‌ها: 10/8/30/18/8 + 5 cross-cutting
  - وظایف هر نقش با مجموع اعلامی یکی است
  - SoD-1: هیچ write-X و approve-X هم‌زمان (با استثنای مستند release)
  - SoD-3/SoD-4: نگهدارندگان مجوزهای حساس
خروجی: 0 = سالم | 1 = رگرسیون
"""
import json
import os
import sys

RULES = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "RULES.json")

with open(RULES, encoding="utf-8") as f:
    rules = json.load(f)

org = rules["org_model"]
errors = []


def check(cond, msg):
    print(("  OK   " if cond else "  FAIL ") + msg)
    if not cond:
        errors.append(msg)


roles = org["roles"]
check(len(roles) == 79, f"total roles = 79 (got {len(roles)})")
check(org["total_roles"] == len(roles), "declared total_roles matches roles array")
task_sum = sum(r["atomic_tasks"] for r in roles)
check(task_sum == 372, f"sum of atomic_tasks = 372 (got {task_sum})")
check(org["total_atomic_tasks"] == task_sum, "declared total_atomic_tasks matches sum")

from collections import Counter
levels = Counter(r["level"] for r in roles)
tasks_by_level = {}
for r in roles:
    tasks_by_level[r["level"]] = tasks_by_level.get(r["level"], 0) + r["atomic_tasks"]
expected = {0: (10, 67), 1: (8, 88), 2: (30, 132), 3: (18, 62), 4: (8, 8), 5: (5, 15)}
for lv, (nr, nt) in expected.items():
    check(levels.get(lv, 0) == nr, f"level {lv}: {nr} roles (got {levels.get(lv, 0)})")
    check(tasks_by_level.get(lv, 0) == nt, f"level {lv}: {nt} tasks (got {tasks_by_level.get(lv, 0)})")

# شمارش فایل‌های قرارداد Worker (اگر بسته کامل موجود باشد)
workers_dir = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "scaffolds", "workers")
if os.path.isdir(workers_dir):
    contracts = [f for f in os.listdir(workers_dir) if f.startswith("T-") and f.endswith(".md")]
    check(len(contracts) == 79, f"worker contracts = 79 (got {len(contracts)})")
    by_role = os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "docs", "org", "atomic-tasks", "by-role")
    if os.path.isdir(by_role):
        check(len([f for f in os.listdir(by_role) if f.endswith(".md")]) == 79,
              "atomic-tasks/by-role = 79 files")

# SoD-1 با استثنای مستند release (مشورت C-001)
sod1_bad = []
for r in roles:
    for p in r["permissions"]:
        if p.startswith("write-") and f"approve-{p[6:]}" in r["permissions"]:
            if r["id"] == "T-005" and p == "write-release":
                continue  # استثنای مستند — approve-release دولایه است (SoD-2)
            sod1_bad.append(f"{r['id']} {r['en']}: {p}")
check(not sod1_bad, f"SoD-1 holds (violations: {sod1_bad or 'none'})")
co_signers = [r["en"] for r in roles if "approve-release" in r["permissions"] and r["en"] != "Release Manager"]
check(len(co_signers) >= 1, f"SoD-2 co-signer exists ({co_signers})")
check(org["council"]["release_signatures_required"] == 5, "exec-release = 5 signatures")

veto_sec = [r["en"] for r in roles if "veto-security" in r["permissions"]]
check(veto_sec == ["Council Security"], f"SoD-3 veto-security holders = {veto_sec}")
exec_mig = [r["en"] for r in roles if "exec-migration" in r["permissions"]]
check(set(exec_mig) == {"Database Lead", "DB Migration Specialist"}, f"SoD-4 exec-migration holders = {exec_mig}")

# مجوزها فقط از فهرست رسمی؟ (فهرست رسمی در RULES نیست — از نقش‌ها جمع می‌کنیم و شمارش کلی می‌گیریم)
all_perms = {p for r in roles for p in r["permissions"]}
check(org["total_permissions"] == 42, f"total_permissions = 42 (in-use: {len(all_perms)})")

print()
if errors:
    print(f"VERIFY ORG: {len(errors)} REGRESSION(S)")
    sys.exit(1)
print("VERIFY ORG: PASS — 79 roles / 372 tasks / 42 permissions / SoD intact")
