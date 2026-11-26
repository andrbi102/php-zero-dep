<?php

declare(strict_types=1);

/**
 * واکشی وب سبک + HTML→text/markdown — الگوبرداری‌شده از firecrawl (AGPL-3.0/MIT)
 *
 * فقط الگوی معماری الگوبرداری شد (واترفال موتور، حذف عناصر ناخواسته،
 * robots.txt، فیلتر لینک، charset)؛ **هیچ کد از firecrawl کپی نشده** (AGPL
 * انتقالی است) — همه در PHP خالص با ext-curl + ext-dom + ext-mbstring بازنویسی شد.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 * @pattern-source firecrawl (AGPL-3.0 server / MIT SDKs) — apps/api/src/scraper/scrapeURL/*
 */
final class WebFetch
{
    /** فهرست ثابت عناصر ناخواسته (firecrawl removeUnwantedElements.ts). */
    private const UNWANTED_TAGS = ['head', 'title', 'meta', 'link', 'header', 'footer', 'nav', 'aside', 'script', 'style', 'noscript', 'iframe', 'form'];
    private const UNWANTED_CLASS_SUBSTR = ['sidebar', 'ad', 'cookie', 'social', 'breadcrumb', 'comment', 'related', 'promo'];
    private const UNWANTED_ID_SUBSTR = ['sidebar', 'cookie', 'comment', 'footer', 'header'];

    /** دلایل رد لینک (firecrawl crawler.ts DenialReason). */
    public const DENIAL_NON_HTTP = 'DENIAL_NON_HTTP';
    public const DENIAL_CROSS_DOMAIN = 'DENIAL_CROSS_DOMAIN';
    public const DENIAL_SOCIAL = 'DENIAL_SOCIAL';
    public const DENIAL_FILE_EXT = 'DENIAL_FILE_EXT';
    public const DENIAL_ANCHOR = 'DENIAL_ANCHOR';
    public const DENIAL_MAILTO = 'DENIAL_MAILTO';
    public const DENIAL_DEPTH = 'DENIAL_DEPTH';
    public const DENIAL_EXCLUDED = 'DENIAL_EXCLUDED';

