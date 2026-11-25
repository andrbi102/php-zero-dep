# ADR-0021: معماری توکن طراحی سه‌لایه (الگوبرداری از ui-ux-pro-max)

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Frontend Lead (T-015-03)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
خروجی PHP مهارت رابط کاربری هم تولید می‌کند. بدون سیستم توکن، رنگ‌ها و
فاصله‌های پراکنده و ناهمگن می‌شوند. ابزارهای موجود (Tailwind، Style
Dictionary) وابستگی اضافه می‌کنند (نقض قید ۱-۲).

## Decision (تصمیم)
معماری توکن سه‌لایه (primitive→semantic→component) از
nextlevelbuilder/ui-ux-pro-max-skill (MIT) الگوبرداری و در
`scaffolds/design-tokens.php` پیاده شد. یک resolver واحد، توکن‌ها را در سه
فرمت صادر می‌کند: PHP constants، JSON (W3C DTCG)، CSS vars. سند در
`docs/frontend/09-design-tokens.md`. نقش‌های رنگ semantic (Primary/On-Primary
/…/Ring) از همان منبع.

## Consequences (پیامدها)
+ رابط یکدست و teme‌پذیر بدون وابستگی.
+ صدور سه‌گانه از یک منبع (هیچ drift بین PHP/CSS/JSON).
+ تم‌پذیری در runtime با CSS vars (بدون rebuild).
− فقط یک نقشهٔ پایه در v0.0.۲؛ palette/font pairingهای آماده (۱۹۴/۷۴ در
  منبع) حذف شدند (دادهٔ volumeی — در صورت نیاز نسخهٔ بعدی).
− بدون موتور جستجوی BM25 روی CSV (پایگاه‌دانی در مستندات کافی است).
