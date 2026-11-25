# ۰۳ — مرجع: firecrawl/firecrawl

> نسخه مهارت: **0.0.2** | لایسنس مرجع: **AGPL-3.0** (server) / **MIT** (SDKها/UI)
> زبان: TypeScript/Node (server)، Go (html-to-md)، Rust (native)
> URL: <https://github.com/firecrawl/firecrawl>
> ADR متناظر: [0020](../../ADR/0020-light-web-fetch-and-markdown.md)

## هویت

Firecrawl «زیرساخت دانش» است: وب‌سایت‌ها را به markdown/JSON تمیز برای
ایجنت‌های LLM تبدیل می‌کند — scrape، crawl، map، search، extract، interact،
agent. خروجی: markdown، HTML، rawHTML، links، JSON، screenshots. یک SDK PHP
رسمی دارد (که ما *استفاده نمی‌کنیم* — فقط الگوی معماری را الگوبرداری می‌کنیم).

## ساختار (مجموعهٔ مرتبط)

```
firecrawl/
├── apps/api/   # سرور TS (~۱۱۳۹ فایل .ts)
│   ├── src/scraper/scrapeURL/
│   │   ├── engines/index.ts          # buildFallbackList — واترفال موتور
│   │   ├── lib/removeUnwantedElements.ts  # حذف header/footer/nav/aside/.ad/.cookie
│   │   ├── lib/html-to-markdown.ts   # تبدیل via Go service یا FFI
│   │   └── shouldCheckRobots.ts      # invariant lockdown
│   ├── src/scraper/WebScraper/crawler.ts  # filterLinks + DenialReason enum
│   └── src/lib/robots-txt.ts         # fetchRobotsTxt / isUrlAllowedByRobots
├── apps/go-html-to-md-service/       # converter.go (github.com/firecrawl/html-to-markdown)
└── apps/api/src/services/worker/     # NuQ queue (Postgres/FDB) + scrape-worker
```

## آنچه آموختیم

۱. **واترفال موتور (engine waterfall):** یک URL با موتورها به‌ترتیب امتحان
   می‌شود: index cache → fetch (undici) → fire-engine/chrome-cdp → specialty
   (pdf/document/image/wikipedia) — تا یکی موفق شود؛ در غیر این صورت
   `NoEnginesLeftError`.
۲. **حذف عناصر ناخواسته:** فهرست ثابت tags + class/id substrings
   (`header, footer, nav, aside, .sidebar, .ad, .cookie, .social, .breadcrumbs,
   [aria-label="Cookie consent"]`) با Cheerio حذف می‌شوند قبل از استخراج متن.
۳. **HTML→markdown زیرمجموعه:** h1-h6، p، ul/ol/li، a[href]، pre/code،
   blockquote، strong/em با پیمایش DOMDocument — ~۱۵۰ خط PHP خالص برای
   صفحات مقاله‌ای کافی است.
۴. **robots.txt:** fetch `/robots.txt`، parse User-agent/Disallow/Allow/Crawl-delay،
   glob-match path. invariant: در lockdown safe-mode هرگز robots fetch نمی‌شود.
۵. **فیلتر لینک با DenialReason:** maxDepth (به تعداد path-segment)،
   include/exclude regex، same-domain-only، skip social/file-ext/#/mailto.
۶. **charset:** header `charset=` → `<meta charset>` → utf8 fallback (در PHP:
   `mb_convert_encoding` بعد از sniff).

## آنچه الگوبرداری شد (با artifact)

| الگو | منبع (مسیر فایل) | artifact ما |
|------|------------------|-------------|
| engine waterfall (fetch first، فقط اگر JS-gated هشدار) | `scrapeURL/engines/index.ts:350` | `WebFetch::fetch()` fallback در [`scaffolds/web-fetch.php`](../../scaffolds/web-fetch.php) |
| removeUnwantedElements (فهرست ثابت tags/class) | `scrapeURL/lib/removeUnwantedElements.ts:9` | `WebFetch::cleanHtml()` با DOMDocument + DOMXPath |
| HTML→markdown زیرمجموعه | `lib/html-to-markdown.ts` | `WebFetch::toMarkdown()` (همان اسکلت) |
| robots.txt respect | `lib/robots-txt.ts`، `crawler.ts` | `WebFetch::robotsAllows()` |
| فیلتر لینک با denial reasons | `crawler.ts` `filterLinks` | `WebFetch::filterLinks()` + ثابت‌های DenialReason |
| charset sniff | `decodeHtmlBuffer` | `WebFetch::sniffCharset()` + `mb_convert_encoding` |
| مثال اجرایی | — | [`examples/10-web-fetch-demo.php`](../../examples/10-web-fetch-demo.php) |

## آنچه الگوبرداری *نشد*

| آنچه حذف شد | چرا |
|-------------|-----|
| SDK PHP رسمی firecrawl | وابستگی خارجی (نقض قید ۱-۲)؛ فقط الگوی معماری الگوبرداری شد |
| NuQ queue (Postgres/FDB) | زیرساخت سنگین؛ تک‌پردازشی کافی است |
| fire-engine CDP browser pool | نیاز به مرورگر headless (خارج از ext مجاز) |
| GCS index cache، Alexandria، Cloudflare/hCaptcha solving | زیرساخت ابری |
| Go html-to-markdown (FFI) | FFI ممنوع (`ext-ffi` در RULES.json forbidden_extensions) |
| rate-limit توزیع‌شده (Redis) | token-bucket per-host با `usleep` کافی (مستند در اسکلت) |

## گارد سازگاری

- `web-fetch.php` فقط از `ext-curl` + `ext-dom` + `ext-mbstring` + `ext-json`
  استفاده می‌کند — همگی در فهرست مجاز `RULES.json`.
- `curl_exec` در ممیزی امنیتی مجاز است (regex مرز واژه‌ای `(?<![\w>])exec(`
  در `tests/security-audit.php` زیرخط پیشوند را می‌بیند → تطابق نمی‌کند).
- **هیچ کد از firecrawl کپی نشده** (AGPL-3.0 انتقالی است؛ کپی‌کردن کل خروجی
  را آلوده می‌کرد). فقط *ایدهٔ ساختاری واترفال/پاکسازی/robots* الگوبرداری شد
  و در PHP خالص بازنویسی شد.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: firecrawl (AGPL-3.0/MIT, Mendable)
