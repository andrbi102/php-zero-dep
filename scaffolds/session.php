<?php

declare(strict_types=1);

/**
 * نشست امن — کوکی با HttpOnly + Secure + SameSite=Strict
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class SecureSession
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => true,    // فقط HTTPS
            'httponly' => true,  // دور از جاوااسکریپت
            'samesite' => 'Strict',
        ]);
        session_start();
    }

    /**
     * پس از login اجباری — جلوگیری از Session Fixation.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * پیام یک‌بارمصرف بین دو request.
     */
    public static function flash(string $key, ?string $message = null): ?string
    {
        self::start();
        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;

            return null;
        }
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return $value;
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        session_destroy();
    }
}
