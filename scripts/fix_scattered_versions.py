#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""fix_scattered_versions.py — اصلاح 0.0.1‌های پراکنده و مسیرهای stale برای v0.0.2.

کارها:
  1. SKILL.md: عنوان «XX اسکلت + tools + workers» — رقم را به ۲۹ تنظیم می‌کند
     (regex چون رقم فارسی U+06F0 است و Edit مستقیم رد شده بود).
  2. SPEC.md: «v0.0.1» → «v0.0.2» در قرارداد تولید + به‌روزرسانی شمارش خروجی.
  3. README.md: مسیر دانلود v0.0.1 → archive tag v0.0.2 (gh موجود نیست؛
     archive/refs/tags برای هر تگی خودکار تولید می‌شود).
  4. PHP_DEV_ENV_SETUP.md: مسیر stale /home/z/my-project/download/... →
     docs/dev-environment/... (۲ رخداد).

council-log.md و جلسات شورا تاریخی (v0.0.1) دست‌نخورده می‌مانند — سند رخدادند.

اجرا:  python3 scripts/fix_scattered_versions.py
"""
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent


def fix_skill() -> list[str]:
    p = ROOT / "SKILL.md"
    t = p.read_text(encoding="utf-8")
    # هر «(XX اسکلت + tools + workers)» را به «(۲۹ اسکلت + tools + workers)» تبدیل کن.
    t2 = re.sub(r"\(\d{2}\s+اسکلت\s*\+\s*tools\s*\+\s*workers\)",
                "(۲۹ اسکلت + tools + workers)", t)
    p.write_text(t2, encoding="utf-8")
    return ["SKILL.md: heading scaffold count -> 29"] if t2 != t else ["SKILL.md: no change"]


def fix_spec() -> list[str]:
    p = ROOT / "SPEC.md"
    t = p.read_text(encoding="utf-8")
    changes = []
    # قرارداد تولید: نسخه
    if "نسخه‌گذاری: `v0.0.1`" in t:
        t = t.replace("نسخه‌گذاری: `v0.0.1`", "نسخه‌گذاری: `v0.0.2`")
        changes.append("SPEC.md: generation contract version -> v0.0.2")
    # خروجی نهایی: شمارش
    old_block = "Skill v0.0.1 ready.\nFiles: 228 + ADRs | Docs: 103 | Scaffolds: 79 workers + 25 code | Examples: 9"
    new_block = ("Skill v0.0.2 ready.\n"
                 "Files: 410 | ADRs: 25 | Docs: 246 | Scaffolds: 79 workers + 29 code (+1 tool) | Examples: 10")
    if old_block in t:
        t = t.replace(old_block, new_block)
        changes.append("SPEC.md: expected-output counts -> v0.0.2 reality")
    p.write_text(t, encoding="utf-8")
    return changes or ["SPEC.md: no change"]


def fix_readme() -> list[str]:
    p = ROOT / "README.md"
    t = p.read_text(encoding="utf-8")
    old = ("**راه ۲ — دانلود بسته آماده (Release v0.0.1):**\n\n"
           "از بخش\n"
           "[Releases](https://github.com/andrbi102/php-zero-dep/releases/tag/v0.0.1)\n"
           "فایل `php-zero-dep-0.0.1.zip` را بگیرید — همان بسته‌ای که در CI این مخزن\n"
           "راستی‌آزمایی شده است.")
    new = ("**راه ۲ — دانلود بستهٔ سورس تگ (tag v0.0.2):**\n\n"
           "بستهٔ سورس تگ v0.0.2 را از\n"
           "[archive/refs/tags/v0.0.2.tar.gz](https://github.com/andrbi102/php-zero-dep/archive/refs/tags/v0.0.2.tar.gz)\n"
           "بگیرید — GitHub برای هر تگ به‌صورت خودکار tar.gz/zip تولید می‌کند؛ همان سورسی که در CI این مخزن\n"
           "راستی‌آزمایی شده است.")
    if old in t:
        t = t.replace(old, new)
        p.write_text(t, encoding="utf-8")
        return ["README.md: download path v0.0.1 zip -> v0.0.2 tag archive"]
    # fallback: فقط نسخه‌ها را بزن اگر ساختار فرق کرده
    t2 = t.replace("Release v0.0.1", "tag v0.0.2").replace(
        "releases/tag/v0.0.1", "archive/refs/tags/v0.0.2.tar.gz").replace(
        "php-zero-dep-0.0.1.zip", "v0.0.2 source archive")
    if t2 != t:
        p.write_text(t2, encoding="utf-8")
        return ["README.md: version refs -> v0.0.2 (fallback)"]
    return ["README.md: no change"]


def fix_devenv() -> list[str]:
    p = ROOT / "docs" / "dev-environment" / "PHP_DEV_ENV_SETUP.md"
    t = p.read_text(encoding="utf-8")
    old = "/home/z/my-project/download/PHP_DEV_ENV_SETUP.md"
    new = "docs/dev-environment/PHP_DEV_ENV_SETUP.md"
    n = t.count(old)
    if n:
        t = t.replace(old, new)
        p.write_text(t, encoding="utf-8")
        return [f"PHP_DEV_ENV_SETUP.md: {n} stale path(s) -> docs/dev-environment/"]
    return ["PHP_DEV_ENV_SETUP.md: no change"]


def fix_byphase() -> list[str]:
    """هدر «نسخه مهارت: 0.0.1» در docs/org/atomic-tasks/by-phase/F*.md (۱۰ فایل زنده)."""
    changes = []
    for p in sorted((ROOT / "docs" / "org" / "atomic-tasks" / "by-phase").glob("F*.md")):
        t = p.read_text(encoding="utf-8")
        t2 = re.sub(r"نسخه مهارت:\s*0\.0\.1", "نسخه مهارت: 0.0.2", t)
        if t2 != t:
            p.write_text(t2, encoding="utf-8")
            changes.append(f"{p.name}: header -> 0.0.2")
    return changes or ["by-phase: no change"]


def main() -> int:
    for fn in (fix_skill, fix_spec, fix_readme, fix_devenv, fix_byphase):
        for line in fn():
            print("  -", line)
    print("PASS: scattered version/path fixes applied")
    return 0


if __name__ == "__main__":
    sys.exit(main())
