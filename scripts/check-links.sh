#!/usr/bin/env bash
# check-links.sh — بازبینی سه‌ماهه منابع (SPEC بخش ۱۷: سیاست بازبینی دوره‌ای)
# همه لینک‌های https کاتالوگ را با HTTP HEAD/GET بررسی می‌کند؛ غیر-200 گزارش می‌شود.
# خروجی: 0 = همه سالم | 1 = حداقل یک لینک مشکوک

set -u
CATALOG="$(dirname "$0")/../docs/catalog.md"
[ -f "$CATALOG" ] || CATALOG="$(dirname "$0")/../SPEC.md"

mapfile -t URLS < <(grep -oE 'https://[^) ]+' "$CATALOG" | sort -u)

if [ "${#URLS[@]}" -eq 0 ]; then
  echo "no URLs found in $CATALOG"
  exit 1
fi

FAIL=0
for url in "${URLS[@]}"; do
  code=$(curl -s -o /dev/null -w '%{http_code}' -L --max-time 20 -A "php-zero-dep-link-check" "$url" || echo "ERR")
  if [ "$code" = "200" ]; then
    echo "  OK    $url"
  else
    echo "  FAIL  $url  ->  $code"
    FAIL=1
  fi
done

if [ "$FAIL" -eq 0 ]; then
  echo "ALL LINKS OK (${#URLS[@]} checked)"
else
  echo "LINK CHECK FAILED — لینک‌های FAIL را حذف/اصلاح و ستون «آخرین تأیید» را به‌روز کنید"
fi
exit "$FAIL"
