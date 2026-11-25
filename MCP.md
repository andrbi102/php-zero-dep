# MCP.md — یکپارچه‌سازی Model Context Protocol

> نسخه مهارت: **0.0.1** | MCP یک **پروتکل** است نه پکیج PHP — سرور در فرایند
> جداگانه اجرا می‌شود و کد PHP تولیدشده هیچ import از MCP ندارد.

## ۱. Context7 MCP (Tier-1 — پیش‌فرض)

| ویژگی | توضیح |
|--------|-------|
| ابزارها | `resolve-library-id` ← سپس `query-docs` (ترتیب اجباری) |
| نسخه‌محور | با ذکر نسخه، مستندات همان نسخه |
| Local | `npx -y @upstash/context7-mcp` (نیازمند Node.js) — روش جدید: `npx ctx7 setup` |
| Remote | `https://mcp.context7.com/mcp` با هدر `Authorization: Bearer <API_KEY>` — بدون Node.js؛ کلید رایگان از context7.com/dashboard؛ بدون کلید، محدودیت نرخ شدید |

قاعده AGENTS.md (بخش C): مشورت اجباری پیش از تولید کد API کتابخانه خارجی؛
در نبود Context7 اعلام محدودیت — نه حدس.

## ۲. Tier-2 (با مجوز صریح کاربر)

Playwright MCP (تست مرورگر — فقط dev)، ابزارهای تحلیل با MCP server
(PhpCodeArcheology)، و سرورهای MCP خودتان با PHP خالص.

## ۳. سرور MCP با PHP خالص

- اسکلت خودت: `scaffolds/mcp-server.php` (JSON-RPC روی stdio؛ هر Tool یک
  فایل در `tools/` با اعتبارسنجی ورودی — نمونه: `scaffolds/tools/hello.php`)
- آماده: **VibeShell** — github.com/d3cline/VibeShell (تک‌فایلی PHP 7.4+،
  محافظت path traversal؛ GPL-3.0 — فقط به‌عنوان ابزار، کپی کد در خروجی MIT مجاز نیست)

## ۴. امنیت

مسیر مجاز سرور بسته؛ ورودی هر Tool اعتبارسنجی (خطای -32602)؛ کلید API از
env نه هاردکد و هرگز در لاگ؛ Remote فقط HTTPS.

## ۵. محدودیت‌ها

Local نیازمند Node.js؛ Remote نیازمند شبکه؛ context window محدود — پاسخ‌ها
خلاصه و هدفمند. **MCP ابزار توسعه است نه وابستگی runtime** (قید ۱۱ بخش ۱۲ SPEC).

جزئیات کامل: `docs/mcp/01..05`
