<?php

declare(strict_types=1);

/**
 * هندلر Serverless — بدون وابستگی به وب‌سرور، ورودی/خروجی استاندارد
 *
 * محدودیت (SPEC بخش ۱۲): فایل‌سیستم پایدار وجود ندارد؛ وضعیت را در
 * دیتابیس بیرونی نگه دارید، نه در فایل.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level infra
 */
final class ServerlessHandler
{
    /**
     * @param callable(array<string, mixed>):array{status?:int, headers?:array<string,string>, body?:string} $handler
     */
    public static function run(callable $handler): void
    {
        $headers = [];
        if (function_exists('getallheaders')) {
            $raw = getallheaders();
            if (is_array($raw)) {
                foreach ($raw as $k => $v) {
                    if (is_string($k) && is_string($v)) {
                        $headers[strtolower($k)] = $v;
                    }
                }
            }
        }
        $request = [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'GET',
            'uri' => $_SERVER['REQUEST_URI'] ?? '/',
            'query' => $_GET,
            'body' => (string) (file_get_contents('php://input') ?: ''),
            'headers' => $headers,
        ];
        $response = $handler($request);
        http_response_code($response['status'] ?? 200);
        foreach ($response['headers'] ?? [] as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $response['body'] ?? '';
    }
}
