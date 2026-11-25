# ADR-0022: ممیزی امتیازی skill با قرارداد JSON (الگوبرداری از skillspector)

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Council Quality (T-002-01)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
سه اسکریپت ممیزی موجود (`verify_org.py`، `security-audit.php`،
`smoke-test.php`) خروجی متنی و exit code ساده دارند. برای CI یکنواخت و
گزارش‌دهی، یک نمرهٔ واحد قابل‌مقایسه + قرارداد JSON پایدار + انضباط
completeness لازم بود. skillspector این انضباط را دارد.

## Decision (تصمیم)
`scripts/skill_score.py` (الگوبرداری از nvidia/skillspector، Apache-2.0)
سه ممیزی موجود را متحد می‌کند و: نمرهٔ ریسک ۰-۱۰۰ وزنی
(CRITICAL+50/HIGH+25/MEDIUM+10/LOW+5) + توصیهٔ
`SAFE/CAUTION/DO_NOT_INSTALL`؛ بودجهٔ ingest fail-closed (byte ceiling)؛
completeness ledger (exit ۱ اگر ناقص)؛ baseline suppression (URLهای flaky)؛
قرارداد JSON پایدار + exit ۰/۱/۲. فقط stdlib Python — بدون وابستگی.

## Consequences (پیامدها)
+ یک نمرهٔ واحد قابل‌مقایسه برای CI و گزارش‌دهی.
+ انضباط completeness (تحلیل ناتمام = شکست، نه فقط یافته).
+ قرارداد JSON پایدار برای اتوماسیون.
− فقط ممیزی skill خود ما (نه skillهای نصب‌شدهٔ خارجی — مجموعهٔ الگوی
  امنیتی skillspector حذف شد چون `security-audit.php` پوشش کافی دارد).
− بدون LLM (ایستا) — قابل‌تکرار اما محدودتر از تحلیل semantic.
