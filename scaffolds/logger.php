<?php

declare(strict_types=1);

/**
 * لاگر فایل‌محور با ۸ سطح PSR-3 و پاک‌سازی داده حساس
 *
 * کلیدهای password/token/secret/key/api_key در context هرگز لاگ نمی‌شوند.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class Logger
{
    private const LEVELS = [
        'debug' => 0, 'info' => 1, 'notice' => 2, 'warning' => 3,
        'error' => 4, 'critical' => 5, 'alert' => 6, 'emergency' => 7,
    ];

    private const REDACT = ['password', 'token', 'secret', 'key', 'api_key'];

    public function __construct(
        private string $file,
        private string $minLevel = 'debug',
    ) {
    }

    public function log(string $level, string $message, array $context = []): void
    {
        $priority = self::LEVELS[$level] ?? null;
        if ($priority === null) {
            throw new InvalidArgumentException("Unknown log level: {$level}");
        }
        if ($priority < (self::LEVELS[$this->minLevel] ?? 0)) {
            return;
        }
        $line = sprintf(
            "[%s] %s: %s %s\n",
            date('c'),
            strtoupper($level),
            $this->interpolate($message, $context),
            $this->encode($this->redact($context))
        );
        file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('info', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->log('warning', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('error', $message, $context);
    }

    private function interpolate(string $message, array $context): string
    {
        $replacements = [];
        foreach ($context as $k => $v) {
            if (is_scalar($v) || $v === null) {
                $replacements['{' . $k . '}'] = (string) $v;
            }
        }

        return strtr($message, $replacements);
    }

    /** @param array<string, mixed> $context */
    private function redact(array $context): array
    {
        foreach ($context as $k => $v) {
            if (in_array(strtolower((string) $k), self::REDACT, true)) {
                $context[$k] = '[REDACTED]';
            }
        }

        return $context;
    }

    private function encode(array $context): string
    {
        if ($context === []) {
            return '';
        }
        $json = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);

        return $json === false ? '{}' : $json;
    }
}
