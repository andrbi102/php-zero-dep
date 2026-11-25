<?php

declare(strict_types=1);

/**
 * لایه View با escape خودکار در helper
 *
 * قاعده: هر خروجی متغیر از View با e() رندر می‌شود —
 * htmlspecialchars(..., ENT_QUOTES, 'UTF-8').
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class View
{
    public function __construct(private string $dir)
    {
    }

    /**
     * @param array<string, mixed> $data متغیرهایی که در قالب در دسترس‌اند
     */
    public function render(string $template, array $data = []): string
    {
        // مسیر قالب پاک‌سازی می‌شود تا path traversal ممکن نباشد
        $safe = str_replace(['..', '\\'], '', $template);
        $file = $this->dir . '/' . $safe . '.php';
        if (!is_file($file)) {
            throw new RuntimeException("Template not found: {$template}");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;

        return (string) ob_get_clean();
    }

    /**
     * escape — تنها راه مجاز چاپ داده متغیر در قالب.
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
