# کاربرد: نشر و محتوای مکتوب

> نسخه مهارت: **0.0.2** | کد دسته: **۱** | کاتالوگ: [`00-catalog.md`](00-catalog.md)
> ADR: [0025](../../ADR/0025-use-case-taxonomy.md) | پروفایل پیشنهادی: **solo**

## مرور

دستهٔ نشر، همهٔ کاربردهایی که محتوای مکتوب را تولید/توزیع/آرشیو می‌کنند:
وبلاگ در ابعاد مختلف، مجله، خبرگزاری، خبرنامه، سایت نویسنده، پورتفولیو
محتوایی. واحد دادهٔ اصلی «مقاله/پست» است؛ تفاوت‌ها در مدل انتشار (تک‌نویسنده
یا چندنویسنده)، فرکانس، و نوع محتوا (متن/ویدئو) است.

## زیرمجموعه‌ها و نحوهٔ ساخت

| کد | کاربرد | نحوهٔ ساخت php-zero-dep | scaffolds کلیدی |
|----|--------|--------------------------|------------------|
| 1.1 | وبلاگ عمومی | MVC سبک: posts/comments/users؛ Markdown via view؛ RSS با ext-simplexml | router, view, database, csrf, cache |
| 1.2 | وبلاگ شخصی | همان 1.1 با تک‌نویسنده (مدیریت ساده) | router, view, database |
| 1.3 | وبلاگ تخصصی | 1.1 + دسته‌بندی موضوعی + جستجوی full-text (LIKE یا FTS5 در SQLite) | router, database, view, cache |
| 1.4 | وبلاگ شرکتی | 1.1 + چندنویسنده با نقش + پیش‌نمایش/برنامه انتشار | router, database, session, csrf, validator |
| 1.5 | وبلاگ خبری | 1.4 + اولویت/ساعت انتشار + تصویر شاخص (آپلود امن T-031) | router, database, session, csrf |
| 1.6 | وبلاگ ویدئویی | 1.1 با embed ویدئو (نه آپلود مستقیم — هاست ویدئو جدا) | router, view, database, validator |
| 1.7 | مجله آنلاین | 1.3 + شماره‌نامه/نشریه + صفحه‌بندی | router, database, view, cache |
| 1.8 | سایت خبری | 1.5 + چندنگار + دسته‌بندی + تیکر | router, database, session, csrf, cache |
| 1.9 | خبرگزاری | 1.8 + API عمومی خروجی JSON (REST) + کاتالوگ منابع | router, database, http-client, view |
| 1.10 | سایت تحلیلی | 1.3 + مقالات بلند + زیرنویس پانویس | router, database, view |
| 1.11 | خبرنامه | جمع‌آوری ایمیل (اعتبارسنجی double-opt-in) + ارسال با cron/mail | validator, database, crypto, session |
| 1.12 | بولتن خبری | 1.11 + قالب HTML ایمیل (escape داخل ایمیل) | validator, view, database |
| 1.13 | سایت نویسنده | پورتفولیو + بیوگرافی + فهرست آثار | router, view, database, cache |
| 1.14 | پورتفولیو محتوایی | 1.13 + گالری نمونه‌کار (تصویر/متن) | router, view, database, validator |
| 1.15 | آرشیو مقالات | فهرست قابل‌جستجو + دسته/برچسب + خروجی CSV | router, database, view |

## الگوی مشترک

- **مدل دادهٔ پایه:** `posts(id, title, slug, body, author_id, status, published_at)`،
  `comments(id, post_id, author, body, approved, created_at)`، `users(id, email, password_hash, role)`.
- **نشست نویسنده:** `session_regenerate_id()` پس از ورود؛ نقش نویسنده با
  проверка در هر action نوشتن.
- **کش:** صفحهٔ پست پس از انتشار با `scaffolds/cache.php` (فایل‌محور) کش
  می‌شود؛ در صورت ویرایش/کامنت، invalidate.
- **RSS/sitemap:** تولید با ext-simplexml، کش هفتگی.
- **مهاجرت از مرورگر:** اگر هاست SSH ندارد (T-061)، migration با توکن یک‌بارمصرف.

## ✅ Checklist

- [ ] slug از عنوان تولید و یکتاست (collusion با DB چک)
- [ ] کامنت با CSRF + rate limit + اعتبارسنجی + تأیید ناظر
- [ ] RSS/sitemap تولید و کش می‌شوند
- [ ] خروجی همه escape (`View::e()`) — حتی body Markdown (render سپس escape)
- [ ] مهاجرت از مرورگر (هاست اشتراکی) با توکن یک‌بارمصرف

## ❌ Anti-patterns

- فریمورک کامل برای یک وبلاگ پنج‌صفحه‌ای (درس: ۱۰-۲۰ فایل کافی است)
- آپلود ویدئو مستقیم روی هاست اشتراکی (embed از سرویس ویدئو)
- بدون کش — هر بازدید کوئری DB (N+1 در فهرست پست‌ها)

## ارجاع

- الگوی کوچک‌نگه‌داشتن: Chyrp Lite (BSD-3) در [`docs/catalog.md`](../catalog.md)
- امنیت فرم: [`docs/frontend/02-forms.md`](../frontend/02-forms.md)، [`docs/frontend/03-csrf-ui.md`](../frontend/03-csrf-ui.md)
- دستهٔ مرتبط: ۲ (آموزش — محتوای آموزشی)، ۱۷ (گالری)

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
