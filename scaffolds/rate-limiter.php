<?php

declare(strict_types=1);

/**
 * محدودکننده نرخ — پنجره ثابت، فایل‌محور، بدون Redis
 *
 * کلید = ترکیب هویت + عملیات (مثال: 'login:ip:1.2.3.4').
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class RateLimiter
{
    public function __construct(
        private string $dir,
        private int $limit = 60,
        private int $windowSeconds = 60,
    ) {
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0775, true);
        }
    }

    public function allow(string $key): bool
    {
        $file = $this->dir . '/' . hash('sha256', $key) . '.count';
        $now = time();
        $count = 0;
        $start = $now;
        if (is_file($file)) {
            [$start, $count] = array_map(
                'intval',
                explode(':', (string) file_get_contents($file))
            );
        }
        if ($now - $start >= $this->windowSeconds) {
            $start = $now;
            $count = 0;
        }
        $count++;
        file_put_contents($file, $start . ':' . $count, LOCK_EX);

        return $count <= $this->limit;
    }

    public function remaining(string $key): int
    {
        $file = $this->dir . '/' . hash('sha256', $key) . '.count';
        if (!is_file($file)) {
            return $this->limit;
        }
        [$start, $count] = array_map('intval', explode(':', (string) file_get_contents($file)));
        if (time() - $start >= $this->windowSeconds) {
            return $this->limit;
        }

        return max(0, $this->limit - $count);
    }
}
