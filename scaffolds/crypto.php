<?php

declare(strict_types=1);

/**
 * رمزنگاری با ext-sodium — اولویت ۱ رویکرد Zero-Dependency
 *
 * اگر ext-sodium نبود: sodium_compat (ISC) را vendored کنید در
 * scaffolds/crypto/ — هرگز composer require در runtime (SPEC بخش ۶).
 * پیاده‌سازی منحنی بیضوی از صفر در production ممنوع.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class Crypto
{
    public static function sodiumAvailable(): bool
    {
        return extension_loaded('sodium');
    }

    /** کلید تصادفی برای secretbox (۳۲ بایت). */
    public static function generateKey(): string
    {
        self::guard();

        return random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
    }

    public static function encrypt(string $plaintext, string $key): string
    {
        self::guard();
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($plaintext, $nonce, self::key($key));

        return base64_encode($nonce . $ciphertext);
    }

    public static function decrypt(string $sealed, string $key): string
    {
        self::guard();
        $raw = base64_decode($sealed, true);
        if ($raw === false) {
            throw new InvalidArgumentException('invalid ciphertext (not base64)');
        }
        $nonceLen = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
        if (strlen($raw) < $nonceLen + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            throw new InvalidArgumentException('invalid ciphertext (too short)');
        }
        $plaintext = sodium_crypto_secretbox_open(
            substr($raw, $nonceLen),
            substr($raw, 0, $nonceLen),
            self::key($key)
        );
        if ($plaintext === false) {
            throw new RuntimeException('decryption failed (wrong key or tampered data)');
        }

        return $plaintext;
    }

    /**
     * امضای Ed25519 (RFC 8032) — detached.
     */
    public static function sign(string $message, string $secretKey): string
    {
        self::guard();

        return base64_encode(sodium_crypto_sign_detached($message, $secretKey));
    }

    public static function verify(string $message, string $signature, string $publicKey): bool
    {
        self::guard();
        $raw = base64_decode($signature, true);

        return $raw !== false
            && sodium_crypto_sign_verify_detached($raw, $message, $publicKey);
    }

    /** هش رمز عبور — همیشه Argon2id. */
    public static function passwordHash(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    public static function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    private static function guard(): void
    {
        if (!extension_loaded('sodium')) {
            throw new RuntimeException(
                'ext-sodium not available; vendor sodium_compat (ISC) into scaffolds/crypto/ instead'
            );
        }
    }

    private static function key(string $key): string
    {
        if (strlen($key) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new InvalidArgumentException(
                'key must be exactly ' . SODIUM_CRYPTO_SECRETBOX_KEYBYTES . ' bytes'
            );
        }

        return $key;
    }
}
