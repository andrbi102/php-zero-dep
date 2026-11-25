# ۰۸ — پالایش نثر: کاتالوگ «بوی AI» (الگوبرداری از humanizer)

> نسخه مهارت: **0.0.2** | مرجع: [`docs/references/02-humanizer.md`](../references/02-humanizer.md)
> اسکلت: [`scaffolds/humanizer.php`](../../scaffolds/humanizer.php) | ADR: [0019](../../ADR/0019-humanizer-prose-polish.md)

## چرا این سند

خروجی این مهارت، کد PHP است نه نثر ادبی — اما کد شامل **رشته‌های
انسانی‌خواندنی** است: hero، CTA، tooltip، button label، empty-state،
error-message، README محصول. این رشته‌ها اغلب «بوی AI» می‌گیرند: کلمات
بادکنکی (`seamless`, `powerful`)، تثلث‌های مکانیکی، disclaimerهای
chatbot. این سند کاتالوگ الگوهایی است که باید در F5 بازبینی، **به‌نام**
شناسایی و اصلاح شوند — نه به‌حسب.

## کاتالوگ (۲۶ الگو در ۶ گروه، تطبیق‌یافته برای متن UI تولیدشده)

پیاده‌سازی قابل‌ممیزی ایستا در `scaffolds/humanizer.php::SMELLS`.

### گروه A — inflation (بادکردن)

| الگو | نشانه | وزن |
|------|--------|-----|
| AI stock words | `seamless`, `leverage`, `robust`, `cutting-edge`, `powerful`, `comprehensive`, `elevate`, `empower`, `unlock` | ۱۲-۱۵ |
| inflated opener | «در دنیای پرشتاب امروز…» | ۱۸ |
| not-X-but-Y | «نه تنها X، بلکه Y» | ۱۰ |

**درست:** «ثبت‌نام ۳۰ ثانیه طول می‌کشد.» / **غلط:** «با تجربه‌ای یکپارچه و
قدرتمند، ثبت‌نام را متحول کنید.»

### گروه B — rhythm

| الگو | نشانه | وزن |
|------|--------|-----|
| triad | سه صفت کاما‌جداشده (`سریع، امن، مقیاس‌پذیر`) | ۸ |
| em-dash overuse | ≥ ۳ em-dash در یک پاراگراف کوتاه | ۱۰ |
| repeated openings | هر جمله با همان ساز شروع شود | ۸ |

### گروه C — formatting

| الگو | نشانه | وزن |
|------|--------|-----|
| decorative bold | `**کلمه**` در متن ساده (نه markdown UI واقعی) | ۶ |
| curly quotes | نویسه‌های " " ' ' در متن لاتین | ۵ |

### گروه D — chatbot residue (بدترین‌ها)

| الگو | نشانه | وزن |
|------|--------|-----|
| AI disclaimers | «به‌عنوان یک AI، من نمی‌توانم…»، «لطفاً توجه کنید که…»، «اگه سوالی بود…» | ۲۵ |
| meta-writing | «جای تأکید دارد که…»، «ارزش یادآوری دارد که…» | ۱۸ |

این‌ها در خروجی production **مطلقاً** نباید باشند — وزن بالا یعنی نمره را
فوراً بالای آستانه می‌برند.

### گروه E — wrong-reader re-explaining

| الگو | نشانه | وزن |
|------|--------|-----|
| re-explaining | «به‌سادگی…»، «فقط…»، «به‌راحتی…» | ۷ |
| you-can-easily | «شما به‌راحتی می‌توانید…»، «کافیست…» | ۱۲ |

## پایپلاین سه‌گانه (mark → draft → claim-check)

```
۱. mark        → Humanizer::smells($text)   → فهرست tells با گروه/وزن
۲. draft       → بازنویسی: merge/split/reorder + افزودن specifics
۳. claim-check → Humanizer::claimCheck($before, $after, $facts)
                 → هر fact (نام، عدد، تاریخ، لینک) باید در after زنده بماند
۴. final       → فقط اگر claimCheck.ok === true
```

**قاعدهٔ هم‌وقوعی (مهم):** یک tell ضعیفِ تنها (مثلاً یک تثلث در فهرست
ویژگی) اصلاح نمی‌شود — فقط وقتی tells **خوشه‌بندی** کنند (≥ ۲ tell متمایز
یا نمره ≥ ۳۰) اقدام کن. این، جلوی over-correction مکانیکی را می‌گیرد.

```php
$h = new Humanizer();
$r = $h->report($heroText);              // ['score'=>.., 'should_polish'=>.., 'tells'=>[..]]
if ($r['should_polish']) {
    $rewritten = polish($heroText);      // انسانی‌سازی
    $check = $h->claimCheck($heroText, $rewritten, ['۳۰ ثانیه', 'ثبت‌نام', '/signup']);
    if (!$check['ok']) { /* fact گم شد → بازگردان یا اصلاح */ }
}
```

## file-mode discipline (از humanizer SKILL.md)

وقتی یک فایل PHP را برای پالایش نثر باز می‌کنی:

- **فقط رشته‌های انسانی** را ویرایش کن: مقادیر string literal که به کاربر
  نمایش داده می‌شوند (label، title، description، placeholder).
- **دست‌نزن** به: نام متغیر، نام کلاس، مسیر فایل، کلید آرایه، کلید i18n،
  identifier، comments که منطق را توضیح می‌دهند.
- یک گذر، یک فایل — نه بازنویسی ساختاری.

## درگاه کیفیت (F5)

به `[ ]` نمرهٔ `Humanizer::score()` روی همه رشته‌های نمایشی اصلی < ۳۰
اضافه کن. اگر بالاتر است، بازبین در `review.md` با severity medium ثبت
می‌کند (نه veto — veto فقط امنیتی است).

## چه چیزی الگوبرداری *نشد*

- بازنویسی واقعی با LLM (اسکلت ما فقط **آشکارساز** است؛ بازنویسی بر عهده
  ایجنت/انسان با کاتالوگ بالا).
- voice-matching با نمونهٔ پروژه (در صورت نیاز نسخهٔ بعدی).

---

> مهارت `php-zero-dep` — نسخه `0.0.2` | الگو: humanizer (MIT)
