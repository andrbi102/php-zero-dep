<?php

declare(strict_types=1);

/**
 * مثال ۱۰ — واکشی وب سبک + HTML→markdown (الگوی firecrawl در PHP خالص)
 * اجرا: php examples/10-web-fetch-demo.php
 * توجه: این مثال به شبکه نیاز دارد؛ در sandbox بدون شبکه، به‌جای fetch
 *        واقعی، تابع cleanHtml/toMarkdown روی HTML نمونه نمایش داده می‌شود.
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/web-fetch.php';

$wf = new WebFetch(timeoutSeconds: 8);

// — نمایش قابلیت‌ها روی HTML نمونه (بدون نیاز به شبکه) —
$sampleHtml = <<<'HTML'
<!DOCTYPE html>
<html><head><title>Demo</title></head>
<body>
  <header><nav>Home About</nav></header>
  <main>
    <h1>عنوان مقاله</h1>
    <p>پاراگراف <strong>مهم</strong> با <a href="/x">لینک داخلی</a>.</p>
    <ul><li>یک</li><li>دو</li></ul>
    <pre>code block</pre>
  </main>
  <footer class="footer copyright">© 2026</footer>
  <div class="ad">تبلیغ</div>
</body></html>
HTML;

echo "=== cleanHtml (nav/footer/ad حذف) ===\n";
echo $wf->cleanHtml($sampleHtml) . "\n\n";

echo "=== toMarkdown ===\n";
echo $wf->toMarkdown($sampleHtml) . "\n\n";

echo "=== filterLinks ===\n";
$links = [
    'https://example.com/page', 'https://example.com/page#section',
    'https://facebook.com/x', 'https://example.com/file.pdf',
    'mailto:a@b.com', '/relative',
];
foreach ($wf->filterLinks($links, 'example.com', maxDepth: 5) as $r) {
    echo "  {$r['url']}  ->  " . ($r['denial'] ?? 'KEEP') . "\n";
}
echo "\n";

echo "=== robotsAllows (only checks if reachable; offline = true) ===\n";
echo "example.com/ allowed (no robots rule seen): " . ($wf->robotsAllows('https://example.com/') ? 'yes' : 'no') . "\n";
