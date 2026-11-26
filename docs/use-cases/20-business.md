# کاربرد: مدیریت کسب‌وکار و بازاریابی

> نسخه مهارت: **0.0.2** | کد دسته: **۱۴** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ کسب‌وکار، کاربردهای مدیریت روابط/منابع/بازاریابی: CRM، ERP، انبار،
فروش، ایمیل مارکتینگ، نظرسنجی، فرم‌ساز، کمپین، اتوماسیون. واحد دادهٔ
«مشتری/فرصت/کمپین» است؛ چالش اصلی یکپارچگی با فروش/مالی است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 14.1 | CRM آنلاین | مشتری + تماس + فرصت + مرحله | router, database, session, csrf, validator |
| 14.2 | ERP آنلاین | چندماژول (فروش/خرید/انبار/مالی) + یکپارچه | router, database, session, csrf, crypto |
| 14.3 | مدیریت انبار | کالا + موجودی + انبار + ورود/خروج | database, validator, session, csrf |
| 14.4 | مدیریت فروش | فرصت + مرحله + پیش‌بینی | router, database, session, view |
| 14.5 | مدیریت ارتباط با مشتری | 14.1 + تاریخچه تعامل + امتیاز | router, database, session, csrf |
| 14.6 | بازاریابی آنلاین | کمپین + کانال + هدف | router, database, session, csrf |
| 14.7 | ایمیل مارکتینگ | فهرست + قالب + ارسال + آمار باز | router, database, http-client, validator |
| 14.8 | نظرسنجی آنلاین | فرم + سؤال + جمع‌آوری + گزارش | router, database, session, csrf, validator |
| 14.9 | فرم‌ساز آنلاین | فرم پویا + ذخیره پاسخ + خروجی | router, database, validator, crypto |
| 14.10 | ثبت‌نام آنلاین | فرم + تأیید + partici pant | router, database, session, csrf, validator |
| 14.11 | مدیریت کاربران | نقش + سطح + تاریخچه | router, database, session, crypto |
| 14.12 | احراز هویت آنلاین | ورود + MFA + نشست + بازیابی | crypto, session, csrf, rate-limiter |
| 14.13 | امضای دیجیتال | 12.17 | crypto, database, session, logger |
| 14.14 | مدیریت وفاداری | امتیاز + سطح + پاداش + تبدیل | router, database, session, crypto |
| 14.15 | مدیریت کمپین | 14.6 + زمان‌بندی + A/B + آمار | router, database, session, csrf |
| 14.16 | اتوماسیون بازاریابی | قاعده + ماشه + action + گزارش | router, database, event-dispatcher, logger |

## الگوی مشترک

- **یکپارچگی:** CRM↔فروش↔مالی↔انبار با شناسهٔ مشتری یکتا؛ رخدادها
  (فروش، بازپرداخت) در `event-dispatcher.php` انتشار.
- **اتوماسیون:** قاعده «if مشتری مرحله X → ارسال ایمیل Y» با
  event-dispatcher + cron؛ نه در request.
- **ایمیل مارکتینگ:** double-opt-in + unsubscribe همیشه موجود؛ آمار باز با
  pixel (با رعایت حریم خصوصی — اختیاری).
- **امضای دیجیتال (14.13):** Ed25519 برای قرارداد/تأیید؛ کلید در custodian.
- **مدیریت کاربران (14.11):** نقش fine-grained + audit؛ نقش از RULES.json
  (نه هاردکد).

## ✅ Checklist

- [ ] شناسهٔ مشتری یکتا در همه ماژول
- [ ] رخدادها با event-dispatcher انتشار
- [ ] اتوماسیون با cron (نه request)
- [ ] ایمیل: double-opt-in + unsubscribe همیشه
- [ ] امضای دیجیتال با کلید در custodian
- [ ] audit log از تغییرات نقش

## ❌ Anti-patterns

- نقش هاردکد در کد (نه RULES.json)
- اتوماسیون در request (کند)
- ایمیل بدون unsubscribe (ضد اسپم)
- شناسهٔ مشتری ناهمگن بین ماژول

## ارجاع

- اتوماسیون: [`scaffolds/event-dispatcher.php`](../../scaffolds/event-dispatcher.php)
- دستهٔ مرتبط: ۴ (مالی)، ۳ (فروش)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/20-business-case-study.md`](case-studies/20-business-case-study.md).
