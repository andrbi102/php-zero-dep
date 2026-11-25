# AGENTS.md — قواعد ایجنت‌های php-zero-dep

> نسخه مهارت: **0.0.1** | مدل سازمانی: v2.0 | منبع حقیقت شمارش‌ها: `RULES.json`
>
> این فایل «قانون اساسی» ایجنت‌هاست: ۱۵ قاعده سازمانی (بخش A) و ۱۳ قید
> تولید کد (بخش B) که در هیچ فازی و هیچ پروفایلی تعطیل نمی‌شوند. متن
> انگلیسی بخش A صوری و الزامی است؛ جدول زیر آن، همان قواعد را با دلیل
> و مثال باز می‌کند تا در لحظه تصمیم تفسیر ثانیه‌ای لازم نباشد.

## A. قواعد سازمانی (۱۵ قاعده — الزامی)

```markdown
1. Only the roles required by the CURRENT phase (F1–F10) MUST be
   instantiated as workers, each with its own atomic task (T-NNN-NN)
   — profile "full" or "solo" (see org_model.profiles).
2. Workers are hired per atomic task and dissolved after approval.
3. Every worker MUST have: role_id, worker_id, task_id, scope,
   permissions, budget, reports_to, can_consult, must_consult, DoD.
4. The task dependency graph MUST be a DAG.
5. Council Security has ABSOLUTE veto.
6. Council Architect has veto on architecture violations.
7. Council Quality has veto on quality gate failures.
8. Council Performance has veto on performance regressions.
9. Council Compliance has veto on license/ethics violations.
10. No release without 5 signatures (4 council + Release Manager).
11. Every consultation MUST be logged in consultation/.
12. Every Council session MUST be logged in council-log.md.
13. Every ADR MUST have approved_by signature from Council Architect.
14. Separation of duties (SoD-1..5) MUST be enforced.
15. A worker may NOT exceed its declared scope.
```

### همان ۱۵ قاعده — با دلیل و مثال

| # | قاعده به زبان کار | چرا وجود دارد | مثال نقض |
|---|-------------------|----------------|-----------|
| ۱ | فقط نقش‌های فاز جاری فعال می‌شوند | ایجنتِ بی‌قاعده همه‌چیز را یک‌جا فعال می‌کند و context پر می‌شود | فعال‌کردن نقش‌های F8 در میانه F4 |
| ۲ | Worker به‌ازای وظیفه اتمیک استخدام و پس از تأیید منحل می‌شود | Worker مانده = scope creep و پاسخ‌گویی مبهم | نگه‌داشتن یک Worker برای سه وظیفه پشت‌سرهم |
| ۳ | هر Worker ده شناسه/محدودیت الزامی دارد (role_id تا DoD) | بدون این‌ها «چه کسی، با چه مجوزی، تا کجا» مبهم می‌ماند | Worker بدون `budget` و `DoD` |
| ۴ | گراف وابستگی وظایف DAG است (بدون چرخه) | چرخه = بن‌بست: A منتظر B و B منتظر A | `T-049-01` وابسته به `T-050-01` و برعکس |
| ۵ | وتوی امنیتی مطلق است | تنها گاردی که زیر هیچ فشار زمانی/سازمانی خم نمی‌شود | عبور از ردِ XSS «چون ددلاین نزدیک است» |
| ۶ | وتوی معماری برای تخلفات معماری | تا پروژه به کد گلوله‌قفران تبدیل نشود | رندر HTML درون Model |
| ۷ | وتوی کیفیت برای شکست درگاه‌های کیفیت | خروجیِ تست‌قرمز نباید منتشر شود | انتشار با smoke-test قرمز |
| ۸ | وتوی عملکرد برای رگرسیون کارایی | رشد تدریجی کندی، دیر دیده می‌شود | پذیرش کوئری N+1 در فهرست محصولات |
| ۹ | وتوی انطباق برای تخلف لایسنس/اخلاق | یک فایل GPL در خروجی MIT، کل پروژه را آلوده می‌کند | کپی کد GPL در خروجی بدون ثبت |
| ۱۰ | انتشار بدون ۵ امضا ممنوع | انتشار پنج‌لایه، «تصادفی بودن» را حذف می‌کند | tag زدن با امضای تنها RM |
| ۱۱ | هر مشورت در consultation/ ثبت می‌شود | مشورت ثبت‌نشده = تصمیم بی‌ریشه در آینده | تصمیم رمزنگاری بدون سند مشورت |
| ۱۲ | هر جلسه شورا در council-log.md ثبت می‌شود | رأی بی‌صورت‌جلسه قابل بازخوانی نیست | ردِ انتشار بدون ردیف در لاگ |
| ۱۳ | هر ADR امضای Council Architect دارد | تصمیم معماری بی‌امضا فقط یک نظر است | ADR انتخاب CQRS بدون امضا |
| ۱۴ | SoD-1..5 اعمال می‌شود | سازنده و تأییدکننده یک‌نفر = خودتأییدی | نویسنده کد، بازبین همان کد |
| ۱۵ | Worker از scope قراردادش فراتر نمی‌رود | «همین‌جا هم دست می‌زنم» = باگ در کد دیگران | Worker مهاجرت دیتابیس که CSS را هم «مرتب» می‌کند |

## B. قیدهای سخت تولید کد (از پرامپت اصلی SPEC بخش ۲)

هر قید با «چرا» و جفت مثال درست/غلط — جزئیات بیشتر و مطالعه موردی در `SKILL.md` بخش ۲.

1. هرگز `composer.json` تولید نکن و به پکیج Composer ارجاع نده.
   *غلط:* `require 'vendor/autoload.php';` — *درست:* `require_once __DIR__.'/src/autoloader.php';` (از `scaffolds/autoloader.php`)
