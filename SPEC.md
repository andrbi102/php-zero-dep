# 🐘 مهارت اختصاصی PHP بدون وابستگی

> **Zero-Dependency PHP Skill**
> پرامپت و مرجع کامل برای تولید مهارت اختصاصی ایجنت‌های کدنویسی PHP
> فلسفه: بازآفرینی آگاهانه چرخ (deliberate reinvention)، بدون Composer، بدون پکیج‌منیجر، امنیت‌محور، پرسرعت و پایدار، سازمان‌یافته با Workerهای اتمیک.

## 📑 فهرست

1. فلسفه و اصول بنیادین
2. پرامپت جامع تولید مهارت (Main Prompt)
3. کاتالوگ مخازن بر اساس دسته‌بندی
4. معماری‌های نرم‌افزاری گسترده
5. مهندسی معکوس (REA)
6. رمزنگاری منحنی بیضوی (Elliptic Curve Cryptography)
7. ساختار داده گراف و نقشه ذهنی مدل
8. انواع پایگاه داده و ویژگی‌های مدرن
9. ویژگی‌های مدرن PHP (8.2–8.6)
10. اکوسیستم استقرار
11. یکپارچه‌سازی MCP
12. محدودیت‌ها و ملاحظات واقع‌بینانه
13. ساختار فایل‌های مهارت
14. چک‌لیست کیفیت و پذیرش
15. منابع تکمیلی
16. **مدل سازمانی: نقش‌ها، وظایف اتمیک، دسترسی‌ها، سلسله‌مراتب، شورا و مشورت (v2)**
17. راهنمای اجرا

## ۱. فلسفه و اصول بنیادین

| اصل | توضیح | معیار سنجش |
|-----|-------|-----------|
| **Zero-Composer** | هیچ `composer.json`، `vendor/` یا اتولودر تولید نشود | هیچ ارجاعی به `composer` یا `autoload` در خروجی نباشد |
| **Zero-Dependency** | فقط افزونه‌های هسته PHP | `ext-json`، `ext-pdo`، `ext-mbstring`، `ext-curl`، `ext-openssl`، `ext-sodium` |
| **بازآفرینی آگاهانه چرخ** | اتولودر، روتر، DI، Event Bus دست‌نویس | هر کامپوننت مستقل و درون‌فایل |
| **امنیت‌محور** | OWASP Top 10 در تمام لایه‌ها | Prepared Statement، escape، CSRF، Argon2id |
| **پرسرعت و پایدار** | کش، OPcache، عدم عملیات سنگین در حلقه | latency قابل اندازه‌گیری |
| **مستندسازی** | PHPDoc کامل + README قابل اجرا | هر فایل دارای docblock |
| **نسخه‌نویسی** | SemVer + CHANGELOG + Git tags | هر مهارت نسخه‌بندی‌شده |
| **طراحی انسانی** | PSR-12، نام‌گذاری معنادار | عبور از PHP_CodeSniffer |
| **بدون توهم API** | مشورت اجباری با Context7 MCP | `resolve-library-id` → `query-docs`  |
| **اتمی بودن وظایف** | هر وظیفه یک فعل، یک خروجی، یک مالک، یک DoD | هیچ وظیفه‌ای بیش از یک deliverable ندارد |
| **یک نقش = یک Worker** | هر نقشِ لازم در فاز جاری موقتاً یک Worker استخدام می‌کند | `workers.active == roles.required(current phase)` |
| **تفکیک وظایف (SoD)** | هیچ Worker هم‌زمان `write-*` و `approve-*` روی یک artifact ندارد | ماتریس دسترسی نقض نشود |

## ۲. پرامپت جامع تولید مهارت (Main Prompt)

```markdown
# ROLE
You are a Senior PHP Architect and Security Engineer specialized in
building ZERO-DEPENDENCY, ZERO-COMPOSER PHP systems for AI coding agents.

# MISSION
Build a comprehensive PHP Skill (Markdown + code scaffolds) that enables
AI coding agents to generate production-grade PHP code following the
principles and organizational model defined below.

# HARD CONSTRAINTS
1. NEVER generate composer.json or reference Composer packages.
2. NEVER use third-party libraries.
3. ONLY use native PHP 8.2+ features and core extensions.
4. Every file MUST have PHPDoc with @package, @version, @security-level.
5. Every class MUST be single-file and self-contained.
6. Every query MUST use PDO prepared statements.
7. Every output MUST be escaped (htmlspecialchars, ENT_QUOTES).
8. Every session cookie: HttpOnly + Secure + SameSite=Strict.
9. Every form MUST include HMAC-signed CSRF token.
10. Password hashing: password_hash(PASSWORD_ARGON2ID).
11. No silent failures: log or rethrow with context.
12. Event Dispatcher, Command Bus, Pipeline MUST be hand-written.
13. Before using any external library API, consult Context7 MCP
    (resolve-library-id → query-docs). If unavailable, warn explicitly.
14. Only the roles required by the CURRENT phase (F1–F10) MUST be instantiated
    as workers, each with an atomic task (T-NNN-NN). Two profiles exist:
    - "full": all 10 phases and the full council model (large projects).
    - "solo": a single architect worker + Security Reviewer + Code Reviewer,
      phases F1/F2/F4/F5/F6/F9/F10 collapsed; council sign-off batched
      (small projects, target ≤ 7 concurrent workers).
15. Workers are hired per atomic task and dissolved after approval.
16. The task dependency graph MUST be a DAG.
17. Council Security has ABSOLUTE veto.
18. No release without 5 signatures.

# DELIVERABLES
1. SKILL.md, AGENTS.md, RULES.json, MCP.md, README.md, CHANGELOG.md
2. docs/architecture/ (17 files: MVC → Actor, EDA, ES, CQRS, Onion, Vertical Slice)
3. docs/mcp/ (Context7, Tier-1/2, PHP MCP, security, limitations)
4. docs/runtime/ (FrankenPHP, RoadRunner)
5. docs/infrastructure/ (Podman, Nix, OpenTelemetry, Redis)
6. docs/security/ (OWASP, SQLi, XSS/CSRF, session, crypto, elliptic-curve)
7. docs/performance/ (OPcache, caching, memory, profiling)
8. docs/tooling/ (debug, error, static analysis, PHPUnit, Playwright)
9. docs/frontend/ (sessions, cookies, forms, CSRF, UI, A11y)
10. docs/use-cases/ (website, ecommerce, REST, CMS, CLI, blog)
11. docs/vision/ (limitations, pure PHP, external service)
12. docs/database/ (types, PDO, modern features, optimization)
13. docs/modern-php/ (8.2–8.6, version detection)
14. docs/deployment/ (Apache, Nginx, Docker, shared hosting)
15. docs/data-structures/ (graph, mind map, crypto primitives)
16. docs/reverse-eng/ (REA code, API, ethics)
17. docs/elliptic-curve/ (fundamentals, sodium, ECDSA, RFC)
18. docs/org/ (roles, RACI, permissions, council, consultation, lifecycle)
19. scaffolds/ (autoloader, router, DI, PDO wrapper, event bus, MCP server,
               tracing, cache, graph, workers/)
20. examples/ (9 runnable examples)
21. tests/ (smoke, security-audit)

# OUTPUT QUALITY BAR
- All docs in Persian (fa) with English technical terms preserved.
- Every code snippet must run standalone (php file.php).
- Every doc section ends with "✅ Checklist" and "❌ Anti-patterns".
- At least 3 examples per major topic.

# PROCESS
1. Read REPOSITORY_CATALOG and extract patterns.
2. Draft RULES.json (single source of truth, including org_model).
3. Generate SKILL.md, AGENTS.md, MCP.md.
4. Generate all docs/* in parallel.
5. Generate scaffolds/* matching rules.
6. Self-audit against Quality Checklist.
7. Report: "Skill vX.Y.Z ready. N files. N tasks."
```

## ۳. کاتالوگ مخازن بر اساس دسته‌بندی

### 🏗️ ۱. معماری‌های نرم‌افزاری و پیاده‌سازی

