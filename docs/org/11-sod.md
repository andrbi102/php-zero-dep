# تفکیک وظایف (SoD-1..5)

> نسخه مهارت: **0.0.1** | گارد خودکار: scripts/verify_org.py

- **SoD-1:** هیچ Worker هم‌زمان write-X و approve-X ندارد
- **SoD-2:** approve-release نیازمند ۲ امضا (Release Manager + ۱ شورا)
- **SoD-3:** veto-security فقط توسط Council Security یا Security Engineer
- **SoD-4:** exec-migration فقط توسط Database Lead یا DB Migration Specialist
- **SoD-5:** Code Reviewer نمی‌تواند نویسنده همان کد باشد

## استثنای مستند — release

طبق مشورت C-001 (consultation/log/2026-11-25-sod-release-exception.md):
Release Manager (T-005) هم `write-release` و هم `approve-release` دارد؛
چون امضای approve-release طبق SoD-2 **دولایه** است (RM + ۱ عضو شورا)،
خودتأییدی ساختاراً ناممکن است. هم‌امضای الزامی: حداقل یک عضو شورا
دارای approve-release (در پیاده‌سازی فعلی: Council Architect).
این استثنا در `scripts/verify_org.py` به‌صورت گارد کد شده است.

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
