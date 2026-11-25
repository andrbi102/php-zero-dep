<?php

declare(strict_types=1);

/**
 * مدیریت خطای سراسری — بدون افشای جزئیات در production
 *
 * خطاها به ErrorException تبدیل و لاگ می‌شوند؛ پیام خام استثنا فقط
 * در حالت debug نمایش داده می‌شود (و escape می‌شود).
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class ErrorHandler
{
    public function __construct(
        private Logger $logger,
        private bool $debug = false,
    ) {
    }

    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $number, string $message, string $file, int $line): bool
    {
        if ((error_reporting() & $number) === 0) {
            return false; // خطای suppress شده — به PHP واگذار می‌شود
        }
        throw new ErrorException($message, 0, $number, $file, $line);
    }

    public function handleException(Throwable $e): void
    {
        $this->logger->log('error', $e->getMessage(), [
            'type' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
        http_response_code(500);
        if ($this->debug) {
            echo self::escape($e->getMessage()), "\n", self::escape($e->getTraceAsString());
        } else {
            echo 'خطای داخلی سرور — جزئیات در لاگ ثبت شد';
        }
        exit(1);
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array(
            $error['type'],
            [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR],
            true
        )) {
            $this->logger->log('critical', 'Fatal: ' . $error['message'], [
                'file' => $error['file'],
                'line' => $error['line'],
            ]);
        }
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