2. هرگز کتابخانه ثالث استفاده نکن — فقط PHP 8.2+ هسته و افزونه‌های مجاز.
   *تشخیص:* «آیا کد production بدون آن اجرا می‌شود؟» اگر نه، وابستگی است (`docs/vision/02-pure-php-boundary.md`).
3. هر فایل با `declare(strict_types=1);` و PHPDoc شامل `@package`, `@version`, `@security-level`.
   *غلط:* خط اول فایل `<?php` و بلافاصله کد — *درست:* declare + PHPDoc سه‌فیلدی، سپس کد.
4. هر کلاس تک‌فایل و خودکفا.
   *غلط:* کلاسی که به `Helper::fmt()` از فایل دیگری وابسته است بدون autoload مشخص — *درست:* وابستگی از طریق `scaffolds/autoloader.php` یا تزریق صریح.
5. هر کوئری PDO Prepared Statement.
   *غلط:* `"SELECT * FROM users WHERE id=$id"` — *درست:* `$db->run('SELECT ... WHERE id=?', [$id]);`
6. هر خروجی escape شود (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`).
   *غلط:* `echo $user->bio;` — *درست:* `echo View::e($user->bio);`
7. کوکی نشست: HttpOnly + Secure + SameSite=Strict.
   *درست:* `session_set_cookie_params(['httponly'=>true,'secure'=>true,'samesite'=>'Strict']);` (پیاده‌سازی آماده: `scaffolds/session.php`)
8. هر فرم توکن CSRF امضاشده HMAC.
   *درست:* توکن از `scaffolds/csrf.php`؛ سمت سرور `hash_equals` — نه `==` (حمله timing).
9. هش رمز: `password_hash(PASSWORD_ARGON2ID)`.
   *غلط:* `md5($pass)`, `sha1($pass)`, `hash('sha256', $pass)` — *درست:* Argon2id + `password_verify()` + `password_needs_rehash()` برای مهاجرت تدریجی.
10. هیچ شکست خاموشی: لاگ یا rethrow با context.
    *غلط:* `@file_get_contents(...)` یا `if(!$r){}` — *درست:* try/catch با لاگ زمینه‌دار یا rethrow.
11. Event Dispatcher / Command Bus / Pipeline دست‌نویس (از scaffolds).
    *چرا:* این سه، «فریمورک درون فریمورک»اند؛ scaffold تست‌شده وجود دارد — بازنویسی ممنوع.
12. هدرهای امنیتی: CSP، HSTS، X-Content-Type-Options، X-Frame-Options، Referrer-Policy.
    *درست:* تنظیم در middleware ورودی (`scaffolds/pipeline.php`) نه در هر کنترلر.
13. توابع ممنوع: `eval()`, `exec()`, `shell_exec()`, `system()`, `passthru()`, `proc_open()`, `popen()` — مگر با مستندسازی صریح و sandbox.
    *استثنا:* فقط ابزار dev با ADR و sandbox؛ کد production هرگز. `php tests/security-audit.php` این قید را ایستا می‌گیرد.

## C. قاعده منابع خارجی (بدون توهم API)

> هرگاه ایجنت قصد تولید کد برای کتابخانه خارجی دارد، ابتدا `resolve-library-id`
> (مگر شناسه `/owner/repo` داده شده باشد)، سپس `query-docs` با پرسش مشخص و زبان PHP.
> اگر Context7 در دسترس نیست، اعلام محدودیت کن و بر دانش خود تکیه نکن.

**سناریوی کامل:** کاربر می‌خواهد پرداخت با «زرین‌پل» اضافه شود.
(۱) API خارجی است → مشورت اجباری. (۲) `resolve-library-id` با «zarinpal».
(۳) اگر شناسه‌ای برنگشت، محدودیت را اعلام کن: «کتابخانه‌ای برای درگاه
پرداخت بیرونی در دسترس مستندات نیست؛ توصیف مستند رسمی را بده یا از
HTTP API مستند آن استفاده می‌کنیم.» (۴) اگر برگشت، `query-docs` با
پرسش مشخص («ایجاد پرداخت در PHP با نسخه X، پارامترها و خطاها») و فقط
بر اساس پاسخ واقعی کد بده. (۵) ارتباط HTTP فقط از `scaffolds/http-client.php`.
گردش‌کار کامل و حالت‌های خطا: `MCP.md`.

## D. نسخه PHP

| نسخه | وضعیت | قاعده |
|------|--------|-------|
| 8.2 | EOL در 2026-12-31 | فقط اگر کاربر صریحاً گفت و محدودیت ثبت شد |
| **8.3** | پشتیبانی‌شده | **پیش‌فرض وقتی نامشخص است** |
| 8.4 / 8.5 | فعال | فقط با تأیید صریح کاربر |
| 8.6+ | شناختی | تشخیص از رفتار runtime: `docs/modern-php/05-php-8.6-detection.md` |

*چرا پیش‌فرض؟* کد روی نسخه EOL از روز اول بدهی امنیتی است؛ 8.3 نزدیک‌ترین
نسخه پشتیبانی‌شده به اکثر هاست‌های رایج است.

## E. مراجع

- ساختار و شمارش‌ها: `RULES.json` → `org_model` (منبع واحد؛ شمارش‌ها از آن تولید می‌شوند — درس L-001)
- شرح کامل مدل سازمانی: `docs/org/` (نقشه خواندن: `docs/org/00-index.md`)
- قرارداد Worker: `scaffolds/workers/_template.md` (با نمونه تکمیل‌شده)
- گردش کار کامل و مطالعه موردی: `SKILL.md` بخش ۳
- چک‌لیست پذیرش: `SKILL.md` بخش درگاه‌های کیفیت + `SPEC.md` بخش ۱۴
- مشورت مستندات: `MCP.md`
