<?php

declare(strict_types=1);

/**
 * Manual Tracing سازگار با W3C Trace Context — بدون SDK اجباری
 *
 * هدر traceparent با قالب: 00-{trace-id}-{span-id}-{flags}
 * برای ارسال با HttpClient، هدر را به defaultHeaders اضافه کنید.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level infra
 */
final class Tracing
{
    /** trace-id: ۱۶ بایت هگز (W3C) */
    public static function newTraceId(): string
    {
        return bin2hex(random_bytes(16));
    }

    /** span-id: ۸ بایت هگز (W3C) */
    public static function newSpanId(): string
    {
        return bin2hex(random_bytes(8));
    }

    public static function traceparent(string $traceId, string $spanId, bool $sampled = true): string
    {
        if (strlen($traceId) !== 32 || strlen($spanId) !== 16) {
            throw new InvalidArgumentException('invalid W3C id length');
        }

        return sprintf('00-%s-%s-%s', $traceId, $spanId, $sampled ? '01' : '00');
    }
}
