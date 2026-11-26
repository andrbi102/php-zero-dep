# RELEASE — php-zero-dep v0.0.2

> تاریخ انتشار: ۲۰۲۶-۱۱-۲۶ | برچسب Git: `v0.0.2` | لایسنس: MIT
> سند کامل تغییرات: [`CHANGELOG.md`](CHANGELOG.md) | مشخصات: [`SPEC.md`](SPEC.md)

---

## خلاصه

v0.0.2 مهارت را از یک جعبه‌ابزار PHP بدون وابستگی به یک **مهارت الگوبرداری‌شده**
ارتقا می‌دهد: پنج مخزن مرجع (hermes-agent، humanizer، firecrawl، ui-ux-pro-max،
skillspector) به‌عنوان منبع الگو مطالعه و به PHP خالص بازنویسی شدند — **هیچ کد
از مخازن مرجع کپی نشده** (رعایت AGPL-3.0 انتقالی)، فقط معماری و الگوها.

سه محور اصلی:

1. **ارکستراسیون Worker** (الگوی hermes-agent): پنج بدیههٔ `delegate`/`kanban`/
   `review`/`moa`/`estop` + state machine + IterationBudget + DenialCircuitBreaker
   + handoff + capability token HMAC — بدون افزودن نقش (۷۹/۳۷۲/۴۲ دست‌نخورده).
2. **سه اسکلت جدید**: `humanizer.php` (آشکارساز بوی AI)، `web-fetch.php`
   (واکشی وب + HTML→markdown)، `design-tokens.php` (توکن سه‌لایه).
3. **تاکسونومی کاربردها**: ۲۰ دسته و ~۳۶۰ کاربرد با کد `<دسته>.<زیرمجموعه>`
   و پروفایل پیشنهادی، به‌علاوهٔ کاتالوگ ۵۷ مخزن تأییدشده.

---

## آمار

| سنجه | مقدار |
|------|-------|
| فایل‌های ردیابی‌شده | ۴۱۰ |
| اسکلت‌های کد | ۲۹ (+ ۱ ابزار) |
| قراردادهای Worker | ۷۹ (همه با بخش ۹ ارکستراسیون) |
| مثال‌ها | ۱۰ |
| ADR | ۲۵ (۰۰۰۱–۰۰۲۵) |
| سندهای docs/ | ۲۴۶ |
| مخازن مرجع تأییدشده | ۵۷ (لینک‌ها ۲۰۲۶-۱۱-۲۶ راستی‌آزمایی شد) |
| سناریوهای eval | ۱۲ |

---

## نصب / ارتقا

**راه ۱ — کلون:**
```bash
git clone https://github.com/andrbi102/php-zero-dep.git
git -C php-zero-dep checkout v0.0.2
```

**راه ۲ — بستهٔ سورس تگ:**
[archive/refs/tags/v0.0.2.tar.gz](https://github.com/andrbi102/php-zero-dep/archive/refs/tags/v0.0.2.tar.gz)
(GitHub برای هر تگ به‌صورت خودکار tar.gz/zip تولید می‌کند).

سپس پوشه را به‌عنوان skill به ایجنت بدهید یا `SKILL.md` را به پرامپت اضافه کنید.

---

## راستی‌آزمایی انتشار

```bash
python3 scripts/verify_org.py          # 79 roles / 372 tasks / 42 perms
php tests/security-audit.php           # forbidden funcs / strict_types / composer
php tests/smoke-test.php               # 48 behavior check (شامل ۱۱ چک ارکستراسیون)
python3 scripts/skill_score.py         # 6 gate: org/security/behavior/ingest/version/content-completeness
```

نتیجهٔ موردانتظار: همهٔ درگاه‌ها `exit=0`، `SKILL SCORE: 0/100 [SAFE]`،
`completeness: 100%`.

---

## اصلاحات پس از انتشار اولیهٔ v0.0.2

این نسخه شامل اصلاحاتی است که پس از نقد v0.0.2 اعمال شد (همراه با force-move
برچسب `v0.0.2`):

- **A1:** حذف کلمهٔ روسی آلوده‌کننده در کامنت `worker-orchestrator.php`.
- **A2:** لایهٔ `component` توکن‌ها اکنون در هر سه فرمت صدور (PHP/JSON/CSS) ظاهر
  می‌شود (قبلاً تعریف ولی unreachable بود).
- **A3:** افزودن `DENIAL_CROSS_DOMAIN` در `web-fetch.php` — لینک cross-domain
  دیگر به‌اشتباه `DENIAL_NON_HTTP` نمی‌گیرد.
- **A4:** داک `moa` به‌روز شد: اجرای **متوالی** در PHP خالص (نه «ThreadPool موازی»).
- **B5+6:** بخش ۹ (ارکستراسیون) به همهٔ ۷۹ قرارداد Worker + ۷۹ کارت by-role
  اضافه شد (قالب وعده می‌داد ولی کارت‌ها فاقدش بودند)؛ نسخهٔ ۱۷۳ فایل جا‌مانده
  روی `0.0.2` هم‌تراز شد.
- **B7:** عنوان scaffolds در SKILL.md از «۳۰» به «۲۹» اصلاح شد (دوبارشماری).
- **B8:** مسیر stale در `PHP_DEV_ENV_SETUP.md` به مکان واقعی به‌روز شد.
- **C9:** `check-links.sh` اجرا شد؛ یک ارجاع جعلی (`phpdet`/crates.io ۴۰۴) حذف
  و با ابزار واقعی (mago) ادغام شد؛ ۵۷ لینک سبز.
- **C10:** smoke-test با ۱۱ چک جدید (steer/handoff/moa/forceTerminate/
  continueWorker/filterLinks/robotsAllows) گسترش یافت.
- **C11:** ۴ سناریوی eval جدید (id 9–12) برای ارکستراسیون، مسیریابی use-case،
  humanizer و web-fetch.
- **D13:** درگاه `content-completeness` به `skill_score.py` اضافه شد (هر قرارداد
  بخش ۹ دارد؟ هر مرجع به ADR موجود لینک می‌دهد؟).
- **D14:** سند نقشهٔ ترکیب ۵ مرجع (`docs/references/06-composition-map.md`).
- **D15:** مطالعهٔ موردی کامل ۱۰-فازی برای هر ۲۰ دستهٔ use-case.

---

## سازگاری

- **بدون breaking change** برای کاربران v0.0.1: همهٔ اسکلت‌های موجود دست‌نخورده؛
  افزوده‌ها سندی/اختیاری‌اند.
- پی‌روی از SemVer: این یک انتشار patch درون‌چرخه‌ای v0.0.2 است (اصلاح + تکمیل)،
  نه v0.0.3، چون v0.0.2 اولیه ناقص/ناسازگار بود و برچسب منتقل شد.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | لایسنس MIT
