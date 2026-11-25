# نقشه پوشه org — مدل سازمانی v2

> نسخه مهارت: **0.0.2** | منبع حقیقت: `RULES.json` → `org_model` (شمارش‌ها از آنجا تولید می‌شوند)

## مسیرهای خواندن — کدام فایل، کِی؟

| اگر می‌خواهی… | بخوان (به ترتیب) |
|----------------|-------------------|
| مدل را از صفر بفهمی | `01-model.md` ← `02-hierarchy.md` ← `03-roles.md` |
| یک Worker استخدام کنی | `08-lifecycle.md` ← `scaffolds/workers/_template.md` ← قرارداد نقش در `scaffolds/workers/T-*.md` |
| بدانی چه کسی چه مسئولیتی دارد | `05-raci.md` ← `raci/tasks/` |
| تصمیم شورا بخواهی/ثبت کنی | `06-council.md` ← `council-log.md` (ریشه) ← `council/sessions/` |
| مشورت کنی | `07-consultation.md` ← `templates/consultation-template.md` ← `consultation/log/` |
| ترتیب وظایف را بچینی | `09-dag.md` ← `atomic-tasks/by-phase/F*.md` |
| پروفایل solo/full انتخاب کنی | `10-profiles.md` |
| مطمئن شوی کسی هم می‌سازد هم تأیید نمی‌کند | `11-sod.md` ← `scripts/verify_org.py` |
| بدانی Worker چگونه هم‌نفس می‌شوند (delegate/kanban/review/estop) | `12-worker-orchestration.md` ← `scaffolds/worker-orchestrator.php` ← `docs/references/01-hermes-agent.md` |
| فهرست وظایف یک نقش را ببینی | `atomic-tasks/by-role/T-NNN.md` (کارت کامل نقش) |
| فهرست وظایف یک فاز را ببینی | `atomic-tasks/by-phase/F*.md` |

**قاعده طلایی:** دادهٔ نهایی همیشه از `RULES.json` می‌آید؛ فایل‌های این
پوشه «نمای قابل‌فهم» همان داده‌اند. هر عددی که اینجا می‌بینی با
`python3 scripts/verify_org.py` قابل راستی‌آزمایی است.

## ساختار

```
docs/org/
├── 00-index.md            ← همین فایل
├── 01-model.md            ← نمای کلی مدل (۵ سطح، ۷۹ نقش، ۳۷۲ وظیفه، ۴۲ مجوز)
├── 02-hierarchy.md        ← سطوح سلسله‌مراتب و قواعد آن
├── 03-roles.md            ← نمای کلی نقش‌ها + پیوند فایل هر سطح
├── 04-permissions.md      ← ۴۲ مجوز در ۵ دسته
├── 05-raci.md             ← ماتریس RACI
├── 06-council.md          ← شورا (۶ ثابت + ۴ متغیر) و ۷ مکانیزم تصمیم
├── 07-consultation.md     ← پروتکل مشورت (۷ گام + SLA)
├── 08-lifecycle.md        ← چرخه عمر Worker (۸ گام) و ۸ بخش قرارداد
├── 09-dag.md              ← گراف وابستگی وظایف (DAG)
├── 10-profiles.md         ← پروفایل‌های full و solo
├── 11-sod.md              ← تفکیک وظایف SoD-1..5 (با استثنای مستند release)
├── 12-worker-orchestration.md ← مدل ارکستراسیون Worker (الگوی hermes-agent) [v0.0.2]
├── roles/by-level/        ← جزئیات نقش‌ها به تفکیک سطح (۶ فایل)
├── atomic-tasks/by-role/  ← ۷۹ فایل (کارت کامل هر نقش: وظایف + مجوزها)
├── atomic-tasks/by-phase/ ← ۱۰ فایل (هر فاز: وظایف همان فاز)
├── raci/tasks/            ← RACI وظایف کلیدی
├── council/sessions/      ← صورت‌جلسات (به‌علاوه council-log.md در ریشه)
├── consultation/log/      ← آرشیو مشورت‌ها
└── templates/             ← قالب قرارداد Worker / ADR / مشورت
```

## شمارش‌ها (تولیدشده از data — نه هاردکد)

| سطح | نقش | وظایف اتمیک |
|------|-----|-------------|
| ۰ شورا | 10 | 67 |
| ۱ ارشد | 8 | 88 |
| ۲ متخصص | 30 | 132 |
| ۳ مجری | 18 | 62 |
| ۴ اتمیک | 8 | 8 |
| Cross-cutting | 5 | 15 |
| **جمع** | **79** | **372** |

راستی‌آزمایی: `python3 scripts/verify_org.py`

---

> مهارت `php-zero-dep` — نسخه `0.0.2`
