#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""create_github_release.py — ساخت/به‌روزرسانی GitHub Release برای v0.0.2 via API.

توکن را از env var GH_RELEASE_TOKEN می‌خواند — هرگز چاپ/ذخیره نمی‌شود.
اگر release برای tag v0.0.2 موجود باشد، به‌روزرسانی می‌کند (PATCH)؛ وگرنه می‌سازد (POST).
بدنهٔ release از RELEASE.md خوانده می‌شود.

اجرا:  GH_RELEASE_TOKEN=... python3 scripts/create_github_release.py
"""
import json
import os
import subprocess
import sys
import urllib.error
import urllib.request

REPO = "andrbi102/php-zero-dep"
TAG = "v0.0.2"
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

token = os.environ.get("GH_RELEASE_TOKEN", "").strip()
if not token:
    sys.exit("FATAL: GH_RELEASE_TOKEN not set")

# commitish = HEAD فعلی main
commitish = subprocess.check_output(["git", "-C", ROOT, "rev-parse", "HEAD"],
                                    text=True).strip()

body = (open(os.path.join(ROOT, "RELEASE.md"), encoding="utf-8").read()
        if os.path.isfile(os.path.join(ROOT, "RELEASE.md")) else "")

api = f"https://api.github.com/repos/{REPO}/releases"


def http(method, url, data=None):
    req = urllib.request.Request(url, method=method, data=(
        json.dumps(data).encode("utf-8") if data is not None else None))
    req.add_header("Authorization", f"token {token}")
    req.add_header("Accept", "application/vnd.github+json")
    req.add_header("Content-Type", "application/json; charset=utf-8")
    try:
        with urllib.request.urlopen(req, timeout=60) as r:
            return r.status, json.loads(r.read().decode("utf-8") or "{}")
    except urllib.error.HTTPError as e:
        return e.code, json.loads(e.read().decode("utf-8") or "{}")


# ۱) آیا release برای این tag هست؟
status, existing = http("GET", f"{api}/tags/{TAG}")
release_id = None
if status == 200:
    release_id = existing.get("id")
    print(f"existing release found (id={release_id}) -> will PATCH")

payload = {
    "tag_name": TAG,
    "target_commitish": commitish,
    "name": "v0.0.2 — مهارت PHP بدون وابستگی، الگوبرداری‌شده از ۵ مخزن مرجع",
    "body": body,
    "draft": False,
    "prerelease": False,
}

if release_id:
    status, resp = http("PATCH", f"{api}/{release_id}", payload)
    action = "updated"
else:
    status, resp = http("POST", api, payload)
    action = "created"

if status in (200, 201):
    print(f"RELEASE {action} OK ({status})")
    print(f"  url:    {resp.get('html_url')}")
    print(f"  tag:    {resp.get('tag_name')}  commitish: {resp.get('target_commitish', '')[:12]}")
    print(f"  draft={resp.get('draft')} prerelease={resp.get('prerelease')}")
    sys.exit(0)
else:
    print(f"RELEASE FAILED ({status})")
    print(json.dumps(resp, ensure_ascii=False, indent=2)[:1000])
    sys.exit(1)
