<?php

declare(strict_types=1);

/**
 * کش فایل‌محور با TTL و قفل Stampede
 *
 * اگر کش منقضی باشد، فقط یک پردازش تابع resolver را اجرا می‌کند؛
 * بقیه با قفل مشترک منتظر نتیجه می‌مانند.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level performance
 */
final class FileCache
{
    public function __construct(private string $dir)
    {
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0775, true);
        }
    }

    /**
     * @template T
     * @param callable():T $resolver محاسبه گران در صورت نبود کش
     * @return T
     */
    public function get(string $key, callable $resolver, int $ttl = 300): mixed
    {
        $file = $this->path($key);
        $payload = $this->read($file);
        if ($payload !== null) {
            return $payload['value'];
        }

        $lock = fopen($file . '.lock', 'c');
        if ($lock === false) {
            return $resolver(); // بدون قفل هم کار درست است؛ فقط پرهزینه‌تر
        }
        if (!flock($lock, LOCK_EX | LOCK_NB)) {
            // پردازش دیگری در حال محاسبه است: صبر و خواندن نتیجه
            flock($lock, LOCK_SH);
            $payload = $this->read($file);
            flock($lock, LOCK_UN);
            fclose($lock);

            return $payload !== null ? $payload['value'] : null;
        }
        try {
            $value = $resolver();
            file_put_contents(
                $file,
                serialize(['expires' => $ttl > 0 ? time() + $ttl : 0, 'value' => $value]),
                LOCK_EX
            );

            return $value;
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    public function invalidate(string $key): void
    {
        $file = $this->path($key);
        if (is_file($file)) {
            unlink($file);
        }
    }

    /** @return array{expires:int, value:mixed}|null */
    private function read(string $file): ?array
    {
        if (!is_file($file)) {
            return null;
        }
        $payload = unserialize(
            (string) file_get_contents($file),
            ['allowed_classes' => false]
        );
        if (!is_array($payload) || !isset($payload['expires'], $payload['value'])) {
            return null;
        }
        if ($payload['expires'] !== 0 && $payload['expires'] <= time()) {
            return null; // منقضی
        }

        return $payload;
    }

    private function path(string $key): string
    {
        return $this->dir . '/' . hash('sha256', $key) . '.cache';
    }
}
