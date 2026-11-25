# قالب ADR (MADR)

> نسخه مهارت: **0.0.2** | قالب تکمیل‌شده: `ADR/0000-template.md` | ۱۴ نمونه واقعی: پوشه ریشه `ADR/`

## قالب

```markdown
# ADR-NNNN: عنوان تصمیم

- وضعیت: Proposed | Accepted | Superseded by ADR-XXXX
- تاریخ: YYYY-MM-DD
- تصمیم‌گیرنده: {نقش} ({T-NNN})
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
چه مشکل یا نیرویی این تصمیم را ضروری کرد؟

## Decision (تصمیم)
چه چیزی تصمیم گرفتیم — با «خواهیم» بیان شود.

## Consequences (پیامدها)
مثبت‌ها، منفی‌ها، خنثی‌ها — صادقانه.
```

## نمونه تکمیل‌شده — ADR کوتاه

```markdown
# ADR-0001: معماری MVC سبک برای وبلاگ شخصی

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Chief Architect (T-010)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
وبلاگ روی هاست اشتراکی (بدون SSH، PHP 8.3، MySQL) اجرا می‌شود؛
دامنه ساده (پست/نظر) است و تیم تک‌نفره است. قید صفر-وابستگی،
فریمورک‌های آماده را رد می‌کند.

## Decision (تصمیم)
MVC سبک خواهیم داشت: `scaffolds/router.php` نقش Controller ورودی،
`scaffolds/view.php` با escape خودکار نقش View، و Model فقط از طریق
`scaffolds/database.php` (PDO Prepared) با دیتابیس حرف می‌زند.

## Consequences (پیامدها)
+ شروع سریع، کپی سه scaffold، فهم آسان برای نگهدارنده بعدی
− منطق رشد‌یافته باید به Service جدا منتقل شود (ریسک «Controller چاق»)
− بدون لایه Domain جداگانه — برای این دامنه پذیرفته شده
```

**قواعد ADR (قاعده ۱۳ AGENTS.md):** هر ADR بدون امضای `approved_by`
رسمی نیست؛ هر تغییر معماری مهم = ADR جدید یا Supersede صریح؛ ADR هرگز
حذف نمی‌شود — سوپرساید می‌شود.

نمونه‌های تکمیل‌شده: پوشه ریشه `ADR/`
