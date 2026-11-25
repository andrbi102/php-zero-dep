# AGENTS.md — قواعد ایجنت‌های php-zero-dep

> نسخه مهارت: **0.0.1** | مدل سازمانی: v2.0 | منبع حقیقت شمارش‌ها: `RULES.json`

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

## B. قیدهای سخت تولید کد (از پرامپت اصلی SPEC بخش ۲)

1. هرگز `composer.json` تولید نکن و به پکیج Composer ارجاع نده.
2. هرگز کتابخانه ثالث استفاده نکن — فقط PHP 8.2+ هسته و افزونه‌های مجاز.
3. هر فایل با `declare(strict_types=1);` و PHPDoc شامل `@package`, `@version`, `@security-level`.
4. هر کلاس تک‌فایل و خودکفا.
5. هر کوئری PDO Prepared Statement.
6. هر خروجی escape شود (`htmlspecialchars(..., ENT_QUOTES)`).
7. کوکی نشست: HttpOnly + Secure + SameSite=Strict.
8. هر فرم توکن CSRF امضاشده HMAC.
9. هش رمز: `password_hash(PASSWORD_ARGON2ID)`.
10. هیچ شکست خاموشی: لاگ یا rethrow با context.
11. Event Dispatcher / Command Bus / Pipeline دست‌نویس (از scaffolds).
12. هدرهای امنیتی: CSP، HSTS، X-Content-Type-Options، X-Frame-Options، Referrer-Policy.
13. توابع ممنوع: `eval()`, `exec()`, `shell_exec()`, `system()`, `passthru()`, `proc_open()`, `popen()` — مگر با مستندسازی صریح و sandbox.

## C. قاعده منابع خارجی (بدون توهم API)

> هرگاه ایجنت قصد تولید کد برای کتابخانه خارجی دارد، ابتدا `resolve-library-id`
> (مگر شناسه `/owner/repo` داده شده باشد)، سپس `query-docs` با پرسش مشخص و زبان PHP.
> اگر Context7 در دسترس نیست، اعلام محدودیت کن و بر دانش خود تکیه نکن.

## D. نسخه PHP

baseline: **8.3** (اگر نامشخص). 8.4/8.5 فقط با تأیید صریح. PHP 8.2 در
2026-12-31 EOL می‌شود. جزئیات: `docs/modern-php/`.

## E. مراجع

- ساختار و شمارش‌ها: `RULES.json` → `org_model`
- شرح کامل مدل سازمانی: `docs/org/`
- قرارداد Worker: `scaffolds/workers/_template.md`
- چک‌لیست پذیرش: `SKILL.md` بخش درگاه‌های کیفیت
