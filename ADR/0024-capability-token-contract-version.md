# ADR-0024: capability token (HMAC) و contract_version برای Worker

- وضعیت: Accepted
- تاریخ: 2026-11-25
- تصمیم‌گیرنده: Crypto Specialist (T-017-01)
- approved_by: Council Architect (T-000-01)

## Context (زمینه)
بدون یک توکن قابلیت، scope/metadata یک Worker پس از استخدام قابل‌تغییر بود
(قابل جعل). و بدون نسخهٔ قرارداد، یک Worker با قرارداد قدیمی می‌توانست با
سیستم جدید تعامل کند (ناخوش‌سازگاری خاموش). hermes-agent هر دو را با
`capability` HMAC + `contract_version` حل کرده است.

## Decision (تصمیم)
هر Worker هنگام spawn یک `capability_token` می‌گیرد:
`hmac:<contract_version>:<sig>` که `sig = hash_hmac('sha256',
worker_id:contract_version, runKey)`. `contract_version` با نسخهٔ مهارت
(`0.0.2`) برابر است. توکن با `hash_equals` راستی‌آزمایی می‌شود. کلید per-run
تصادفی (sandbox ریست → کلید پایدار بین runs نداریم — محدودیت مستند).
پیاده‌سازی در `WorkerOrchestrator::issueCapabilityToken()`.

## Consequences (پیامدها)
+ scope/metadata Worker پس از spawn قابل‌جعل نیست (HMAC).
+ نسخهٔ قرارداد صریح؛ ناخوش‌سازگاری قابل‌تشخیص.
+ `hash_equals` ضد timing attack.
− کلید per-run (نه پایدار cross-session) — در sandbox ریست، توکن‌ها بین
  sessions معنی ندارند؛ محدودیت مستند در `docs/references/01-hermes-agent.md`.
− اضافه‌بار رمزنگاری هر spawn (سبک — HMAC سریع).
