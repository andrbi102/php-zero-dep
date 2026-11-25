# ۰۹ — معماری توکن طراحی سه‌لایه (الگوبرداری از ui-ux-pro-max)

> نسخه مهارت: **0.0.2** | مرجع: [`docs/references/04-ui-ux-pro-max.md`](../references/04-ui-ux-pro-max.md)
> اسکلت: [`scaffolds/design-tokens.php`](../../scaffolds/design-tokens.php) | ADR: [0021](../../ADR/0021-design-token-architecture.md)

## چرا این سند

خروجی PHP این مهارت، رابط کاربری هم تولید می‌کند (وبلاگ، فروشگاه،
پورتفولیو). یک رابط بدون سیستم توکن، رنگ‌های پراکنده و فاصله‌های ناهمگن
می‌شود. این سند یک معماری توکن **سه‌لایه** را معرفی می‌کند که از یک
resolver واحد در سه فرمت صادر می‌شود — بدون هیچ وابستگی (بدون Tailwind،
بدون Style Dictionary، بدون پکیج).

## سه لایه

```
لایهٔ ۱ — primitive     (خام، بدون معنی)
   color.blue-600 = #1d4ed8
   spacing.4      = 1rem
   radius.md      = 0.5rem
        ↓
لایهٔ ۲ — semantic      (نقش، مستقل از رنگ خام)
   color.primary   = {color.blue-600}
   color.on-primary= {color.white}
   color.destructive = {color.red-600}
        ↓
لایهٔ ۳ — component     (قرارداد مؤلفه)
   button.bg    = {color.primary}
   button.radius= {radius.control}   ← {radius.md}
```

**چرا سه لایه:** primitive فقط مقادیر خام است (هیچ تصمیم طراحی). semantic
نقش‌ها را تعریف می‌کند (`primary`, `destructive`, `border`) — اگر تم عوض
شد، فقط primitive را عوض می‌کنی و semantic به‌روز می‌شود. component قرارداد
مؤلفه را تثبیت می‌کند (دکمه همیشه `radius.control` و `shadow.control`).

## نقش‌های رنگ semantic (الگوبرداری‌شده از design_system.py)

| نقش | معنا | مرجع primitive |
|------|------|----------------|
| `primary` | رنگ برند، CTA | `blue-600` |
| `on-primary` | متن روی primary | `white` |
| `secondary` | عمل کم‌اهمیت | `gray-500` |
| `accent` | تأکید | `blue-500` |
| `background` | پس‌زمینهٔ صفحه | `gray-50` |
| `foreground` | متن اصلی | `gray-900` |
| `card` | پس‌زمینهٔ کارت | `white` |
| `muted` | متن کم‌رنگ | `gray-100` |
| `border` | خطوط جداکننده | `gray-200` |
| `destructive` | حذف/خطا | `red-600` |
| `success` | موفقیت | `green-600` |
| `warning` | هشدار | `amber-500` |
| `ring` | حلقهٔ focus | `blue-500` |

## صدور دوگانه/سه‌گانه از یک resolver

```php
$dt = new DesignTokens();
$dt->export('php');  // define('TOKEN_COLOR_PRIMARY', '#1d4ed8'); ...
$dt->export('json'); // W3C DTCG: {"semantic":{"color-primary":{"$value":"#1d4ed8","$type":"color"}}}
$dt->export('css');  // :root { --color-primary: #1d4ed8; }
```

- **PHP constants:** برای inline style در view.php یا config ثابت.
- **JSON (DTCG):** برای تبادل با ابزار طراحی خارجی (Figma Tokens، مثلاً).
- **CSS vars:** برای `:root` در stylesheet — بهترین گزینه برای production چون
  امکان theme-switch در runtime را می‌دهد (و بدون rebuild).

## حالت‌های تعامل (الگوبرداری از states-and-variants.md)

هر مؤلفه چهار حالت دارد — توکن component باید هر چهار را پوشش دهد:

| حالت | نمونه |
|------|-------|
| default | `button.bg = primary` |
| hover | تیره‌تر (`primary` → `blue-700`) — یا فقط `brightness(0.9)` |
| focus | `outline: 2px solid {color.ring}; outline-offset: 2px` |
| disabled | `opacity: 0.5; cursor: not-allowed` (نه تغییر رنگ به معنای جدید) |

**قاعده:** حالت‌ها را با opacity/outline تغییر بده، نه با رنگ semantic جدید —
تا تم‌پذیری حفظ شود.

## دسترس‌پذیری (الگوبرداری از quick-reference §1)

- کنتراست متن: **۴.۵:۱** (AA) / ۷:۱ (AAA). `on-primary` روی `primary` باید
  این نسبت را داشته باشد. در пример ما: `#ffffff` روی `#1d4ed8` ≈ ۸.۶:۱ ✓.
- کنتراست غیرمتن: **۳:۱** (border/icon).
- هدف لمسی: **۴۴×۴۴ dp** (iOS) / ۴۸×۴۸ dp (Android) — `button` باید حداقل
  این ارتفاع را داشته باشد.
- `focus` هرگز با `outline: none` حذف نمی‌شود مگر با جایگزین قابل‌مشاهده.
- `prefers-reduced-motion`: انیمیشن را غیرفعال کن (`@media`).

## persistence با guard (الگوبرداری از `--force`)

وقتی توکن‌ها را به پروژهٔ کاربر صادر می‌کنی:

- به‌صورت `tokens.php` / `tokens.json` / `tokens.css` در مسیر ثابت بنویس.
- **skip-if-exists** مگر تأیید صریح کاربر (`--force`).
- **قبل از force، فایل موجود را بخوان** — شاید کاربر رنگ برند را عوض کرده.
- همیشه `MASTER` (semantic+primitive) را جدا از override صفحه‌ای نگه دار.

## چه چیزی الگوبرداری *نشد*

- ۱۹۴ palette آماده و ۷۴ font pairing (دادهٔ volumeی ui-ux-pro-max) — در
  v0.0.۲ فقط معماری + یک نقشهٔ پایه.
- موتور جستجوی BM25 روی CSV (ما پایگاه‌دانی در مستندات داریم).
- multi-platform manifests.

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: ui-ux-pro-max-skill (MIT)
