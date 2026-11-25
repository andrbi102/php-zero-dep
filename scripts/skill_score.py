#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""skill_score.py — ممیزی امتیازی skill با قرارداد JSON (الگوبرداری از skillspector)

سه ممیزی موجود را متحد می‌کند و یک نمرهٔ ریسک ۰-۱۰۰ + توصیهٔ SAFE/CAUTION/DO_NOT_INSTALL
+ گزارش completeness تولید می‌کند. الگوبرداری از nvidia/skillspector (Apache-2.0):
  - شناسهٔ یافته دسته‌بندی‌شده + وزن شدت → نمرهٔ ریسک
  - بودجهٔ ingest fail-closed (byte ceiling روی scaffold‌ها)
  - completeness ledger (exit ۱ اگر ناقص)
  - قرارداد JSON پایدار + exit ۰/۱/۲

استفاده:
  python3 scripts/skill_score.py                 # خروجی متنی
  python3 scripts/skill_score.py --format json   # قرارداد JSON پایدار
  python3 scripts/skill_score.py --fail-on-findings   # exit ۱ اگر نمره > ۰

کدهای خروجی:
  ۰ = نمره ≤ ۵۰ و gates سبز
  ۱ = نمره > ۵۰ یا --fail-on-findings یا completeness ناقص
  ۲ = خطای اجرا

