<?php

declare(strict_types=1);

/**
 * اتولودر دست‌نویس — بدون Composer، بدون vendor/
 *
 * نگاشت پیشوند فضای‌نام به پوشه پایه (سبک ساده‌شده PSR-4).
 * «بازآفرینی آگاهانه چرخ»: هر پروژه این ۳۰ خط را می‌نویسد، نه نصب می‌کند.
 *
 * استفاده:
 *   $loader = new Autoloader();
 *   $loader->addNamespace('App\\', __DIR__ . '/src');
 *   $loader->register();
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class Autoloader
{
    /** @var array<string, string> پیشوند فضای‌نام => پوشه پایه */
    private array $prefixes = [];

    /**
     * @param string $prefix مثال: 'App\\'
     * @param string $baseDir مثال: __DIR__ . '/src'
     */
    public function addNamespace(string $prefix, string $baseDir): void
    {
        $this->prefixes[rtrim($prefix, '\\') . '\\'] = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR;
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    private function load(string $class): void
    {
        foreach ($this->prefixes as $prefix => $baseDir) {
            if (str_starts_with($class, $prefix)) {
                $relative = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, strlen($prefix)));
                $file = $baseDir . $relative . '.php';
                if (is_file($file)) {
                    require $file;
                    return;
                }
            }
        }
        // کلاس خارج از نگاشت‌ها: عمداً بی‌صدا رد می‌شود تا spl_autoload
        // به لودرهای بعدی (مثل لودر تست) فرصت دهد.
    }
}
