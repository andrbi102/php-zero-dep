# کاربرد: سلامت و سبک زندگی

> نسخه مهارت: **0.0.2** | کد دسته: **۱۰** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **full**

## مرور

دستهٔ سلامت، کاربردهای مرتبط با دادهٔ سلامتی: نوبت پزشکی، مشاوره، داروخانه،
پرونده پزشکی، تناسب، رژیم، پایش. **این دسته حساس‌ترین دادهٔ شخصی را
پردازش می‌کند** — رمزنگاری در حال استراحت الزامی؛ وتوی T-001 مطلق.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت | scaffolds کلیدی |
|----|--------|-----------|------------------|
| 10.1 | سلامت آنلاین | پورتال + پرونده + نوبت | router, database, session, crypto, csrf |
| 10.2 | نوبت‌دهی پزشکی | 8.3 (slot) + پزشک + تخصص | router, database, session, csrf, validator |
| 10.3 | مشاوره آنلاین | نوبت + چت/ویدئو (embed) + یادداشت | router, database, session, crypto |
| 10.4 | داروخانه آنلاین | کاتالوگ + نسخه + تحویل | router, database, session, csrf, validator |
| 10.5 | پرونده پزشکی | ثبت رمزنگاری‌شده + دسترسی نقش‌بندی + audit | database, crypto, session, logger |
| 10.6 | تناسب اندام | برنامه + ثبت تمرین + پیشرفت | router, database, session, view |
| 10.7 | رژیم غذایی | برنامه + کالری + ثبت وعده | router, database, session, validator |
| 10.8 | ورزش آنلاین | ویدئو تمرین + برنامه + پیگیری | router, database, view, cache |
| 10.9 | مدیتیشن | جلسه + صوت (embed) + آمار | router, database, view |
| 10.10 | پایش خواب | ثبت داده + نمودار + هدف | router, database, view |
| 10.11 | پایش علائم | ثبت فشار/قند + نمودار + هشدار | router, database, view, crypto |
| 10.12 | واکسیناسیون | ثبت + یادآوری + گواه | router, database, session, crypto |
| 10.13 | سلامت روان | مشاوره (10.3) + mood tracking | router, database, session, crypto |
| 10.14 | مشاوره تغذیه | برنامه + پیگیری + چت | router, database, session, validator |

## الگوی مشترک

- **رمزنگاری در حال استراحت (الزامی):** هر دادهٔ سلامتی با libsodium
  (`crypto.php`) رمزنگاری؛ کلید در key custodian (T-026) — نه در کد، نه در DB.
- **دسترسی نقش‌بندی:** patient/doctor/nurse/admin؛ هر دسترسی در audit log
  ثبت (چه، چه زمانی، چه را خواند).
- **audit log:** لاگ تغییرنشدنی (append-only) از دسترسی به پرونده.
- **یادآوری:** cron/email/SMS (نه در request).
- **پایش:** دادهٔ سری زمانی؛ نمودار با render سمت کلاینت (Chart.js embed)؛
  داده از JSON endpoint.
- **data minimization:** فقط دادهٔ لازم را جمع کن؛ نه «همه چیز برای بعد».

## ✅ Checklist

- [ ] دادهٔ سلامتی رمزنگاری‌شده در حال استراحت (libsodium)
- [ ] کلید در key custodian (نه در کد/DB)
- [ ] دسترسی نقش‌بندی + audit log تغییرنشدنی
- [ ] data minimization (فقط لازم)
- [ ] یادآوری خارج از request
- [ ] وتوی T-001 در هر تصمیم رمزنگاری/دسترسی

## ❌ Anti-patterns

- دادهٔ سلامتی در plaintext در DB
- کلید رمزنگاری در کد یا DB
- audit log قابل ویرایش
- جمع‌آوری بیش از حد داده (عدم minimization)

## ارجاع

- رمزنگاری: [`docs/elliptic-curve/`](../elliptic-curve/)، [`scaffolds/crypto.php`](../../scaffolds/crypto.php)
- حریم خصوصی: [`docs/security/04-session-security.md`](../security/04-session-security.md)
- دستهٔ مرتبط: ۸ (نوبت)، ۱۱ (خدمات دولتی سلامت)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`

## مطالعهٔ موردی کامل

یک مطالعهٔ ۱۰-فازی concreet (کارفرما + scaffoldها + امنیت + humanizer + skillspector) برای این دسته: [`case-studies/16-health-case-study.md`](case-studies/16-health-case-study.md).
