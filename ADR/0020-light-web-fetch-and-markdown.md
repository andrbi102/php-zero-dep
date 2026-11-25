# ADR-0020: واکشی وب سبک و HTML→markdown (الگوبرداری از firecrawl)

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Integration Lead (T-016-02)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
برخی کاربردها (مقایسه قیمت 3.7، خلاصه‌سازی URL 13.12، crawler کاتالوگ) به
واکشی وب و تبدیل HTML به متن/markdown تمیز نیاز دارند. firecrawl این را به‌خوبی
حل کرده اما AGPL-3.0 (انتقالی) است و زیرساخت سنگین (CDP، Postgres queue) دارد
که در PHP خالص بدون وابستگی ناممکن است.

## Decision (تصمیم)
فقط *الگوی معماری* firecrawl الگوبرداری شد (واترفال موتور، حذف عناصر
ناخواسته، robots.txt، فیلتر لینک، charset) و در `scaffolds/web-fetch.php`
با ext-curl + ext-dom + ext-mbstring بازنویسی شد. **هیچ کد از firecrawl کپی
نشده** (AGPL انتقالی — کپی‌کردن کل خروجی را آلوده می‌کرد). مثال در
`examples/10-web-fetch-demo.php`.

## Consequences (پیامدها)
+ قابلیت واکشی+پاکسازی سبک، بدون وابستگی، قابل‌استفاده در چند کاربرد.
+ robots.txt و anti-hotlink رعایت می‌شود (صداقت مهندسی).
+ بدون نقض لایسنس (فقط الگو، نه کد).
− فقط fetch ساده؛ سایت‌های JS-gated (SPA) پشتیبانی نمی‌شوند (هشدار صریح).
− بدون CDP browser pool — برای سایت‌های پویا، اعلام محدودیت.
− rate-limit ساده per-host (token-bucket با usleep)، نه توزیع‌شده.