@version 0.0.2
@pattern-source nvidia/skillspector (Apache-2.0)
"""
import argparse
import json
import os
import subprocess
import sys

REPO = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEVERITY_WEIGHT = {"critical": 50, "high": 25, "medium": 10, "low": 5}
SCORE_SAFE = 50  # ≤ ۵۰ = SAFE
MAX_FILE_BYTES = 1_000_000  # ۱ MB به‌ازای فایل scaffold (fail-closed)


def run(cmd: list[str]) -> tuple[int, str]:
    """اجرای یک فرمان و بازگشت (exit_code, stdout_tail)."""
    try:
        # بررسی در دسترس بودن php از PATH یا مسیر پروژه
        env = os.environ.copy()
        php = shutil_which_php()
        if php:
            env["PATH"] = os.path.dirname(php) + os.pathsep + env.get("PATH", "")
        r = subprocess.run(cmd, capture_output=True, text=True, cwd=REPO, env=env, timeout=120)
        return r.returncode, (r.stdout + r.stderr).strip()[-800:]
    except FileNotFoundError:
        return 2, "tool not found: " + " ".join(cmd)
    except subprocess.TimeoutExpired:
        return 2, "timeout: " + " ".join(cmd)


def shutil_which_php() -> str | None:
    for cand in ("php", "/home/z/my-project/bin/php"):
        try:
            r = subprocess.run([cand, "-v"], capture_output=True, text=True, timeout=10)
            if r.returncode == 0:
                return cand if "/" in cand else "php"
        except Exception:
            continue
    return None


def check_org() -> dict:
    code, out = run([sys.executable, "scripts/verify_org.py"])
    issues = []
    if code != 0:
        issues.append({"id": "ORG-001", "severity": "critical",
                       "msg": "verify_org.py failed — org counts regression (79/372/42)"})
    return {"gate": "org", "ok": code == 0, "exit": code, "issues": issues, "log": out}


def check_security() -> dict:
    php = shutil_which_php()
    if not php:
        return {"gate": "security", "ok": False, "exit": 2, "issues": [{"id": "SEC-000", "severity": "high", "msg": "php not found — security audit skipped"}], "log": ""}
    code, out = run([php, "tests/security-audit.php"])
    issues = []
    if code != 0:
        # شمارش findings از خروجی
        issues.append({"id": "SEC-001", "severity": "critical",
                       "msg": "security-audit.php failed — forbidden functions / strict_types / composer"})
    return {"gate": "security", "ok": code == 0, "exit": code, "issues": issues, "log": out}


def check_smoke() -> dict:
    php = shutil_which_php()
    if not php:
        return {"gate": "behavior", "ok": False, "exit": 2, "issues": [{"id": "BEH-000", "severity": "high", "msg": "php not found — smoke test skipped"}], "log": ""}
    code, out = run([php, "tests/smoke-test.php"])
    issues = []
    if code != 0:
        issues.append({"id": "BEH-001", "severity": "high",
                       "msg": "smoke-test.php failed — scaffold behavior regression"})
    return {"gate": "behavior", "ok": code == 0, "exit": code, "issues": issues, "log": out}


def check_byte_ceiling() -> dict:
    """بودجهٔ ingest fail-closed: هیچ scaffold نباید > MAX_FILE_BYTES باشد (skillspector MAX_FILE_BYTES)."""
    issues = []
    scaff_dir = os.path.join(REPO, "scaffolds")
    big = []
    for root, _dirs, files in os.walk(scaff_dir):
        for fn in files:
            if not fn.endswith(".php"):
                continue
            full = os.path.join(root, fn)
            try:
                size = os.path.getsize(full)
            except OSError:
                continue
            if size > MAX_FILE_BYTES:
                big.append({"file": os.path.relpath(full, REPO), "size": size})
                issues.append({"id": "INGEST-001", "severity": "medium",
                               "msg": f"scaffold > {MAX_FILE_BYTES} bytes: {fn} ({size}) — split it"})
    return {"gate": "ingest-budget", "ok": not big, "exit": 1 if big else 0, "issues": issues, "big_files": big, "log": ""}


def check_version_consistency() -> dict:
    """VERSION و RULES.json skill_version باید یکی باشند."""
    issues = []
    try:
        with open(os.path.join(REPO, "VERSION"), encoding="utf-8") as f:
            v_file = f.read().strip()
    except OSError:
        v_file = ""
    try:
        with open(os.path.join(REPO, "RULES.json"), encoding="utf-8") as f:
            rules = json.load(f)
        v_rules = rules.get("skill_version", "")
    except (OSError, json.JSONDecodeError):
        v_rules = ""
    if not v_file:
        issues.append({"id": "VER-001", "severity": "high", "msg": "VERSION file empty/missing"})
    if not v_rules:
        issues.append({"id": "VER-002", "severity": "high", "msg": "RULES.json skill_version missing"})
    if v_file and v_rules and v_file != v_rules:
        issues.append({"id": "VER-003", "severity": "high",
                       "msg": f"version mismatch: VERSION={v_file} RULES.json={v_rules}"})
    return {"gate": "version", "ok": not issues, "exit": 1 if issues else 0, "issues": issues,
            "version_file": v_file, "version_rules": v_rules, "log": ""}


def compute_score(gates: list[dict]) -> int:
    total = 0
    for g in gates:
        for issue in g["issues"]:
            total += SEVERITY_WEIGHT.get(issue["severity"], 5)
    return min(100, total)


def recommendation(score: int, gates: list[dict]) -> str:
    if any(g["exit"] == 2 for g in gates):
        return "DO_NOT_INSTALL"
    if score > 50 or any(not g["ok"] for g in gates):
        return "CAUTION"
    return "SAFE"


def main() -> int:
    ap = argparse.ArgumentParser(description="scored skill self-audit (skillspector-inspired)")
    ap.add_argument("--format", choices=["text", "json"], default="text")
    ap.add_argument("--fail-on-findings", action="store_true", help="exit 1 if score > 0")
    args = ap.parse_args()

    gates = [
        check_org(),
        check_security(),
        check_smoke(),
        check_byte_ceiling(),
        check_version_consistency(),
    ]
    all_issues = [i for g in gates for i in g["issues"]]
    score = compute_score(gates)
    # completeness: همهٔ gates اجرا شدند؟ (هیچ one نه)
    completeness = sum(1 for g in gates if g["exit"] != 2) / len(gates)
    incomplete = completeness < 1.0
    rec = recommendation(score, gates)

    result = {
        "skill": "php-zero-dep",
        "version": next((g.get("version_file") for g in gates if g["gate"] == "version"), ""),
        "risk_assessment": {"score": score, "max": 100, "recommendation": rec},
        "gates": [{k: v for k, v in g.items() if k != "log"} for g in gates],
        "issues": all_issues,
        "analysis_completeness": {"ratio": completeness, "incomplete": incomplete},
        "metadata": {"tool": "skill_score.py", "pattern_source": "nvidia/skillspector (Apache-2.0)"},
    }

    if args.format == "json":
        print(json.dumps(result, ensure_ascii=False, indent=2))
    else:
        print(f"SKILL SCORE: {score}/100  [{rec}]")
        print(f"completeness: {completeness:.0%}{' (INCOMPLETE)' if incomplete else ''}")
        for g in gates:
            tag = "OK  " if g["ok"] else "FAIL"
            print(f"  {tag} {g['gate']:<18} exit={g['exit']}")
            for i in g["issues"]:
                print(f"        [{i['severity']}] {i['id']}: {i['msg']}")
        if not all_issues:
            print("\nNO FINDINGS — skill passes all gates.")

    # کدهای خروجی (skillspector-style)
    if any(g["exit"] == 2 for g in gates):
        return 2  # خطای اجرا
    if args.fail_on_findings and score > 0:
        return 1
    if score > 50 or incomplete or any(not g["ok"] for g in gates):
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