| مخزن | لینک | ویژگی | وضعیت | آخرین تأیید | لایسنس |
|------|------|--------|--------|-------------|--------|
| **upMVC** | [github.com/upMVC/upMVC](https://github.com/upMVC/upMVC) | NoFramework + MMVC + **AI Agent Pack داخلی** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **PHP DDD Demo** | [github.com/yceruto/php-ddd-demo](https://github.com/yceruto/php-ddd-demo) | DDD + Hexagonal + CQS، بدون وابستگی | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **NexusPHP** | [github.com/gak722/nexusphp](https://github.com/gak722/nexusphp) | Zero-Dependency، PHP 8.2+، **<3.5ms latency** و **<2.0MB memory** (خوداظهاری README)، onion middleware، libsodium، Argon2id، CSRF | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **Trongate** | [github.com/trongate/trongate-framework](https://github.com/trongate/trongate-framework) | PHP 8، **صفر Composer، صفر وابستگی** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **Siler** | [github.com/leocavalcante/siler](https://github.com/leocavalcante/siler) | Zero-Dependency، declarative | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **Ace Framework** | [github.com/celionatti/ace-framework](https://github.com/celionatti/ace-framework) | MVC سبک و Zero-Dependency | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **MicroMVC** | [github.com/db3net/MicroMVC](https://github.com/db3net/MicroMVC) | MVC **تک‌فایلی** با صفر وابستگی | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | GPL-3.0 |
| **Blink** | [github.com/Belikhun/blink](https://github.com/Belikhun/blink) | Zero-Dependency، بدون `composer install`، بدون build step | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **gfabrizi/plain-simple-framework** | [packagist.org/packages/gfabrizi/plain-simple-framework](https://packagist.org/packages/gfabrizi/plain-simple-framework) | Zero dependencies PHP framework | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **Relay** | [github.com/JustSteveKing/relay](https://github.com/JustSteveKing/relay) | **PHP 8.5+**، Fiber-native transport، **حداقل‌وابستگی** (`ext-curl` + `ext-mbstring`)، Pipe-native — در Packagist منتشر نشده؛ نصب از VCS | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **stann/stream** | [packagist.org/packages/stann/stream](https://packagist.org/packages/stann/stream) | **PHP 8.5+**، Pipe-native stream processing، **صفر وابستگی** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **DesignPatternsPHP** | [github.com/domnikl/DesignPatternsPHP](https://github.com/domnikl/DesignPatternsPHP) | مرجع الگوهای طراحی خالص | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **Dudulina** | [packagist.org/packages/xprt64/dudulina](https://packagist.org/packages/xprt64/dudulina) | CQRS + Event Sourcing با حداقل وابستگی | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | اختصاصی |
| **Event Machine** | [github.com/proophsoftware/event-machine](https://github.com/proophsoftware/event-machine) | CQRS/ES با OOP event sourced objects | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | BSD-3-Clause |

### 🛡️ ۲. امنیت و هک

| مخزن | لینک | نکته | وضعیت | آخرین تأیید | لایسنس |
|------|------|------|--------|-------------|--------|
| **SOTA-skills** | [github.com/martinholovsky/SOTA-skills](https://github.com/martinholovsky/SOTA-skills) | OWASP در سطح Skill ایجنت | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | CC-BY-4.0 |
| **Secure-PHP-Development** | [github.com/armourinfosec/Secure-PHP-Development](https://github.com/armourinfosec/Secure-PHP-Development) | ۳۴ ماژول امنیتی PHP 8.2/8.3 | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | CC-BY-4.0 |
| **php-storageless-sessions** | [github.com/Snawoot/php-storageless-sessions](https://github.com/Snawoot/php-storageless-sessions) | Session در کوکی HMAC + رمزنگاری | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **laramint/php-security-scanner** | [packagist.org/packages/laramint/php-security-scanner](https://packagist.org/packages/laramint/php-security-scanner) | اسکنر استاتیک framework-agnostic | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

### ⚡ ۳. عملکرد

| مخزن | لینک | کاربرد | وضعیت | آخرین تأیید | لایسنس |
|------|------|--------|--------|-------------|--------|
| **PerformanceMeter** | [github.com/InitPHP/PerformanceMeter](https://github.com/InitPHP/PerformanceMeter) | تک‌فایلی پروفایلر | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **nocopy** | [github.com/chrfritsch/nocopy](https://github.com/chrfritsch/nocopy) | کش فوق‌سریع در حافظه | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **StaticPHP** | [github.com/crazywhalecc/static-php-cli](https://github.com/crazywhalecc/static-php-cli) | باینری قابل حمل | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

### 🛠️ ۴. ابزارهای توسعه

| مخزن | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **vardumper** | [github.com/michelphp/vardumper](https://github.com/michelphp/vardumper) | `dump()`، `dd()`، `btrace()` — Zero-dependency | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **ddless-engine** | [github.com/behindSolution/ddless-engine](https://github.com/behindSolution/ddless-engine) | دیباگر AST-based | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **filo** | [github.com/giacomomasseron/filo](https://github.com/giacomomasseron/filo) | Zero-extension call tracer | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **PhpCodeArcheology** | [github.com/PhpCodeArcheology/PhpCodeArcheology](https://github.com/PhpCodeArcheology/PhpCodeArcheology) | تحلیل معماری + dependency graph + MCP server | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **PHPStan / Phan** | [github.com/phpstan/phpstan](https://github.com/phpstan/phpstan) و [github.com/phan/phan](https://github.com/phan/phan) | تحلیل ایستا | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT / — |

### 👁️ ۵. بینایی ماشین

| مخزن | لینک | محدودیت | وضعیت | آخرین تأیید | لایسنس |
|------|------|---------|--------|-------------|--------|
| **HAARPHP** | [github.com/foo123/HAARPHP](https://github.com/foo123/HAARPHP) | Haar Cascades خالص — PHP خالص، دقت پایین | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **php-opencv** | [github.com/php-opencv/php-opencv](https://github.com/php-opencv/php-opencv) | افزونه C — خارج از Zero-Ext | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | Apache-2.0 |

### 🌐 ۶. Frontend و UI

| مخزن | لینک | نقش | وضعیت | آخرین تأیید | لایسنس |
|------|------|-----|--------|-------------|--------|
| **Whisker Cart** | [github.com/WhiskerEnt/Whisker-Cart](https://github.com/WhiskerEnt/Whisker-Cart) | فروشگاه کامل PHP خالص + MySQL | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **Hot-UI** | [packagist.org/packages/hot-ui/hot-ui](https://packagist.org/packages/hot-ui/hot-ui) | کامپوننت UI سبک، Zero-dependency (PHP 8.2+، Alpine.js islands) | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **php-instant-router** | [github.com/nadermkhan/php-instant-router](https://github.com/nadermkhan/php-instant-router) | روتر تک‌فایلی با CSRF + rate limit | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

### 🛒 ۷. کاربردها

| مخزن | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **VibeShell** | [github.com/d3cline/VibeShell](https://github.com/d3cline/VibeShell) | سرور MCP تک‌فایلی، **صفر وابستگی**، PHP 7.4+، محافظت path traversal (تأییدشده ۲۰۲۶-۱۱-۲۵) | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | GPL-3.0 |
| **Chyrp Lite** | [github.com/xenocrat/chyrp-lite](https://github.com/xenocrat/chyrp-lite) | وبلاگ بدون Composer | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | BSD-3-Clause |
| **Simple-URL-Shortener** | [github.com/azlux/Simple-URL-Shortener](https://github.com/azlux/Simple-URL-Shortener) | ۱۲ فایل | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **QuadCore Store** | [github.com/dd-software/ecommerce-project-s1](https://github.com/dd-software/ecommerce-project-s1) | فروشگاه zero-dependency | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |

### 📚 ۸. مستندسازی

| ابزار | لینک | کاربرد | وضعیت | آخرین تأیید | لایسنس |
|-------|------|--------|--------|-------------|--------|
| **Doctum** | [github.com/code-lts/doctum](https://github.com/code-lts/doctum) | تولید مستندات API PHP | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **ApexDocs** | [packagist.org/packages/anil/apexdocs](https://packagist.org/packages/anil/apexdocs) | OpenAPI 3.1 — Zero framework dependencies in core | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **Keep a Changelog / SemVer / Conventional Commits** | — | استانداردها | — | — | — |

### 🔌 ۹. MCP

| ابزار | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **Context7 MCP** | [github.com/upstash/context7](https://github.com/upstash/context7) | مستندات نسخه‌محور — **`resolve-library-id` سپس `query-docs`** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **VibeShell** | [github.com/d3cline/VibeShell](https://github.com/d3cline/VibeShell) | سرور MCP تک‌فایلی PHP 7.4+ | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | GPL-3.0 |

### 🖥️ ۱۰. Runtime و Infrastructure

| مورد | توضیح |
|------|-------|
| **FrankenPHP** | باینری استاتیک، PHP 8.5 embedded، بدون FPM |
| **RoadRunner** | Long-Lived Workers، باینری `rr` مستقل |
| **Podman** | کانتینر rootless بدون Daemon |
| **Nix / devenv** | محیط توسعه قابل بازتولید |
| **Predis / phpredis** | Redis: Pure PHP در برابر افزونه C |

### 🕸️ ۱۱. گراف

| منبع | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **mbsoft31/graph-core** | [github.com/mbsoft31/graph-core](https://github.com/mbsoft31/graph-core) | Adjacency list با O(1)، Zero-Dependency | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **vegoia** | [github.com/abbadon1334/vegoia](https://github.com/abbadon1334/vegoia) | الگوریتم‌های گراف (Leiden، centrality، shortest paths) — zero runtime dependencies | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

### 🔐 ۱۲. رمزنگاری منحنی بیضوی

| منبع | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **paragonie/sodium_compat** | [github.com/paragonie/sodium_compat](https://github.com/paragonie/sodium_compat) | Pure PHP libsodium (Curve25519، Ed25519، X25519) | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | ISC |
| **RFC 7748** | — | مشخصات Curve25519 و X25519 | استاندارد IETF | — | — |
| **RFC 8032** | — | مشخصات Ed25519/EdDSA (امضای دیجیتال) | استاندارد IETF | — | — |

### 🗄️ ۱۳. پایگاه داده

| منبع | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **InitORM/DBAL** | [github.com/InitORM/DBAL](https://github.com/InitORM/DBAL) | لایه انتزاعی PDO **dependency-free** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **tommyknocker/pdo-database-class** | [github.com/tommyknocker/pdo-database-class](https://github.com/tommyknocker/pdo-database-class) | API یکپارچه برای MySQL، PG، SQLite، MSSQL، Oracle — **Zero external dependencies** | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **RedBeanPHP** | [github.com/gabordemooij/redbean](https://github.com/gabordemooij/redbean) | ORM بدون پیکربندی — یک فایل، بدون Composer | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |

### 🤖 ۱۴. ایجنت‌های کدنویسی و اکوسیستم مهارت

| مخزن | لینک | توضیح | وضعیت | آخرین تأیید | لایسنس |
|------|------|-------|--------|-------------|--------|
| **ECC (Everything Claude Code)** | [github.com/affaan-m/ECC](https://github.com/affaan-m/ECC) | سیستم بهینه‌سازی عملکرد agent harness: مهارت‌ها (skills)، غرایز (instincts)، حافظه (memory)، امنیت (AgentShield) و توسعه research-first برای Claude Code، Codex، Opencode و Cursor — ۷۲ ایجنت تخصصی و ۳۲۸ مهارت؛ مرجع الگوبرداری فرایندی در سمت harness، نه وابستگی PHP (جزئیات: بخش ۱۵) | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

> علامت «—» در ستون لایسنس یعنی لایسنس به‌صورت خودکار شناسایی نشد و پیش از استفاده یا vendoring باید بررسی شود.

## ۴. معماری‌های نرم‌افزاری گسترده

### جدول جامع معماری‌ها

| معماری | ایده اصلی | Zero-Dep مرجع | نکته ایجنت |
|---------|-----------|---------------|-----------|
| **MVC** | Model / View / Controller | Trongate، MicroMVC | View باید escape خودکار داشته باشد |
| **HMVC** | MVC سلسله‌مراتبی | DynaPort X، Trongate | از Dispatcher استفاده کن، نه `new Controller()` |
| **Modular (MMVC)** | هر ماژول یک اپ مینیاتوری مستقل | upMVC، Trongate، Ace | هر ماژول جداگانه تست و مستقر شود |
| **Microservice** | سرویس‌های کوچک مستقل | `webrium/core` | هر سرویس stateless باشد |
| **Serverless** | اجرای تابع بدون مدیریت سرور | NexusPHP (بهینه برای serverless)  | بدون فایل‌سیستم پایدار، Cold Start |
| **REST API** | منابع + متدهای HTTP + JSON | Restler، Flight | نسخه‌بندی `/v1/`، کد وضعیت صحیح |
| **DDD / Hexagonal / Clean** | جداسازی Domain از Infrastructure | PHP DDD Demo | Domain نباید به PDO/HTTP وابسته باشد |
| **Onion** | لایه‌های متحدالمرکز، وابستگی به داخل | `final class` + `interface` | با Hexagonal هم‌پوشانی دارد |
| **Vertical Slice** | سازمان‌دهی بر اساس Feature | دستی از اصول (مرجع فریمورکی قابل‌تأیید ندارد) | با CQRS هم‌پوشانی دارد |
| **EDA** | رویداد ناهمزمان بین اجزا | snicco/event-dispatcher (PSR-14، حداقل‌وابستگی) | Dispatcher را دستی بنویس (≈۲۰ خط) |
| **Event Sourcing** | ذخیره جریان رویداد، بازپخش برای وضعیت | Dudulina، Event Machine | فقط در Bounded Contextهایی که مدل DB کافی نیست |
| **CQRS** | Command / Query جدا | xprt64/dudulina (CQRS + ES) | CommandBus/QueryBus دستی |
| **Pipeline / Middleware** | زنجیره قابل ترکیب | NexusPHP (onion middleware)  | آسان‌ترین الگو برای دست‌نویس |
| **Actor Model** | واحد همزمانی مستقل، بدون حافظه مشترک | `Innmind/actors` | PHP فیبر دارد اما اسکدیولر بومی ندارد — Queue + Worker واقع‌بینانه‌تر |

### الگوهای پیاده‌سازی کلیدی

**Event Dispatcher (PSR-14 style، < 50 خط):**

```php
final class EventDispatcher {
    private array $listeners = [];
    public function listen(string $eventClass, callable $listener): void {
        $this->listeners[$eventClass][] = $listener;
    }
    public function dispatch(object $event): object {
        foreach ($this->listeners[$event::class] ?? [] as $listener) {
            $listener($event);
        }
        return $event;
    }
}
```

**Pipeline (onion model، < 30 خط):**

```php
final class Pipeline {
    public function __construct(private array $stages) {}
    public function process(mixed $payload): mixed {
        $next = array_reduce(
            array_reverse($this->stages),
            fn(callable $carry, callable $stage) => fn($p) => $stage($p, $carry),
            fn($p) => $p
        );
        return $next($payload);
    }
}
```

**Command Bus (CQRS حداقلی):**

```php
final class CommandBus {
    private array $handlers = [];
    public function register(string $class, callable $handler): void {
        $this->handlers[$class] = $handler;
    }
    public function dispatch(object $command): mixed {
        $handler = $this->handlers[$command::class]
            ?? throw new LogicException("No handler for {$command::class}");
        return $handler($command);
    }
}
```

### راهنمای انتخاب معماری

```
CRUD کوچک                  → MVC / Modular
قواعد کسب‌وکار پیچیده       → DDD / Hexagonal / Onion
خواندن/نوشتن نامتقارن      → CQRS
حسابرسی کامل / Time Travel → Event Sourcing (Bounded Context)
همزمانی بالا               → Actor (واقع‌گرایانه: Queue + Worker)
تکرار سریع Feature         → Vertical Slice
اثرات جانبی real-time      → Event-Driven
```

### ✅ Checklist معماری‌ها
- [ ] Domain (DDD/Onion) به PDO/HTTP وابسته نیست
- [ ] Event Dispatcher دستی نوشته شده یا PSR-14 vendored
- [ ] Event Store با PDO + جدول `events`، `aggregate_id` ایندکس‌شده
- [ ] CQRS فقط در صورت نیاز واقعی فعال شده
- [ ] Pipeline با `callable` خالص
- [ ] ES محدود به Bounded Context خاص
- [ ] هر معماری دارای ADR

### ❌ Anti-patterns
- HMVC با `new Controller()` مستقیم
- Microservice با دیتابیس مشترک
- Serverless با فایل‌سیستم محلی
- REST API با `200 OK` برای خطا
- ES برای CRUD کوچک

## ۵. مهندسی معکوس (REA)

| ابزار | لینک | کاربرد | وضعیت | آخرین تأیید | لایسنس |
|-------|------|--------|--------|-------------|--------|
| **disrobe** | [github.com/1-3-7/disrobe](https://github.com/1-3-7/disrobe) | دی‌کامپایلر PHP/Python/JS/Android | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **mago-reflection** | [github.com/carthage-software/mago](https://github.com/carthage-software/mago) | بازرسی و تحلیل کد PHP | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | Apache-2.0 |
| **phpdet** | [crates.io/crates/phpdet](https://crates.io/crates/phpdet) | تحلیل AST و استخراج شواهد ساختاری — zero-dependency | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT OR Apache-2.0 |
| **php-route-mapper** | [github.com/0xShe/PHP-Code-Audit-Skill](https://github.com/0xShe/PHP-Code-Audit-Skill) | استخراج روت‌ها از سورس PHP | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | — |
| **Noir** | [github.com/owasp-noir/noir](https://github.com/owasp-noir/noir) | SAST برای کشف endpoint در ۳۲ زبان | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **reverse-api-engineer** | [github.com/kalil0321/reverse-api-engineer](https://github.com/kalil0321/reverse-api-engineer) | تولید کلاینت API از ترافیک مرورگر | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |
| **JanePHP** | [github.com/lukassteiner/janephp](https://github.com/lukassteiner/janephp) | Model + API Client از OpenAPI (فورک؛ مخزن رسمی: janephp/janephp) | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | MIT |

### ✅ Checklist REA
- [ ] مجوز و کپی‌رایت بررسی شده
- [ ] خروجی برای «درک الگو» استفاده شود، نه کپی مستقیم
- [ ] روت‌ها و پارامترها ابتدا مستند شوند، سپس کلاینت نوشته شود

## ۶. رمزنگاری منحنی بیضوی (Elliptic Curve Cryptography)

| مفهوم | توضیح | کاربرد PHP |
|--------|-------|------------|
| **Curve25519** | منحنی مدرن ECDH | `sodium_crypto_box` (X25519) |
| **Ed25519** | امضای دیجیتال | `sodium_crypto_sign` |
| **P-256/P-384** | منحنی NIST | `openssl` |

### رویکرد Zero-Dependency

1. **اولویت ۱:** `ext-sodium` فعال → `sodium_crypto_*` (Zero-Dependency واقعی)
2. **اولویت ۲:** `sodium_compat` به صورت **vendored** در `scaffolds/crypto/` (نه Composer)
3. **اولویت ۳:** `openssl` برای ECDSA/ECDH
4. **ممنوع:** `composer require paragonie/sodium_compat` در runtime
5. **لایسنس کد vendored:** لایسنس کد کپی‌شده (مانند `sodium_compat` — ISC) بررسی و اعلان کپی‌رایت نویسنده در فایل حفظ شود؛ کپی کد با لایسنس Copyleft (مانند GPL-3.0) در خروجی MIT مجاز نیست.

### ✅ Checklist رمزنگاری منحنی بیضوی
- [ ] `extension_loaded('sodium')` بررسی شده
- [ ] در صورت نبود sodium، `sodium_compat` vendored
- [ ] کلیدها هرگز در لاگ چاپ نمی‌شوند
- [ ] پیاده‌سازی منحنی بیضوی از صفر در production ممنوع

## ۷. ساختار داده گراف و نقشه ذهنی مدل

**Adjacency List بدون وابستگی:**

```php
final class Graph {
    private array $adj = [];
    public function addNode(string $node): void { $this->adj[$node] ??= []; }
    public function addEdge(string $from, string $to, int $w = 1): void {
        $this->addNode($from); $this->addNode($to);
        $this->adj[$from][$to] = $w;
    }
    public function neighbors(string $node): array { return $this->adj[$node] ?? []; }
    public function bfs(string $start): array {
        $visited = [$start => true];
        $queue = [$start];
        $order = [];
        // صف با اشاره‌گر: array_shift حذف شد (O(n) → O(1) در هر گام)
        for ($head = 0, $tail = 1; $head < $tail; $head++) {
            $n = $queue[$head];
            $order[] = $n;
            foreach ($this->neighbors($n) as $next => $_) {
                if (!isset($visited[$next])) {
                    $visited[$next] = true;
                    $queue[] = $next;
                    $tail++;
                }
            }
        }
        return $order;
    }
}
```

**کاربرد در Model Mind Map:** مدل‌ها = گره‌ها، روابط = یال‌ها، وزن = قدرت رابطه. خروجی GraphML یا Cytoscape JSON.

### ✅ Checklist گراف
- [ ] Adjacency list (نه matrix) برای گراف‌های تنک
- [ ] BFS/DFS برای پیمایش
- [ ] خروجی استاندارد GraphML/Cytoscape

## ۸. انواع پایگاه داده و ویژگی‌های مدرن

| نوع | افزونه | مناسب برای | محدودیت |
|-----|--------|-----------|---------|
| **SQLite** | `pdo_sqlite` | پروژه سبک، CLI، تست | همزمانی نوشتن محدود |
| **MySQL/MariaDB** | `pdo_mysql` | وب‌سایت، فروشگاه | سرور جداگانه |
| **PostgreSQL** | `pdo_pgsql` | JSONB، CTE، Window Functions | تنظیمات سنگین‌تر |

**ویژگی‌های مدرن:** JSON columns، Generated columns، CTE، Window Functions، UPSERT، RETURNING. **ایجنت باید Dialect را تشخیص دهد** — `JSONB` مخصوص PostgreSQL و در SQLite باید `JSON` (TEXT) باشد.

### ✅ Checklist دیتابیس
- [ ] Dialect detection در لایه PDO
- [ ] عدم استفاده از ویژگی پشتیبانی‌نشده
- [ ] Migration قابل اجرا از مرورگر (هاست اشتراکی)

## ۹. ویژگی‌های مدرن PHP (8.2–8.6)

| نسخه | ویژگی | کاربرد |
|-------|-------|--------|
| **8.2** | Readonly classes، DNF types | DTO، Value Object |
| **8.3** | Typed class constants، `#[\Override]`، `json_validate()` | ثابت type-safe، اعتبارسنجی JSON |
| **8.4** | **Property hooks**، asymmetric visibility | Property محاسباتی با validation داخلی؛ **Hooks با readonly ناسازگارند** |
| **8.5** | **Pipe operator (`\|>`)** | `mixed \|> callable` — انتقال مقدار چپ به callable راست |
| **8.6** | **Partial Function Application**، کلاس `Duration`، Polling API، تابع `clamp()` | منتشرشده ۲۰۲۶-۱۱-۱۹ — دوره تثبیت؛ پس از تثبیت و با تأیید صریح |

**PHP 8.5 — Pipe-native zero-dependency:**
- `stann/stream`: توابع lazy، curried، **صفر وابستگی** برای `|>` 
- `Relay`: لایه انتقال Fiber-native با `ext-curl` + `ext-mbstring` (نصب از VCS — در Packagist منتشر نشده) 

**قانون:** اگر نسخه PHP نامشخص است، از 8.3 استفاده کن (PHP 8.2 در ۳۱ دسامبر ۲۰۲۶ EOL می‌شود). 8.4/8.5 فقط با تأیید صریح. PHP 8.6 (نوامبر ۲۰۲۶) پس از دوره تثبیت به baseline اضافه شود.

## ۱۰. اکوسیستم استقرار

| محیط | نکته کلیدی |
|------|-----------|
| **Apache** | `.htaccess` تنها اهرم در هاست اشتراکی |
| **Nginx** | الزاماً `PHP-FPM` جداگانه؛ `.htaccess` پشتیبانی نمی‌شود |
| **Docker** | `Dockerfile` بدون `composer install` |
| **FrankenPHP** | باینری استاتیک، PHP 8.5 embedded، بدون FPM؛ musl نمی‌تواند Xdebug بارگذاری کند |
| **RoadRunner** | Long-Lived Workers، باینری `rr` مستقل از Composer |
| **Podman** | rootless بدون Daemon؛ جایگزین Docker در VPS بدون دسترسی root |
| **Nix / devenv** | محیط توسعه بازتولیدپذیر؛ فقط dev |
| **هاست اشتراکی** | بدون CLI، memory_limit پایین، `.htaccess` تنها اهرم |
| **Redis** | `ext-redis` اگر موجود؛ وگرنه کش فایل‌محور دست‌نویس (نه Composer) |
| **OpenTelemetry** | Manual Tracing با `curl` + `traceparent` (نه SDK اجباری) |

### ✅ Checklist استقرار
- [ ] مستندات Apache و Nginx جداگانه
- [ ] `Dockerfile` بدون Composer
- [ ] راهنمای هاست اشتراکی
- [ ] `phpversion()` guard
- [ ] FrankenPHP/RoadRunner/Podman در صورت استفاده
- [ ] Redis: `ext-redis` یا فایل‌محور
- [ ] Tracing دستی

## ۱۱. یکپارچه‌سازی MCP

MCP یک **پروتکل** است، نه پکیج PHP. سرور در فرایند جداگانه اجرا می‌شود؛ کد PHP تولیدشده هیچ import از MCP ندارد.

### Context7 MCP

| ویژگی | توضیح |
|--------|-------|
| **ابزارها** | `resolve-library-id` → `query-docs` (ترتیب اجباری)  |
| **نسخه‌محور** | با ذکر نسخه، مستندات همان نسخه |
| **Local** | `npx -y @upstash/context7-mcp` (روش پیشنهادی جدید: `npx ctx7 setup`) |
| **Remote** | `https://mcp.context7.com/mcp` با هدر `Authorization: Bearer <API_KEY>` — بدون Node.js (کلید رایگان از context7.com/dashboard؛ بدون کلید، محدودیت نرخ شدید دارد) |

**قاعده AGENTS.md:**
> «هرگاه ایجنت قصد تولید کد برای کتابخانه خارجی دارد، ابتدا `resolve-library-id` (مگر شناسه `/owner/repo` داده شده باشد)، سپس `query-docs` با پرسش مشخص و زبان PHP. اگر Context7 در دسترس نیست، اعلام محدودیت کن و بر دانش خود تکیه نکن.»

### PHP MCP (Zero-Dependency)

| مخزن | لینک | ویژگی | وضعیت | آخرین تأیید | لایسنس |
|------|------|--------|--------|-------------|--------|
| **VibeShell** | [github.com/d3cline/VibeShell](https://github.com/d3cline/VibeShell) | سرور تک‌فایلی PHP 7.4+؛ ابزارهای فایل‌سیستم با محافظت path traversal؛ هر Tool یک فایل مستقل | ✅ فعال | ۲۰۲۶-۱۱-۲۵ | GPL-3.0 |

**اسکلت `scaffolds/mcp-server.php`:**

```php
// tools/hello.php — هر فایل یک Tool
return [
    'description' => 'Say hello',
    'input' => ['name' => 'string'],
    'execute' => function (array $args): string {
        $name = $args['name'] ?? null;
        if (!is_string($name) || $name === '') {
            throw new InvalidArgumentException("Tool 'hello': 'name' must be a non-empty string");
        }
        // خروجی متنی برای LLM است؛ htmlspecialchars فقط هنگام رندر HTML به کار رود
        return 'Hello, ' . $name;
    },
];
```

اجرا: `php scaffolds/mcp-server.php serve ./tools` — یا استفاده از VibeShell (تک‌فایلی، PHP 7.4+) به‌عنوان سرور آماده

### ✅ Checklist MCP
- [ ] `MCP.md` شامل Local و Remote
- [ ] `AGENTS.md` شامل قاعده Context7
- [ ] `RULES.json` شامل `no-hallucinated-api: true`
- [ ] `scaffolds/mcp-server.php` موجود
- [ ] `docs/mcp/05-limitations.md` شامل Node.js و context window

## ۱۲. محدودیت‌ها و ملاحظات واقع‌بینانه

1. **CV:** PHP خالص عملی نیست؛ `php-opencv` خارج از Zero-Ext.
2. **Playwright:** کتابخانه PHP نیازمند Composer؛ **Playwright MCP** سازگارتر (فقط dev).
3. **رمزنگاری منحنی بیضوی:** `sodium_compat` از Composer نصب می‌شود اما **vendored** مجاز است.
4. **گراف:** adjacency list را خودت پیاده‌سازی کن.
5. **دیتابیس مدرن:** به نسخه DB وابسته است.
6. **Nginx:** بدون FPM اجرا نمی‌شود.
7. **هاست اشتراکی:** بدون CLI، migration از مرورگر.
8. **REA:** مجوز و کپی‌رایت بررسی شود.
9. **Serverless:** فایل‌سیستم پایدار ندارد.
10. **Actor:** PHP فیبر (Fiber) دارد اما اسکدیولر بومی ندارد؛ Queue + Worker.
11. **MCP:** Local نیازمند Node.js؛ Remote نیازمند شبکه. **ابزار توسعه است، نه وابستگی runtime.**
12. **FrankenPHP:** musl نمی‌تواند Xdebug بارگذاری کند.
13. **RoadRunner:** باینری `rr` باید روی سرور موجود باشد.
14. **Podman:** روی هاست اشتراکی معمولاً نیست.
15. **Nix:** فقط dev.
16. **OpenTelemetry:** Zero-Code نیازمند PECL + Composer؛ Manual Tracing حداقلی است.

## ۱۳. ساختار فایل‌های مهارت

```
php-zero-dep-skill/
├── SKILL.md / AGENTS.md / RULES.json / MCP.md
├── README.md / CHANGELOG.md / VERSION / LICENSE
│
├── docs/
│   ├── architecture/        ← ۱۷ فایل (MVC → Actor)
│   ├── reverse-eng/         ← ۴ فایل
│   ├── elliptic-curve/      ← ۵ فایل
│   ├── security/            ← ۸ فایل
│   ├── database/            ← ۵ فایل
│   ├── modern-php/          ← ۵ فایل
│   ├── deployment/          ← ۵ فایل
│   ├── runtime/             ← ۲ فایل
│   ├── infrastructure/      ← ۴ فایل
│   ├── data-structures/     ← ۴ فایل
│   ├── performance/         ← ۵ فایل
│   ├── tooling/             ← ۶ فایل
│   ├── frontend/            ← ۷ فایل
│   ├── use-cases/           ← ۶ فایل
│   ├── vision/              ← ۳ فایل
│   ├── mcp/                 ← ۵ فایل
│   └── org/                 ← **۱۲ فایل + ۶ زیرپوشه (بخش ۱۶)**
│
├── scaffolds/
│   ├── autoloader.php / router.php / container.php
│   ├── database.php / session.php / csrf.php / validator.php
│   ├── view.php / logger.php / error-handler.php
│   ├── rate-limiter.php / cache.php / http-client.php
│   ├── crypto.php / graph.php / event-dispatcher.php
│   ├── event-store.php / command-bus.php / query-bus.php
│   ├── pipeline.php / hmvc-dispatcher.php / serverless-handler.php
│   ├── mcp-server.php / tracing.php / db-migrate.php
│   └── workers/            ← **۷۹ Worker Contract (بخش ۱۶)**
│
├── ADR/                    ← تصمیم‌های معماری (قالب MADR — به‌ازای هر معماری)
├── council-log.md          ← صورت‌جلسات شورا (بخش ۱۶٫۷)
├── lessons-learned.md      ← خروجی گام ۸ چرخه Worker (بازنگری)
├── examples/               ← ۹ مثال قابل اجرا
└── tests/                  ← smoke-test, security-audit
```

## ۱۴. چک‌لیست کیفیت و پذیرش

### کد و امنیت
- [ ] هیچ `composer.json` / `vendor/` وجود ندارد
- [ ] همه کوئری‌ها Prepared Statement
- [ ] همه خروجی‌ها `htmlspecialchars(..., ENT_QUOTES)`
- [ ] همه فرم‌ها CSRF token
- [ ] `session_regenerate_id()` پس از login
- [ ] کوکی‌ها: HttpOnly + Secure + SameSite=Strict
- [ ] `password_hash(PASSWORD_ARGON2ID)`
- [ ] آپلود: MIME + ext + size + خروج از webroot
- [ ] بدون `eval()` / `exec()` / `shell_exec()` / `system()` / `passthru()` / `proc_open()` / `popen()` مگر با مستندسازی صریح و sandbox
- [ ] همه فایل‌ها با `declare(strict_types=1);` شروع می‌شوند
- [ ] هدرهای امنیتی HTTP (CSP، HSTS، X-Content-Type-Options، X-Frame-Options، Referrer-Policy) تنظیم شده‌اند
- [ ] رمزنگاری منحنی بیضوی: `sodium_crypto_box` یا Pure-PHP مستندشده

### معماری
- [ ] اتولودر دستی (`spl_autoload_register`)
- [ ] Router بدون regex خطرناک
- [ ] Domain جدا از PDO/HTTP
- [ ] Event Dispatcher دستی / PSR-14 vendored
- [ ] Pipeline با `callable` خالص
- [ ] ADR برای هر معماری

### دیتابیس
- [ ] Dialect detection
- [ ] عدم استفاده از ویژگی پشتیبانی‌نشده
- [ ] Migration از مرورگر

### عملکرد
- [ ] کش برای کوئری سنگین
- [ ] Indices مناسب
- [ ] بدون `SELECT *` در production
- [ ] OPcache پیکربندی‌شده

### استقرار
- [ ] Apache + Nginx جداگانه
- [ ] `Dockerfile` بدون Composer
- [ ] راهنمای هاست اشتراکی
- [ ] `phpversion()` guard
- [ ] FrankenPHP / RoadRunner / Podman در صورت استفاده
- [ ] Redis / Tracing مطابق رویکرد Zero-Dep

### MCP
- [ ] `MCP.md` Local + Remote
- [ ] قاعده Context7 در `AGENTS.md`
- [ ] `no-hallucinated-api: true` در `RULES.json`
- [ ] `scaffolds/mcp-server.php` موجود

### سازمان (بخش ۱۶)
- [ ] `RULES.json` شامل `org_model` v2
- [ ] `AGENTS.md` شامل ۱۵ قاعده سازمانی
- [ ] `docs/org/` شامل ۱۲ فایل + ۶ زیرپوشه
- [ ] `scaffolds/workers/` شامل ۷۹ فایل
- [ ] گراف وابستگی DAG معتبر
- [ ] همه ۳۷۲ وظیفه دارای DoD
- [ ] SoD-1 تا SoD-5 نقض نشده
- [ ] `consultation/` شامل مشورت‌های اجباری
- [ ] `council-log.md` شامل جلسات
- [ ] هر ADR دارای امضای Council Architect
- [ ] هر انتشار دارای ۵ امضا

### مستندسازی و نسخه
- [ ] PHPDoc کامل
- [ ] README Quickstart
- [ ] CHANGELOG SemVer
- [ ] `examples/` قابل اجرا

## ۱۵. منابع تکمیلی

**ابزارهای dev:** PHPStan، PHP_CodeSniffer، PHPUnit، PHPBench، Xdebug، Playwright MCP، mago، Phan

**استانداردها:** PSR-1/4/12، PSR-14، PSR-15، OWASP Top 10، OWASP API Security Top 10، PHP: The Right Way، SemVer، Keep a Changelog، Conventional Commits، RFC 7748، MCP Specification

**افزونه‌های مجاز:** `ext-json`، `ext-pdo`، `ext-pdo_mysql`، `ext-pdo_pgsql`، `ext-pdo_sqlite`، `ext-mbstring`، `ext-curl`، `ext-openssl`، `ext-sodium`، `ext-session`، `ext-filter`، `ext-hash`، `ext-fileinfo`، `ext-gd` (اختیاری)، `ext-zip`، `ext-dom`، `ext-xml`، `ext-libxml`، `ext-simplexml`، `ext-redis` (اگر از پیش نصب باشد)

**توابع/ساختارهای ممنوع در خروجی:** `eval()`، `exec()`، `shell_exec()`، `system()`، `passthru()`، `proc_open()`، `popen()` — مگر با مستندسازی صریح و sandbox.

**افزونه‌های ممنوع:** `ext-ffi`، افزونه‌های غیرهسته مانند php-opencv.

> نکته: `eval()` یک ساختار زبانی و `exec()` تابعی هسته‌ای است؛ «ext-eval» و «ext-exec» به‌عنوان افزونه وجود ندارند.

### 🤖 ECC (Everything Claude Code) — هم‌راستایی مهارت با harness ایجنت

[ECC](https://github.com/affaan-m/ECC) (مخزن affaan-m/ECC در GitHub؛ لایسنس MIT؛ فعال؛ ≈۳۱۰ هزار ستاره — تأیید ۲۰۲۶-۱۱-۲۵) «سیستم بهینه‌سازی عملکرد agent harness» است: مهارت‌ها (skills)، غرایز (instincts)، حافظه (memory)، امنیت و توسعه research-first برای Claude Code، Codex، Opencode و Cursor. طبق README آن، ECC به ایجنتِ کدنویس یک سیستم مهندسی هماهنگ می‌دهد: قبل از ساختن برنامه‌ریزی می‌کند، تغییرات را با تست تأیید می‌کند، کار خود را با نگاه تازه بازبینی می‌کند، نکات مهم را به یاد می‌سپارد و پیروزی‌های تکراری را به مهارت و گردش‌کار قابل استفاده مجدد تبدیل می‌کند — چرخه:

`plan → test → implement → review → verify → remember → improve`

ECC پروژه PHP نیست و در این مهارت به‌عنوان وابستگی نصب نمی‌شود؛ نقش آن **مرجع الگوبرداری ساختار و فرایند در سمت harness** است. نگاشت قابلیت‌های ECC به معادل‌های این سند:

| قابلیت ECC | معادل در این مهارت |
|------------|-------------------|
| چرخه plan → test → implement → review → verify → remember → improve | گام‌های PROCESS در پرامپت اصلی (بخش ۲) + خودارزیابی با چک‌لیست بخش ۱۴ |
| توسعه research-first (اول پژوهش، بعد کد) | اصل «بدون توهم API»: مشورت اجباری Context7 MCP (`resolve-library-id` → `query-docs`) |
| اسکن امنیتی AgentShield | چک‌لیست «کد و امنیت» (بخش ۱۴) + `tests/security-audit` |
| ۷۲ ایجنت تخصصی (planning، review، build repair، security، architecture، domain) | مدل سازمانی بخش ۱۶: نقش‌ها، Workerهای اتمیک، شورا و پروتکل مشورت |
| hooks، rules و memory | `RULES.json` (منبع واحد حقیقت)، `AGENTS.md` و `CHANGELOG.md` تولیدشده |

> **مرز روشن با فلسفه Zero-Dependency:** خروجی این مهارت (SKILL.md، AGENTS.md، RULES.json، docs/، scaffolds/) مستقل از هر harness کار می‌کند. اگر ایجنت در محیط ECC اجرا شود، همین فایل‌ها بدون Composer، نصب اضافه یا تغییر در کد PHP، کنار مهارت‌ها و قواعد ECC قرار می‌گیرند.

## ۱۶. مدل سازمانی v2: نقش‌ها، وظایف اتمیک، دسترسی‌ها، سلسله‌مراتب، شورا و مشورت

> مدل: **۵ سطح، ۷۹ نقش، ۳۷۲ وظیفه اتمیک، ۴۲ مجوز، ۱۰ فاز مشارکت، ۶ عضو شورای ثابت، ۷ گام مشورت، ۸ گام چرخه Worker**.
> هر نقش = یک Worker (زیر-ایجنت) که با قرارداد مشخص برای وظیفه اتمیک استخدام می‌شود.

### ۱۶.۱. سطوح سلسله‌مراتب (۵ سطح)

```
سطح ۰ — شورا (Council): ۶ ثابت + ۴ متغیر | حق وتو
سطح ۱ — مدیران ارشد (Seniors): ۸ نقش
سطح ۲ — متخصصان (Specialists): ۳۰ نقش
سطح ۳ — مجریان (Executors): ۱۸ نقش
سطح ۴ — کارگران اتمیک (Atomic Workers): ۸ نقش
Cross-cutting: Code Reviewer, Security Auditor, Doc Writer, Test Writer, Release Manager
```

**قواعد:** Worker سطح N فقط از N-1 دستور می‌گیرد؛ مشورت عمودی مجاز؛ مشورت افقی نیازمند ثبت؛ سطح ۰ الزام‌آور برای همه.

### ۱۶.۲. خلاصه شمارش

| سطح | تعداد نقش | وظایف اتمیک |
|------|----------|-------------|
| ۰ (شورا) | ۱۰ | ۶۷ |
| ۱ (ارشد) | ۸ | ۸۸ |
| ۲ (متخصص) | ۳۰ | ۱۳۲ |
| ۳ (مجری) | ۱۸ | ۶۲ |
| ۴ (اتمیک) | ۸ | ۸ |
| Cross-cutting | ۵ | ۱۵ |
| **جمع** | **۷۹ نقش** | **۳۷۲ وظیفه اتمیک** |

> ⚠️ شمارش‌های سازمانی (نقش، وظیفه، مجوز) باید از `RULES.json` (`org_model`) تولید شوند و در متن هاردکد نشوند.

### ۱۶.۳. نمونه وظایف اتمیک (از ۳۷۲)

| نقش | Task ID | وظیفه | Output | DoD |
|------|---------|-------|--------|-----|
| Council Architect | `T-000-01` | تأیید ADR | امضا در ADR | `approved_by` ثبت شده |
| Council Security | `T-001-02` | **وتوی امنیتی مطلق** | `security-veto.md` | دلیل + CVE/منبع |
| Security Engineer | `T-011-03` | بازبینی XSS | `xss-review.md` | همه escape شده |
| Chief Architect | `T-010-02` | نوشتن ADR | `ADR/NNN.md` | قالب MADR |
| Database Lead | `T-014-02` | تعریف Schema | `schema.sql` | Dialect-aware |
| Core Developer | `T-048-01` | `spl_autoload_register` | `autoloader.php` | PSR-4 + self-contained |
| Code Reviewer | `T-074-03` | تأیید merge | `merge-approved.md` | SoD رعایت شده |
| Release Manager | `T-005-01` | تعیین نسخه | `VERSION` | SemVer منطقی |

> **کل ۳۷۲ وظیفه** در `docs/org/04-atomic-tasks/by-role/` و `by-phase/` مستند می‌شود؛ هر وظیفه دارای `input`، `output`، `DoD` و `dependencies` است.

### ۱۶.۴. ماتریس دسترسی (۴۲ مجوز)

| دسته | مجوزها |
|------|--------|
| **خواندن** | `read-all`, `read-architecture`, `read-security`, `read-quality`, `read-database`, `read-frontend`, `read-performance`, `read-deployment`, `read-mcp`, `read-rea`, `read-crypto`, `read-graph` |
| **نوشتن** | `write-core`, `write-architecture`, `write-security`, `write-database`, `write-frontend`, `write-performance`, `write-deployment`, `write-mcp`, `write-rea`, `write-crypto`, `write-graph`, `write-docs`, `write-tests`, `write-release` |
| **تأیید** | `approve-architecture`, `approve-security`, `approve-quality`, `approve-performance`, `approve-migration`, `approve-merge`, `approve-release`, `approve-mcp` |
| **وتو** | `veto-architecture`, `veto-security`, `veto-quality`, `veto-performance`, `veto-compliance` |
| **اجرا** | `exec-migration`, `exec-release`, `exec-deploy` |

**قواعد SoD:**
- **SoD-1:** هیچ Worker هم‌زمان `write-X` و `approve-X` ندارد
- **SoD-2:** `approve-release` نیازمند ۲ امضا (Release Manager + ۱ شورا)
- **SoD-3:** `veto-security` فقط توسط Council Security یا Security Engineer
- **SoD-4:** `exec-migration` فقط توسط Database Lead یا DB Migration Specialist
- **SoD-5:** Code Reviewer نمی‌تواند نویسنده همان کد باشد

### ۱۶.۵. ماتریس RACI (نمونه)

| وظیفه | R (Responsible) | A (Accountable) | C (Consulted) | I (Informed) |
|--------|----------------|-----------------|---------------|--------------|
| انتخاب معماری | Chief Architect | Council Architect | همه متخصصان | همه |
| تأیید ADR | Council Architect | Council Architect | Security Engineer | Chief Architect |
| پیاده‌سازی Core | Core Developer | Chief Architect | DB Lead, Frontend Lead | QA Lead |
| بازبینی امنیتی | Security Engineer | Council Security | Security Auditor | همه |
| اجرای تست | Test Writer | QA Lead | Performance Lead | همه |
| انتشار | Release Manager | Council Architect | همه شورا | همه |

> RACI برای همه وظایف کلیدی در `docs/org/06-raci.md` مستند می‌شود. هر وظیفه **دقیقاً یک A** دارد .

### ۱۶.۶. فازهای مشارکت (۱۰ فاز)

| فاز | نام | نقش‌های اصلی | خروجی | حداقل حضور |
|------|-----|--------------|--------|-------------|
| **F1** | تحلیل نیاز | Chief Architect, Integration Lead | `requirements.md` | ۱ سطح ۱ |
| **F2** | طراحی اولیه | Chief Architect, Council Architect | `design-draft.md` + ADR | ۲ سطح ۱ |
| **F3** | تأیید طراحی | Council (۵ عضو) | `design-approved.md` | ۵ سطح ۰ |
| **F4** | پیاده‌سازی | Executors + Specialists | کد + PHPDoc | ۱ سطح ۳ |
| **F5** | بازبینی کد | Code Reviewer, Security Auditor | `review.md` | ۱ سطح ۲ |
| **F6** | تست | Test Writer, QA Lead | `test-report.md` | ۱ سطح ۱ |
| **F7** | بازبینی امنیتی | Security Engineer, Council Security | `security-signoff.md` | ۱ سطح ۰ |
| **F8** | مستندسازی | Documentation Writer | `docs/*` | ۱ سطح ۳ |
| **F9** | آماده‌سازی انتشار | Release Manager, QA Lead | `RELEASE.md` + `VERSION` | ۲ سطح ۱ + ۱ سطح ۰ |
| **F10** | انتشار | Release Manager + Council | tag + CHANGELOG | ۴ سطح ۰ |

**قواعد:** F7 الزاماً با Council Security؛ F3 الزاماً ۵ عضو شورا (هم‌راستا با حد نصاب جدول ۱۶٫۷)؛ F10 الزاماً ۵ امضا (۴ عضو شورا + Release Manager).

### ۱۶.۷. شورا (۶ ثابت + ۴ متغیر)

**اعضای ثابت:** Council Architect، Council Security، Council Quality، Council Performance، Council Compliance، Release Manager.

**اعضای متغیر:** Database Lead، Crypto Specialist، Frontend Lead، Integration Lead (بر اساس موضوع).

**مکانیزم تصمیم‌گیری (۷ حالت):**

| نوع | مکانیزم | حد نصاب | وتو |
|-----|---------|---------|------|
| معماری | اکثریت ۴ از ۶ | ۵ عضو | Council Architect |
| **امنیت** | **اجماع کامل** | ۶ عضو | **مطلق — هیچ‌کس نمی‌تواند نقض کند** |
| کیفیت | اکثریت ۴ از ۶ | ۵ عضو | Council Quality |
| عملکرد | اکثریت ۴ از ۶ | ۵ عضو | Council Performance |
| انطباق | اجماع ۵ از ۶ | ۶ عضو | Council Compliance |
| انتشار | اکثریت ۴ از ۶ عضو ثابت | ۵ عضو | هر عضو ثابت |
| اضطرار | Chief Architect + Security Engineer | ۲ نفر | تأیید بعدی شورا در ۲۴ ساعت |

> **قاعده واحد امضا:** «تأیید انتشار» (`approve-release`) = ۲ امضا (Release Manager + ۱ عضو شورا — مطابق SoD-2)؛ «اجرای انتشار» (`exec-release`) = ۵ امضا (۴ عضو ثابت شورا + Release Manager — مطابق قاعده ۱۸ پرامپت، قاعده ۱۰ AGENTS.md و فاز F10).

### ۱۶.۸. پروتکل مشورت (۷ گام + SLA)

| گام | اقدام | مسئول | SLA |
|------|-------|-------|-----|
| C1 | ثبت درخواست | Requester | فوری |
| C2 | تعیین Consultant | Requester / Manager | ۲ ساعت |
| C3 | تحلیل و پاسخ | Consultant | **۲۴ ساعت** |
| C4 | بررسی ریسک | Consultant + Security (اگر امنیتی) | ۴۸ ساعت |
| C5 | تصمیم | Requester | ۲۴ ساعت |
| C6 | ثبت مخالفت | Consultant | — |
| C7 | آرشیو | Doc Writer | — |

**قواعد:** مشورت امنیتی برای `severity >= high` **اجباری**؛ مخالفت کتبی Consultant **الزاماً** در ADR ثبت می‌شود.

### ۱۶.۹. چرخه عمر Worker (۸ گام)

```
1. Requirement → نقش مورد نیاز
2. Hiring → worker-template.md پر می‌شود
3. Contract → scope + permissions + budget + DoD
4. Onboarding → RULES.json + docs/* خوانده می‌شود
5. Execution → وظیفه اتمیک انجام می‌شود
6. Review → Code Reviewer تأیید/رد
7. Dissolution → Worker منحل، خروجی آرشیو
8. Retrospective → lessons-learned.md
```

**قالب قرارداد Worker (۸ بخش):** Identity، Task (Atomic)، Authority، Relationships، Budget، DoD، Escalation، Lifecycle.

### ۱۶.۱۰. گراف وابستگی (DAG)

```
F1 → T-010-01 → T-010-02 → T-000-01 → T-010-03
                                      ├─ T-048-01..03 (Autoloader)
                                      ├─ T-049-01..04 (Router)
                                      └─ T-050-01..04 (DI)

F1 → T-011-01 → T-011-10 → T-001-02 (Veto — شرطی)
F1 → T-014-01 → T-014-02 → T-020-01 → T-061-01
F1 → T-017-01 → T-040-01 → T-064-01..04

F4 → F5 (T-074-01) → F6 (T-012-03..07) → F7 (T-011-02..09)
  → F8 (T-076-01..03) → F9 (T-005-01..04) → F10 (T-005-05)
```

**قواعد:** DAG بدون چرخه؛ وظایف سطح ۰ ریشه؛ وظایف سطح ۴ برگ.

### ۱۶.۱۱. ادغام در مهارت

**`RULES.json` — بخش `org_model`:**

```json
{
  "org_model": {
    "version": "2.0",
    "hierarchy_levels": 5,
    "total_roles": 79,
    "total_atomic_tasks": 372,
    "total_permissions": 42,
    "workers_per_role": 1,
    "worker_lifecycle": "per-atomic-task",
    "profiles": {
      "full": { "phases": "F1-F10", "max_concurrent_workers": null },
      "solo": { "phases": "F1,F2,F4,F5,F6,F9,F10", "max_concurrent_workers": 7 }
    },
    "worker_template": "scaffolds/workers/_template.md",
    "council": {
      "fixed_members": 6,
      "variable_members": 4,
      "security_veto": "absolute",
      "release_signatures_required": 5
    },
    "consultation": {
      "sla_hours": 24,
      "critical_sla_hours": 4,
      "mandatory_for_severity": ["critical", "high"]
    },
    "participation_phases": 10,
    "separation_of_duties": true,
    "atomic_tasks_required": true,
    "dag_validation": true
  }
}
```

**`AGENTS.md` — ۱۵ قاعده سازمانی:**

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

## ۱۷. راهنمای اجرا

### گام‌های اجرا

1. **آماده‌سازی:** این فایل را به ایجنت بدهید.
2. **تولید قوانین:** `RULES.json` (شامل `org_model` v2).
3. **تولید AGENTS.md و MCP.md.**
4. **تولید مستندات:** همه `docs/*` از جمله `docs/org/`.
5. **تولید Scaffolds:** شامل `scaffolds/workers/` (۷۹ فایل).
6. **خودارزیابی:** چک‌لیست بخش ۱۴.
7. **نسخه‌گذاری:** `v0.0.1`، `CHANGELOG.md`.

### خروجی نهایی مطلوب

```
Skill v0.0.1 ready.
Files: 228 + ADRs | Docs: 103 | Scaffolds: 79 workers + 25 code | Examples: 9
Security audit: PASS
PHPStan level: 8 (clean) | PSR-12: PASS
Architectures: MVC, HMVC, Modular, Microservice, Serverless, REST,
              DDD, Hexagonal, Onion, Vertical Slice, EDA, ES, CQRS,
              Pipeline, Actor Model
Runtime: FrankenPHP (static), RoadRunner (worker)
Infrastructure: Podman (rootless), Nix/devenv, OpenTelemetry (manual),
                Redis (ext-redis / file cache)
MCP: Context7 (local+remote + API key), Tier-1/2, VibeShell
REA: code, API, tooling | EC-crypto: sodium, compat, ECDSA, RFC 7748/8032
Graph: adjacency list, BFS/DFS, GraphML/Cytoscape
ORG: 79 roles | 372 atomic tasks | 42 permissions | 5 levels |
     10 phases | 6 council | 7-step consultation | 8-step lifecycle
Limitations documented: 16 items
```

### سیاست بازبینی دوره‌ای منابع

- هر ۳ ماه یک‌بار، همه لینک‌های کاتالوگ (بخش ۳ و ۵) با اسکریپت بررسی وضعیت (HTTP 200) بازرسی شوند.
- لینک‌های 404 در همان جلسه حذف یا اصلاح و ستون «آخرین تأیید» به‌روز شوند.
- ادعاهای عددی (latency، req/s و…) فقط با برچسب «خوداظهاری» یا بنچمارک مستقل قابل بازتولید بمانند.
- لایسنس کدهای vendored در هر بازبینی کنترل شود (ستون «لایسنس» کاتالوگ).

**نسخه:** `0.0.1` | **تاریخ:** 2026-11-25 | **مجوز:** MIT

> **یادداشت پایانی:** این فایل یک «پرامپت زنده» است. هر بار مخزن جدیدی کشف شد، به کاتالوگ اضافه کنید و `VERSION` را ارتقا دهید. فلسفه «بازآفرینی آگاهانه چرخ» یعنی مهارت شما **همیشه در حال تکامل** است — اما هرگز به سمت وابستگی خارجی منحرف نمی‌شود. محدودیت‌ها را صادقانه مستند کنید؛ یک مهارت خوب، محدودیت‌های خود را می‌شناسد — و یک مهارت عالی، **سازمان خود را نیز می‌شناسد**.