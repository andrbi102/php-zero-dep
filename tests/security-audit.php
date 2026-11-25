<?php

declare(strict_types=1);

/**
 * ممیزی امنیتی ایستا — اسکن خودکار خروجی مهارت
 * اجرا: php tests/security-audit.php   (خروجی 0 یعنی PASS)
 *
 * @package PhpZeroDep\Tests
 * @version 0.0.2
 */
$root = dirname(__DIR__);
$failed = 0;

function finding(string $file, int $line, string $issue): void
{
    global $failed;
    echo "  FAIL  {$file}:{$line} — {$issue}\n";
    $failed++;
}

// توابع ممنوع SPEC (بخش ۱۵) — مگر با مستندسازی صریح و sandbox
$forbidden = [
    'eval(', 'exec(', 'shell_exec(', 'system(', 'passthru(', 'proc_open(', 'popen(',
];

$targets = array_merge(
    glob($root . '/scaffolds/*.php') ?: [],
    glob($root . '/scaffolds/tools/*.php') ?: [],
    glob($root . '/examples/*.php') ?: []
);

foreach ($targets as $file) {
    $lines = file($file) ?: [];
    $rel = str_replace($root . '/', '', $file);
    foreach ($lines as $n => $line) {
        $trimmed = trim($line);
        if (str_starts_with($trimmed, '*') || str_starts_with($trimmed, '//') || str_starts_with($trimmed, '|')) {
            continue; // کامنت‌ها و جداول مستندات
        }
        foreach ($forbidden as $fn) {
            // مرز واژه‌ای: curl_exec و ->exec (اجرای SQL در PDO) مجازند؛ فقط فراخوانی خام تابع اجرای برنامه ممنوع است
            if (preg_match('/(?<![\\w>])' . preg_quote($fn, '/') . '/', $line)) {
                finding($rel, $n + 1, "forbidden function {$fn}");
            }
        }
        if (str_contains($line, 'composer')) {
            finding($rel, $n + 1, 'composer reference (Zero-Composer violated)');
        }
        if (preg_match('/SELECT \*/i', $line) && !str_contains($line, '//')) {
            finding($rel, $n + 1, 'SELECT * (forbidden in production code)');
        }
    }
    // strict_types باید در سه خط اول بیاید
    $head = implode('', array_slice($lines, 0, 4));
    if (!str_contains($head, 'declare(strict_types=1);')) {
        finding($rel, 1, 'missing declare(strict_types=1) in file header');
    }
}

echo $failed === 0
    ? "\nSECURITY AUDIT: PASS (" . count($targets) . " files scanned)\n"
    : "\nSECURITY AUDIT: {$failed} FINDING(S)\n";
exit($failed === 0 ? 0 : 1);
