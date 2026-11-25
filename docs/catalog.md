# کاتالوگ مخازن و منابع (تأییدشده ۲۰۲۶-۱۱-۲۵)

> استخراج‌شده از SPEC بخش ۳ (کاتالوگ) و بخش ۵ (ابزارهای REA).
> بازبینی سه‌ماهه: `bash scripts/check-links.sh` — لینک ۴۰۴ در همان جلسه حذف/اصلاح و «آخرین تأیید» به‌روز شود.

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

## ۶. مهارت‌ها و الگوهای مرجع ایجنت (v0.0.2)

> پنج مخزن مرجع که مهارت `php-zero-dep` در v0.0.2 از آن‌ها الگوبرداری کرده است.
> جزئیات کاملِ هر الگو (چه الگوبرداری شد، چه نشد) در [`docs/references/`](references/00-index.md).

| مخزن | لینک | چه الگوبرداری شد | لایسنس | سند |
|------|------|------------------|--------|-----|
| **NousResearch/hermes-agent** | [github.com/NousResearch/hermes-agent](https://github.com/NousResearch/hermes-agent) | مدل ارکستراسیون Worker (delegate/kanban/review/moa/estop, IterationBudget, DenialCircuitBreaker, handoff, capability token HMAC) — الگوبرداری کامل برای بخش ورکرها | MIT | [references/01](references/01-hermes-agent.md) |
| **blader/humanizer** | [github.com/blader/humanizer](https://github.com/blader/humanizer) | کاتالوگ ۲۶-الگویی «بوی AI» + پایپلاین mark→draft→claim-check برای متن UI تولیدشده | MIT | [references/02](references/02-humanizer.md) |
| **firecrawl/firecrawl** | [github.com/firecrawl/firecrawl](https://github.com/firecrawl/firecrawl) | واترفال موتور، حذف عناصر ناخواسته، robots.txt، فیلتر لینک، charset — در PHP خالص (`scaffolds/web-fetch.php`) | AGPL-3.0/MIT | [references/03](references/03-firecrawl.md) |
| **nextlevelbuilder/ui-ux-pro-max-skill** | [github.com/nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) | معماری توکن سه‌لایه + صدور دوگانه (PHP/JSON/CSS) از یک resolver | MIT | [references/04](references/04-ui-ux-pro-max.md) |
| **nvidia/skillspector** | [github.com/nvidia/skillspector](https://github.com/nvidia/skillspector) | ممیزی امتیازی ۰-۱۰۰، completeness ledger، baseline suppression، قرارداد JSON (`scripts/skill_score.py`) | Apache-2.0 | [references/05](references/05-skillspector.md) |

**قاعدهٔ الگوبرداری:** فقط *ایدهٔ ساختاری* الگوبرداری شد؛ هیچ کد از مخازن
AGPL/GPL کپی نشده (انتقالی بودن رعایت شد). همهٔ الگوها در PHP خالص بازنویسی
شدند — هیچ وابستگی اضافه نشد (قید ۱-۲ نقض‌ناپذیر).
