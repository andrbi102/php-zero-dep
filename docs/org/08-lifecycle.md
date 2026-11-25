# چرخه عمر Worker (۸ گام) و قرارداد ۸ بخشی

> نسخه مهارت: **0.0.2** | قالب قرارداد: scaffolds/workers/_template.md (با نمونه تکمیل‌شده)

## هشت گام — هر گام چه معنایی دارد

```
۱. Requirement  → نقش مورد نیاز مشخص می‌شود
۲. Hiring       → worker-template.md پر می‌شود
۳. Contract     → scope + permissions + budget + DoD
۴. Onboarding   → RULES.json و docs/* خوانده می‌شود
۵. Execution    → وظیفه اتمیک انجام می‌شود
۶. Review       → Code Reviewer تأیید/رد می‌کند
۷. Dissolution  → Worker منحل، خروجی آرشیو
۸. Retrospective→ ثبت در lessons-learned.md
```

| گام | خروجی ملموس | اگر حذف شود… |
|-----|-------------|----------------|
| ۱ Requirement | نام نقش + Task ID | Worker بدون کارِ مشخص استخدام می‌شود |
| ۲ Hiring | قرارداد پرشده | مجوز و scope مبهم می‌ماند |
| ۳ Contract | قرارداد تأییدشده | SoD و Budget بی‌حساب می‌شوند |
| ۴ Onboarding | زمینه خوانده‌شده | Worker با فرض‌های غلط شروع می‌کند |
| ۵ Execution | artifact (کد/سند) | — |
| ۶ Review | تأیید/رد بازبین | کد بازبینی‌نشده (نقض SoD-5) |
| ۷ Dissolution | آرشیو + آزادسازی | Worker سرگردان باقی می‌ماند (نقض قاعده ۲) |
| ۸ Retrospective | ردیف در lessons-learned.md | درسِ همان اشتباه دوباره پرداخت می‌شود |

## نمونه کامل — استخدام Router Developer (T-049) برای «تعریف مسیرهای وبلاگ»

| گام | اجرای مشخص |
|-----|------------|
| ۱ | فاز F4، نیاز: مسیرهای `/`, `/post/{slug}`, `/tag/{tag}` → نقش T-049 (سطح ۳)، وظیفه اتمیک T-049-01 |
| ۲ | قرارداد از `scaffolds/workers/T-049-router-developer.md` برداشته و برای این اجرا کامل می‌شود: worker_id = `W-run024-T-049-01` |
| ۳ | scope: فقط فایل `routes.php` + ثبت در container؛ مجوزها: `write-code`, `read-all`؛ DoD: «هر سه مسیر، پارامتر پاک‌شده، تست دود سبز» |
| ۴ | Onboarding: `scaffolds/router.php` (کپی، نه بازنویسی) + قیدهای بخش B در `AGENTS.md` + ADR معماری MVC سبک |
| ۵ | اجرا: `routes.php` با `declare(strict_types=1)` + PHPDoc سه‌فیلدی |
| ۶ | Code Reviewer (T-074) بازبینی می‌کند: مسیر `{slug}` باید با regex محدود شود (path traversal) — یک یافته medium؛ اصلاح و تأیید |
| ۷ | خروجی آرشیو؛ Worker منحل — T-049 برای وظیفه بعدی دوباره «استخدام» می‌شود |
| ۸ | Retrospective: «فرمت slug از ابتدا در DoD قید شود» → اگر درسِ تازه‌ای بود، در lessons-learned.md ثبت |

## قرارداد ۸ بخشی

**Identity، Task (Atomic)، Authority، Relationships، Budget، DoD، Escalation، Lifecycle** —
شرح هر بخش و راهنمای پرکردن + نمونه تکمیل‌شده: `scaffolds/workers/_template.md`.
نمونه‌های واقعی هر ۷۹ نقش: `scaffolds/workers/T-*.md`.

## نکته‌های اجرایی

- **یک وظیفه = یک استخدام:** Worker پس از تأیید منحل می‌شود؛ وظیفه بعدی، استخدامِ تازه است (قاعده ۲ AGENTS.md).
- **Budget یعنی تمرکز:** هم‌زمان فقط یک وظیفه اتمیک — Worker چندکاره، scope creep است.
- **Review توسط دیگری:** سازنده هرگز بازبینِ خودش نیست (SoD-5 در `11-sod.md`).
- **solo هم همین چرخه:** پروفایل سبک، چرخه را کوتاه‌تر اجرا می‌کند نه حذف‌شده.

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
