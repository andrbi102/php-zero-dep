# ۰۵ — مرجع: nvidia/skillspector

> نسخه مهارت: **0.0.2** | لایسنس مرجع: **Apache-2.0** | زبان: Python 3.12+
> URL: <https://github.com/nvidia/skillspector>
> ADR متناظر: [0022](../../ADR/0022-scored-skill-self-audit.md)

## هویت

**اصلاح مهم:** skillspector یک اعتبارسنج ساختاری/schema نیست — یک
**اسکنر امنیتی/آسیب‌پذیری برای skillهای ایجنت** است که پاسخ «آیا این skill
امن نصب است؟» را می‌دهد. ۷۱ الگوی آسیب‌پذیری در ۱۷ دسته را شناسایی می‌کند.

## ساختار (مجموعهٔ مرتبط)

```
skillspector/
├── src/skillspector/
│   ├── cli.py, cli_entry.py, graph.py, models.py, references.py
│   ├── nodes/                  # گره‌های LangGraph
│   │   └── analyzers/          # ~۴۰ ماژول: static_*, behavioral_*, mcp_*, semantic_*
│   ├── providers/              # ۱۳ backend LLM
│   └── yara_rules/             # agent_skills, webshells, cryptominers, hacktools
├── contrib/batch_scan/         # اسکن موازی مجموعه
└── tests/, scripts/, docs/
```

## آنچه آموختیم

۱. **شناسهٔ یافته دسته‌بندی‌شده + وزن شدت → نمرهٔ ریسک ۰-۱۰۰:**
   CRITICAL+50/HIGH+25/MEDIUM+10/LOW+5 (اسکریپت‌ها ×۱.۳). نتیجه: یک نمرهٔ
   واحد قابل‌مقایسه + توصیهٔ `SAFE/CAUTION/DO_NOT_INSTALL`.
۲. **بودجهٔ ingest fail-closed:** `INGEST_MAX_BYTES`، `MAX_FILE_BYTES` (۱MB به‌ازای فایل)،
   سقف zip-member — جلوی zip-bomb و فایل‌های غول‌پیکر.
۳. **completeness ledger + `--fail-on-incomplete`:** خروجی exit ۱ اگر پوشش
   ناقص بود (نه فقط اگر یافته داشت). «تحلیل ناتمام» یک شکست است.
۴. **baseline suppression:** فایل baseline با glob/fingerprint تا URLهای
   شناخته‌شدهٔ flaky فصلی را fail نکنند.
۵. **قرارداد JSON پایدار + SARIF:** یک شکل JSON مشترک
   (`{risk_assessment, issues[], analysis_completeness, metadata}`) و گزینهٔ
   SARIF تا CI یکنواخت gate کند. کدهای خروجی: ۰ (score≤۵۰ & gates pass)،
   ۱ (score>۵۰ / `--fail-on-findings` / `--fail-on-incomplete`)، ۲ (خطا).
۶. **schema projection (نه اعمال):** frontmatter را فقط برای یک manifest
   محدود parse می‌کند (`name, description, version, triggers, permissions,
   allowed-tools, parameters` — همگی اختیاری)؛ YAML مخرب = «تحلیل ناقص» نه
   fail سخت.

## آنچه الگوبرداری شد (با artifact)

| الگو | منبع | artifact ما |
|------|------|-------------|
| نمرهٔ ریسک وزنی ۰-۱۰۰ + توصیه | README §Vulnerability Patterns | `scripts/skill_score.py` — امتیازدهی وزنی + `SAFE/CAUTION/DO_NOT_INSTALL` |
| بودجهٔ ingest fail-closed (byte ceiling) | `INGEST_MAX_BYTES`, `MAX_FILE_BYTES` | `skill_score.py` بررسی حجم فایل‌های scaffold |
| completeness ledger + fail-on-incomplete | README §CLI Options | `skill_score.py` گزارش `completeness` + exit ۱ اگر ناقص |
| baseline suppression | `.skillspector-baseline.example.yaml` | الگوبرداری برای `scripts/check-links.sh` (URLهای شناخته‌شدهٔ flaky) — مستند در ADR-0022 |
| قرارداد JSON پایدار + کدهای خروجی ۰/۱/۲ | README §Machine-readable output | `skill_score.py --format json` با همان شکل + exit ۰/۱/۲ |
| اتحاد سه‌گانهٔ ما | — | `skill_score.py` فراخوانی `verify_org.py` (org) + `security-audit.php` (امنیت) + `smoke-test.php` (رفتار) را متحد می‌کند |

## آنچه الگوبرداری *نشد*

| آنچه حذف شد | چرا |
|-------------|-----|
| ۷۱ الگوی آسیب‌پذیری + ۴۰ آنالایزر AST/taint/YARA | ما skill خود را ممیزی می‌کنیم نه skillهای نصب‌شدهٔ خارجی؛ `tests/security-audit.php` موجود پوشش کافی برای قواعد ما دارد |
| ۱۳ backend LLM | ممیزی ما ایستا است (بدون LLM) — قابل‌تکرار و ارزان |
| LangGraph pipeline | یک اسکریپت Python کافی است |
| MCP least-privilege/tool-poisoning analyzers (LP1-LP4, TP1-TP4) | ما MCP server خود را در `scaffolds/mcp-server.php` با اعتبارسنجی ورودی الزامی داریم (`MCP.md` بخش ۴) |

## گارد سازگاری

- `skill_score.py` فقط `verify_org.py` موجود + شمارش فایل/حجم را فراخوانی
  می‌کند — هیچ وابستگی جدید Python. فقط stdlib.
- ارزش انتقالی skillspector برای ما **انضباط gating** (کدهای خروجی،
  completeness، baseline، JSON امتیازی) بود، نه مجموعهٔ الگوهای امنیتی
  (که `tests/security-audit.php` پوشش می‌دهد).

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: skillspector (Apache-2.0, NVIDIA)
