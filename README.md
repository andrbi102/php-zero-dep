# 🐘 php-zero-dep — مهارت PHP بدون وابستگی

> **نسخه: 0.0.1** | بر پایه سند `SPEC.md` | مجوز: MIT

مهارتی برای ایجنت‌های کدنویسی که کد PHP **آماده production** تولید می‌کند:
بدون Composer، بدون vendor، بدون کتابخانه ثالث — فقط PHP 8.2+ و افزونه‌های
هسته. فلسفه: **بازآفرینی آگاهانه چرخ** — اتولودر، روتر، DI، Event Bus همه
دست‌نویس و خودکفا.

## Quickstart

۱. دریافت بسته:
   ```bash
   git clone https://github.com/andrbi102/php-zero-dep.git
   ```
۲. این پوشه را به‌عنوان skill نصب کنید (یا SKILL.md را به ایجنت بدهید).
۳. از کاربر بخواهید پروژه PHP را توصیف کند — مهارت فعال می‌شود.
۴. اول `SKILL.md`، سپس در صورت نیاز `docs/<topic>/` و `scaffolds/`.

## راستی‌آزمایی بسته

```bash
php tests/smoke-test.php        # تست دود همه اسکلت‌ها (exit 0 = PASS)
php tests/security-audit.php    # ممیزی ایستا: توابع ممنوع، strict_types، composer
python3 scripts/verify_org.py   # شمارش‌های سازمانی از RULES.json (گارد ۴۸۲/۳۷۲)
bash scripts/check-links.sh     # بازبینی HTTP 200 لینک‌های کاتالوگ (سه‌ماهه)
```

## ساختار بسته

| مسیر | محتوا |
|------|-------|
| `SKILL.md` | راهنمای اجرای مهارت (نقطه ورود) |
| `AGENTS.md` / `RULES.json` / `MCP.md` | قواعد ایجنت، منبع حقیقت، پروتکل مشورت |
| `SPEC.md` | سند مبدأ کامل |
| `docs/` | ۱۷ دسته مرجع (معماری، امنیت، دیتابیس، PHP مدرن، مپ‌ها، org، …) + `catalog.md` |
| `scaffolds/` | ۲۵ اسکلت PHP خودکفا + `tools/` + `workers/` (۷۹ قرارداد) |
| `examples/` | ۹ مثال قابل اجرا (`php examples/01-router-demo.php`) |
| `tests/` | smoke-test + security-audit |
| `ADR/` | قالب MADR + ۱۵ تصمیم معماری |
| `council-log.md` / `lessons-learned.md` | صورت‌جلسات شورا و درس‌های آموخته |
| `scripts/` | بازبینی منابع + راستی‌آزمایی سازمانی |
| `evals/` | سناریوهای تست مهارت |

## مدل سازمانی (خلاصه)

۵ سطح | ۷۹ نقش | ۳۷۲ وظیفه اتمیک | ۴۲ مجوز | ۱۰ فاز | شورای ۶+۴ |
امنیت با وتوی مطلق | انتشار با ۵ امضا — شرح کامل: `docs/org/`
(شمارش‌ها همیشه از `RULES.json` — `python3 scripts/verify_org.py`)

## محدودیت‌های صادقانه

۱۶ محدودیت مستندشده در `docs/vision/01-limitations.md` — از نبود CV در PHP
خالص تا اجرا نشدن Xdebug روی FrankenPHP musl. یک مهارت خوب محدودیت‌های
خود را می‌شناسد.