    private const SOCIAL_HOSTS = ['facebook.com', 'twitter.com', 'x.com', 'instagram.com', 'linkedin.com', 'youtube.com', 'tiktok.com'];
    private const SKIP_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'pdf', 'zip', 'rar', 'mp3', 'mp4', 'webm', 'doc', 'docx', 'xls', 'xlsx', 'css', 'js'];

    /** @var array<int,string> robots cache per host */
    private array $robotsCache = [];

    public function __construct(
        private readonly int $timeoutSeconds = 15,
        private readonly string $userAgent = 'php-zero-dep-webfetch/0.0.2 (+https://github.com/andrbi102/php-zero-dep)',
        private readonly float $perHostDelaySeconds = 1.0,
    ) {
    }

    /**
     * واترفال موتور: fetch ساده با cURL؛ اگر پاسخ غیر文本/JS-gated به‌نظر آمد، هشدار.
     * @return array{ok:bool,url:string,final_url:string,status:int,charset:string,warnings:array<int,string>}
     */
    public function fetch(string $url): array
    {
        if (!$this->robotsAllows($url)) {
            return ['ok' => false, 'url' => $url, 'final_url' => $url, 'status' => 0, 'charset' => '', 'warnings' => ['blocked by robots.txt']];
        }
        $this->throttle($url);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 5,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSeconds,
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_HTTPHEADER => ['Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '';
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($errno !== 0 || $body === false) {
            return ['ok' => false, 'url' => $url, 'final_url' => $finalUrl, 'status' => $status, 'charset' => '', 'warnings' => ['curl error: ' . $errno]];
        }
        $warnings = [];
        if (!str_contains($contentType, 'text/') && !str_contains($contentType, 'xml') && !str_contains($contentType, 'json')) {
            $warnings[] = 'non-text content-type: ' . $contentType . ' (JS-gated or binary?)';
        }
        $charset = $this->sniffCharset($contentType, $body);
        $body = $this->toUtf8($body, $charset);

        return ['ok' => $status >= 200 && $status < 400, 'url' => $url, 'final_url' => $finalUrl, 'status' => $status, 'charset' => $charset, 'warnings' => $warnings, 'body' => $body];
    }

    /** حذف عناصر ناخواسته و استخراج متن تمیز (firecrawl removeUnwantedElements). */
    public function cleanHtml(string $html): string
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        $xpath = new DOMXPath($doc);

        // حذف بر اساس tag
        foreach (self::UNWANTED_TAGS as $tag) {
            foreach (iterator_to_array($xpath->query('//' . $tag)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }
        // حذف بر اساس class/id (substring)
        foreach ($xpath->query('//*[@class] | //*[@id]') as $node) {
            $attrs = ($node->getAttribute('class') . ' ' . $node->getAttribute('id'));
            foreach (self::UNWANTED_CLASS_SUBSTR as $sub) {
                if (stripos($attrs, $sub) !== false) {
                    $node->parentNode?->removeChild($node);
                    break;
                }
            }
            foreach (self::UNWANTED_ID_SUBSTR as $sub) {
                if (stripos($node->getAttribute('id'), $sub) !== false) {
                    $node->parentNode?->removeChild($node);
                    break;
                }
            }
        }
        // متن با فاصله‌گذاری
        return trim(preg_replace('/\s+/', ' ', $doc->textContent ?? '') ?? '');
    }

    /** HTML→markdown زیرمجموعه (firecrawl html-to-markdown.ts، ~۱۵۰ خط PHP). */
    public function toMarkdown(string $html): string
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        // ابتدا عناصر ناخواسته را حذف کن (firecrawl pipeline: removeUnwantedElements → toMarkdown)
        $this->removeUnwanted($doc);
        return trim($this->nodeToMarkdown($doc));
    }

    /** حذف عناصر ناخواسته روی یک DOMDocument (مشترک بین cleanHtml و toMarkdown). */
    private function removeUnwanted(DOMDocument $doc): void
    {
        $xpath = new DOMXPath($doc);
        foreach (self::UNWANTED_TAGS as $tag) {
            foreach (iterator_to_array($xpath->query('//' . $tag)) as $node) {
                $node->parentNode?->removeChild($node);
            }
        }
        foreach ($xpath->query('//*[@class] | //*[@id]') as $node) {
            $removed = false;
            $classAttr = $node->getAttribute('class');
            $idAttr = $node->getAttribute('id');
            $attrs = $classAttr . ' ' . $idAttr;
            foreach (self::UNWANTED_CLASS_SUBSTR as $sub) {
                if (stripos($attrs, $sub) !== false) {
                    $node->parentNode?->removeChild($node);
                    $removed = true;
                    break;
                }
            }
            if ($removed) {
                continue;
            }
            foreach (self::UNWANTED_ID_SUBSTR as $sub) {
                if (stripos($idAttr, $sub) !== false) {
                    $node->parentNode?->removeChild($node);
                    break;
                }
            }
        }
    }

    private function nodeToMarkdown(DOMNode $node, int $depth = 0): string
    {
        $out = '';
        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMText) {
                $out .= $child->textContent;
                continue;
            }
            $tag = strtolower($child->nodeName ?? '');
            $inner = $this->nodeToMarkdown($child, $depth);
            $inner = trim($inner);
            switch ($tag) {
                case 'h1': $out .= "\n\n# " . $inner . "\n\n"; break;
                case 'h2': $out .= "\n\n## " . $inner . "\n\n"; break;
                case 'h3': $out .= "\n\n### " . $inner . "\n\n"; break;
                case 'h4': case 'h5': case 'h6': $out .= "\n\n#### " . $inner . "\n\n"; break;
                case 'p': $out .= "\n\n" . $inner . "\n\n"; break;
                case 'br': $out .= "  \n"; break;
                case 'hr': $out .= "\n\n---\n\n"; break;
                case 'strong': case 'b': $out .= '**' . $inner . '**'; break;
                case 'em': case 'i': $out .= '*' . $inner . '*'; break;
                case 'code': $out .= '`' . $inner . '`'; break;
                case 'pre': $out .= "\n\n```\n" . $inner . "\n```\n\n"; break;
                case 'blockquote': $out .= "\n" . preg_replace('/^/m', '> ', $inner) . "\n\n"; break;
                case 'a':
                    $href = $child instanceof DOMElement ? $child->getAttribute('href') : '';
                    $out .= $href !== '' ? '[' . $inner . '](' . $href . ')' : $inner;
                    break;
                case 'li': $out .= "\n- " . $inner; break;
                case 'ul': case 'ol': $out .= "\n" . $inner . "\n"; break;
                default: $out .= $inner;
            }
        }
        return $out;
    }

    /** robots.txt: آیا این URL مجاز است؟ (firecrawl robots-txt.ts). */
    public function robotsAllows(string $url): bool
    {
        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            return true; // فقط http(s) بررسی می‌شود
        }
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'];
        if (!isset($this->robotsCache[$host])) {
            $this->robotsCache[$host] = $this->fetchRobots($scheme . '://' . $host . '/robots.txt');
        }
        $rules = $this->robotsCache[$host];
        $path = $parts['path'] ?? '/';
        foreach ($rules['disallow'] as $pattern) {
            if ($this->robotsMatch($pattern, $path)) {
                // آیا Allow خاص‌تری هست؟
                foreach ($rules['allow'] as $allowPattern) {
                    if ($this->robotsMatch($allowPattern, $path) && strlen($allowPattern) >= strlen($pattern)) {
                        return true; // Allow خاص بر Disallow عام غلبه می‌کند
                    }
                }
                return false;
            }
        }
        return true;
    }

    /** @return array{array<int,string>,array<int,string>} */
    private function fetchRobots(string $robotsUrl): array
    {
        $ch = curl_init($robotsUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        $disallow = [];
        $allow = [];
        $applies = true;
        if (is_string($body)) {
            $lines = preg_split('/\r\n|\r|\n/', $body) ?: [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) {
                    continue;
                }
                if (preg_match('/^User-agent:\s*(.+)$/i', $line, $m)) {
                    $applies = ($m[1] === '*' || stripos($m[1], 'php-zero-dep') !== false);
                    continue;
                }
                if (!$applies) {
                    continue;
                }
                if (preg_match('/^Disallow:\s*(.*)$/i', $line, $m) && $m[1] !== '') {
                    $disallow[] = $m[1];
                } elseif (preg_match('/^Allow:\s*(.*)$/i', $line, $m) && $m[1] !== '') {
                    $allow[] = $m[1];
                }
            }
        }
        return ['disallow' => $disallow, 'allow' => $allow];
    }

    /** glob-match مسیر robots (مبهم * و $). */
    private function robotsMatch(string $pattern, string $path): bool
    {
        $regex = '/^' . str_replace(['\*', '\$'], ['.*', '$'], preg_quote($pattern, '/')) . '/i';
        return (bool) preg_match($regex, $path);
    }

    /**
     * فیلتر لینک با دلایل رد (firecrawl crawler.ts filterLinks).
     * @return array<int,array{url:string,denial:?string}>
     */
    public function filterLinks(array $hrefs, string $baseHost, int $maxDepth = 3, array $excludePatterns = []): array
    {
        $result = [];
        foreach ($hrefs as $href) {
            $parts = parse_url($href);
            if ($parts === false) {
                $result[] = ['url' => $href, 'denial' => self::DENIAL_NON_HTTP];
                continue;
            }
            $scheme = $parts['scheme'] ?? '';
            if (!in_array($scheme, ['http', 'https'], true)) {
                if (($parts['scheme'] ?? '') === 'mailto') {
                    $result[] = ['url' => $href, 'denial' => self::DENIAL_MAILTO];
                } elseif (str_starts_with($href, '#')) {
                    $result[] = ['url' => $href, 'denial' => self::DENIAL_ANCHOR];
                } else {
                    $result[] = ['url' => $href, 'denial' => self::DENIAL_NON_HTTP];
                }
                continue;
            }
            $host = $parts['host'] ?? '';
            if ($host !== $baseHost) {
                $result[] = ['url' => $href, 'denial' => self::DENIAL_CROSS_DOMAIN];
                continue;
            }
            // social host
            foreach (self::SOCIAL_HOSTS as $sh) {
                if (str_contains($host, $sh)) {
                    $result[] = ['url' => $href, 'denial' => self::DENIAL_SOCIAL];
                    continue 2;
                }
            }
            // file extension
            $path = $parts['path'] ?? '';
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, self::SKIP_EXTENSIONS, true)) {
                $result[] = ['url' => $href, 'denial' => self::DENIAL_FILE_EXT];
                continue;
            }
            // depth by path-segment count
            $segments = count(array_filter(explode('/', $path)));
            if ($segments > $maxDepth) {
                $result[] = ['url' => $href, 'denial' => self::DENIAL_DEPTH];
                continue;
            }
            // exclude regex
            foreach ($excludePatterns as $pat) {
                if (preg_match($pat, $href)) {
                    $result[] = ['url' => $href, 'denial' => self::DENIAL_EXCLUDED];
                    continue 2;
                }
            }
            $result[] = ['url' => $href, 'denial' => null];
        }
        return $result;
    }

    /** sniff charset از Content-Type و <meta charset>. */
    private function sniffCharset(string $contentType, string $body): string
    {
        if (preg_match('/charset=["\']?([\w-]+)/i', $contentType, $m)) {
            return $m[1];
        }
        if (preg_match('/<meta[^>]+charset=["\']?([\w-]+)/i', $body, $m)) {
            return $m[1];
        }
        return 'UTF-8';
    }

    private function toUtf8(string $body, string $charset): string
    {
        $charset = strtoupper($charset);
        if ($charset === 'UTF-8' || $charset === '') {
            return $body;
        }
        $conv = @mb_convert_encoding($body, 'UTF-8', $charset);
        return $conv === false ? $body : $conv;
    }

    /** per-host throttle ساده (token-bucket با usleep — نه Redis). */
    private function throttle(string $url): void
    {
        $parts = parse_url($url);
        $host = $parts['host'] ?? '';
        if ($host === '') {
            return;
        }
        $now = microtime(true);
        $key = 'throttle:' . $host;
        if (isset($this->robotsCache[$key])) {
            $elapsed = $now - $this->robotsCache[$key];
            if ($elapsed < $this->perHostDelaySeconds) {
                usleep((int) (($this->perHostDelaySeconds - $elapsed) * 1_000_000));
            }
        }
        $this->robotsCache[$key] = microtime(true);
    }
}
