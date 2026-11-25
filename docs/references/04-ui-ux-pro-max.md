# ۰۴ — مرجع: nextlevelbuilder/ui-ux-pro-max-skill

> نسخه مهارت: **0.0.2** | لایسنس مرجع: **MIT** | زبان: Python (جستجوی BM25 روی CSV) + Markdown
> URL: <https://github.com/nextlevelbuilder/ui-ux-pro-max-skill>
> ADR متناظر: [0021](../../ADR/0021-design-token-architecture.md)

## هویت

یک **skill ایجنت چند-پلتفرمی** (۲۰ پلتفرم: Claude Code، Cursor، Windsurf،
Copilot، Kiro و …) که هوش طراحی UI/UX قابل‌جستجوی محلی ارائه می‌دهد —
style، palette، font pairing، UX rules، icon، GSAP preset، chart، و راهنمای
stack-specific — با یک CLI Python جستجوی BM25+regex روی datasetهای CSV.

## ساختار (مجموعهٔ مرتبط)

```
ui-ux-pro-max-skill/
├── skill.json               # manifest چند-پلتفرمی
├── .claude/skills/ui-ux-pro-max/   # skill اصلی
│   ├── SKILL.md             # frontmatter (name + description طولانی)
│   ├── references/quick-reference.md   # ۱۴۱ قانون (خواندن‌به‌درخواست)
│   └── references/pro-rules.md         # app-polish
├── .claude/skills/design-system/      # skill خواهر: معماری توکن سه‌لایه
│   └── references/{token-architecture,primitive-tokens,semantic-tokens,
│                    component-tokens,states-and-variants,tailwind-integration}.md
├── data/*.csv               # ۱۵ dataset (colors، typography، stacks، …)
├── data/data-provenance.json  # sourceFile/sources[]/verifiedAt/sla/appliesTo
└── scripts/{search,core,design_system,preview,validate_data,reasoning_contract}.py
```

## آنچه آموختیم

۱. **SKILL.md به‌عنوان router (Progressive Disclosure):** SKILL.md کوتاه —
   یک جدول قانون اولویت‌بندی‌شده + زوج «When to Apply / Skip it for» +
   workflow شماره‌گذاری‌شده — و کل corpus قانون در `references/*.md`
   خواندن‌به‌درخواست.
۲. **قرارداد پرس‌وجو + انضباط ۰-نتیجه:** «کوچک‌ترین حالت جستجو را انتخاب کن»،
   «یک intent غالب، ۲-۵ واژه»، «یک retry سپس بگو no match»، و «هرگز ۰-نتیجه
   را به‌عنوان داده ارائه نکن».
۳. **CSV به‌عنوان پایگاه‌داده + BM25:** هم‌مکان یک `scripts/search.py`
  وابسته‌به-هیچ روی `data/*.csv` با BM25+regex + cache، تا skill یک پایگاه
   دانش قابل‌جستجوی محلی داشته باشد نه فراخوانی متن آزاد.
۴. **Master + page-override persistence با guard `--force`:** artifactها به‌عنوان
   `MASTER.md` + فایل override صفحه‌ای نوشته می‌شوند، skip-if-exists مگر
   تأیید صریح، و «قبل از تصمیم به force، Master موجود را بخوان».
۵. **صدور دوگانهٔ توکن (DTCG JSON + CSS vars) از یک resolver:** هم JSON W3C
   Design Tokens Community Group و هم `:root` custom property از یک نقشهٔ
   semantic واحد.
۶. **provenance + freshness SLA به‌ازای رکورد:** هر ردیف dataset با
   `sourceFile`، `sources[]` (URL رسمی)، `verifiedAt`، `sla`
   (manual/needs-review)، `appliesTo` برچسب‌گذاری می‌شود.

## آنچه الگوبرداری شد (با artifact)

| الگو | منبع (مسیر فایل) | artifact ما |
|------|------------------|-------------|
| معماری توکن سه‌لایه (primitive→semantic→component) | `.claude/skills/design-system/references/token-architecture.md` | [`docs/frontend/09-design-tokens.md`](../frontend/09-design-tokens.md) |
| صدور دوگانه (PHP constants / JSON / CSS vars) از یک resolver | `scripts/design_system.py:74-89,981-1085` | `DesignTokens::export()` در [`scaffolds/design-tokens.php`](../../scaffolds/design-tokens.php) |
| نقش‌های رنگ semantic (Primary/On-Primary/.../Ring) | `design_system.py:74-89` | نقشهٔ semantic در همان اسکلت |
| provenance + freshness SLA به‌ازای رکورد | `data/data-provenance.json` | الگوبرداری در ستون «آخرین تأیید» `docs/catalog.md` + `scripts/check-links.sh` (موجود) |
| file-mode discipline | SKILL.md §file-mode | مشترک با الگوی humanizer (به [`02-humanizer.md`](02-humanizer.md)) |
| SKILL.md به‌عنوان router | `.claude/skills/ui-ux-pro-max/SKILL.md` | در حال حاضر SKILL.md ما router است؛ این الگو تأیید شد |

## آنچه الگوبرداری *نشد*

| آنچه حذف شد | چرا |
|-------------|-----|
| CLI BM25 روی CSV | php-zero-deپایگاه دانش را در مستندات نگه می‌دارد نه CSV قابل‌جستجو؛ اضافه‌کردن موتور جستجو در v0.0.۲ خارج از scope |
| ۱۵ dataset (۱۹۴ palette، ۷۴ font pairing) | دادهٔ حجمی؛ در صورت نیاز در نسخهٔ بعدی به‌عنوان `data/` جداگانه |
| manifests چند-پلتفرمی (skill.json، .claude/.cursor) | توزیع با ساختار خود ما |
| sibling-skill routing matrix (۷ skill خواهر) | ما یک skill واحد هستیم |

## گارد سازگاری

- `design-tokens.php` فقط از `ext-json` (هسته) استفاده می‌کند — هیچ وابستگی.
- هیچ کد از ui-ux-pro-max کپی نشده — فقط *ایدهٔ معماری توکن سه‌لایه + صدور
  دوگانه* الگوبرداری و در PHP خالص بازنویسی شد.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: ui-ux-pro-max-skill (MIT, Next Level Builder)
