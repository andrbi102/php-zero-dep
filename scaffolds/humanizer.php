<?php

declare(strict_types=1);

/**
 * آشکارساز «بوی AI» در متن — الگوبرداری‌شده از blader/humanizer (MIT)
 *
 * کاتالوگ الگوی شماره‌گذاری‌شده + پایپلاین mark→draft→claim-check، اما
 * **بدون LLM**: فقط regex/قواعد رشته‌ای با ext-mbstring. نمرهٔ ۰-۱۰۰ +
 * قاعدهٔ هم‌وقوعی (تنها tell ضعیف = نادیده).
 *
 * کاربرد: بررسی رشته‌های انسانی‌خواندنی تولیدشده در خروجی PHP (hero، CTA،
 * tooltip، button label) پیش از تحویل — بخشی از درگاه کیفیت F5.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level quality
 * @pattern-source blader/humanizer (MIT) — SKILL.md §A-F
 */
final class Humanizer
{
    /** شدت: low alone → نادیده؛ خوشه‌بندی → اقدام (قاعدهٔ هم‌وقوعی humanizer). */
    private const ACTION_THRESHOLD = 30;
    private const MIN_TELLS_FOR_ACTION = 2;

    /** @var array<int,array{pattern:string,group:string,weight:int,label:string}> */
    private const SMELLS = [
        // گروه A — inflation / AI stock words
        ['pattern' => '/\b(seamless|seamlessly)\b/iu', 'group' => 'A-inflation', 'weight' => 15, 'label' => 'AI stock word: seamless'],
        ['pattern' => '/\b(leverag(e|ing))\b/iu', 'group' => 'A-inflation', 'weight' => 15, 'label' => 'AI stock word: leverage'],
        ['pattern' => '/\b(robust|cutting-edge|state-of-the-art|world-class|best-in-class)\b/iu', 'group' => 'A-inflation', 'weight' => 15, 'label' => 'AI stock word: robust/cutting-edge'],
        ['pattern' => '/\b(powerful|comprehensive|ultimate|game-changing|revolutionary)\b/iu', 'group' => 'A-inflation', 'weight' => 12, 'label' => 'AI stock word: powerful/comprehensive'],
        ['pattern' => '/\b(elevate|empower|unlock|supercharge|turbocharge)\b/iu', 'group' => 'A-inflation', 'weight' => 12, 'label' => 'AI stock word: elevate/empower'],
        ['pattern' => '/\b(in today\'s (fast-paced|digital) world)\b/iu', 'group' => 'A-inflation', 'weight' => 18, 'label' => 'inflated significance opener'],
        // گروه A — not-X-but-Y staging
        ['pattern' => '/\bnot (only|just) \w+[^.]{0,40}\bbut\b/iu', 'group' => 'A-staging', 'weight' => 10, 'label' => 'not-X-but-Y staging'],
        // گروه B — rhythm: triads (سه صفت کاما‌جداشده)
        ['pattern' => '/\b\w+, \w+, and \w+\b/iu', 'group' => 'B-rhythm', 'weight' => 8, 'label' => 'triad (three-item list)'],
        // گروه B — em-dash overuse (≥ ۳ در یک پاراگراف کوتاه)
        ['pattern' => '/—.*—.*—/u', 'group' => 'B-rhythm', 'weight' => 10, 'label' => 'em-dash overuse (>=3)'],
        // گروه C — formatting: decorative bold (** در متن ساده، نه markdown UI)
        ['pattern' => '/\*\*[^*]{1,40}\*\*/u', 'group' => 'C-formatting', 'weight' => 6, 'label' => 'decorative bold (**...)'],
        // گروه C — curly quotes در متن لاتین (اغلب نشانه نسخه‌برداری)
        ['pattern' => '/[\x{201C}\x{201D}\x{2018}\x{2019}]/u', 'group' => 'C-formatting', 'weight' => 5, 'label' => 'curly quotes'],
        // گروه D — chat leftovers / disclaimers
        ['pattern' => '/\b(as an AI|I (cannot|can\'t|am unable to)|please note that|feel free to|let me know if)\b/iu', 'group' => 'D-chat', 'weight' => 25, 'label' => 'chatbot residue/disclaimer'],
        ['pattern' => '/\b(it\'?s important to note|it\'?s worth noting|it should be noted)\b/iu', 'group' => 'D-chat', 'weight' => 18, 'label' => 'meta-writing: it is important to note'],
        // گروه E — wrong-reader re-explaining
        ['pattern' => '/\b(simply|just|easily|quickly) \b/iu', 'group' => 'E-reexplain', 'weight' => 7, 'label' => 're-explaining: simply/just/easily'],
        ['pattern' => '/\b(you can easily|you simply|all you need to do)\b/iu', 'group' => 'E-reexplain', 'weight' => 12, 'label' => 'wrong-reader: you can easily'],
    ];

    /**
     * @return array<int,array{group:string,weight:int,label:string,count:int}>
     */
    public function smells(string $text): array
    {
        $found = [];
        foreach (self::SMELLS as $rule) {
            $n = preg_match_all($rule['pattern'], $text);
            if ($n === false) {
                $n = 0;
            }
            if ($n > 0) {
                $found[] = [
                    'group' => $rule['group'],
                    'weight' => $rule['weight'],
                    'label' => $rule['label'],
                    'count' => $n,
                ];
            }
        }
        return $found;
    }

    /** نمرهٔ ۰-۱۰۰ (وزنی، با کلاه ۱۰۰). */
    public function score(string $text): int
    {
        $total = 0;
        foreach ($this->smells($text) as $s) {
            $total += $s['weight'] * min($s['count'], 3); // هر tell حداکثر ×۳
        }
        return min(100, $total);
    }

    /**
     * قاعدهٔ هم‌وقوعی: تنها tell ضعیف = نادیده؛ اقدام فقط وقتی tells خوشه بزنند.
     */
    public function shouldPolish(string $text): bool
    {
        $found = $this->smells($text);
        if ($found === []) {
            return false;
        }
        $distinctGroups = array_unique(array_column($found, 'group'));
        if (count($found) < self::MIN_TELLS_FOR_ACTION && count($distinctGroups) < 2) {
            return false; // تنها tell ضعیف
        }
        return $this->score($text) >= self::ACTION_THRESHOLD;
    }

    /**
     * claim-preservation check: هر fact باید در متن بازنویسی‌شده زنده بماند.
     * @param array<int,string> $facts
     * @return array{ok:bool,lost:array<int,string>}
     */
    public function claimCheck(string $before, string $after, array $facts): array
    {
        $lost = [];
        foreach ($facts as $fact) {
            if ($fact === '') {
                continue;
            }
            // تطبیق case-insensitive، فقط برای لاتین؛ برای فارسی/عربی حساس به case نیست.
            if (!mb_stripos($after, $fact) && !str_contains($after, $fact)) {
                $lost[] = $fact;
            }
        }
        return ['ok' => $lost === [], 'lost' => $lost];
    }

    /**
     * گزارش قابل‌فهم برای F5 بازبینی.
     * @return array{score:int,should_polish:bool,tells:array<int,array{group:string,weight:int,label:string,count:int}>}
     */
    public function report(string $text): array
    {
        return [
            'score' => $this->score($text),
            'should_polish' => $this->shouldPolish($text),
            'tells' => $this->smells($text),
        ];
    }
}
