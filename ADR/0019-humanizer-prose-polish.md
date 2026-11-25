# ADR-0019: پالایش نثر با کاتالوگ «بوی AI» (الگوبرداری از humanizer)

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Frontend Lead (T-015-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
خروجی مهارت شامل رشته‌های انسانی‌خواندنی است (hero، CTA، tooltip، button
label). این رشته‌ها اغلب «بوی AI» می‌گیرند: کلمات بادکنکی، تثلث‌های
مکانیکی، disclaimerهای chatbot. بازبین F5 ابزاری برای شناسایی **به‌نام**
این الگوها نداشت.

## Decision (تصمیم)
کاتالوگ ۲۶-الگویی از blader/humanizer (MIT) در شش گروه (inflation، rhythm،
formatting، chat-residue، re-explaining) تطبیق و در `scaffolds/humanizer.php`
به‌عنوان **آشکارساز مبتنی‌بر-قاعده (بدون LLM)** پیاده می‌شود: نمرهٔ ۰-۱۰۰،
قاعدهٔ هم‌وقوعی (تنها tell ضعیف = نادیده)، و claim-check (fact باید زنده
بماند). سند کاتالوگ در `docs/frontend/08-humanizer-prose.md`.

## Consequences (پیامدها)
+ بازبینی F5 ابزار ایستا برای «بوی AI» دارد (قابل‌ممیزی، بدون LLM).
+ قاعدهٔ هم‌وقوعی جلوی over-correction مکانیکی را می‌گیرد.
+ claim-check جلوی گم‌شدن fact در بازنویسی را می‌گیرد.
− آشکارساز فقط tellها را می‌یابد؛ بازنویسی واقعی بر عهده ایجنت/انسان.
− در solo پروفایل، اغلب برای پروژه‌های کوچک بیش از حد است؛ فعال اما اختیاری
  در درگاه کیفیت.
