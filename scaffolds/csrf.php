<?php

declare(strict_types=1);

/**
 * توکن CSRF امضاشده با HMAC — بدون ذخیره‌سازی سمت سرور
 *
 * token = HMAC-SHA256(session_id, APP_KEY)
 * مقایسه زمان-ثابت با hash_equals.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class Csrf
{
    private static function key(): string
    {
        // کلید باید از محیط تامین شود؛ هرگز هاردکد نشود.
        $key = getenv('APP_KEY');
        if ($key === false || $key === '') {
            throw new RuntimeException('APP_KEY is not set; cannot sign CSRF tokens');
        }

        return $key;
    }

    public static function token(): string
    {
        SecureSession::start();

        return hash_hmac('sha256', session_id(), self::key());
    }

    /**
     * فیلد hidden آماده برای درج در فرم (خودش escape می‌شود).
     */
    public static function field(): string
    {
        $token = self::token();

        return '<input type="hidden" name="_csrf" value="'
            . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $submitted): bool
    {
        if ($submitted === null || $submitted === '') {
            return false;
        }

        return hash_equals(self::token(), $submitted);
    }
}
