<?php

declare(strict_types=1);

/**
 * کلاینت HTTP با ext-curl — timeout و retry با backoff ثابت
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level infra
 */
final class HttpClient
{
    public function __construct(
        private int $timeout = 10,
        private int $retries = 2,
        private array $defaultHeaders = [],
    ) {
    }

    /**
     * @param array<string, string|int> $query
     * @return array{status:int, body:string, json:mixed}
     */
    public function get(string $url, array $query = []): array
    {
        return $this->request('GET', $url, $query, null);
    }

    /**
     * @param array<string, mixed> $data
     * @return array{status:int, body:string, json:mixed}
     */
    public function postJson(string $url, array $data): array
    {
        $json = (string) json_encode($data, JSON_UNESCAPED_UNICODE);

        return $this->request('POST', $url, [], $json);
    }

    /**
     * @param array<string, string|int> $query
     * @return array{status:int, body:string, json:mixed}
     */
    private function request(string $method, string $url, array $query, ?string $jsonBody): array
    {
        if ($query !== []) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }
        $attempt = 0;
        while (true) {
            $ch = curl_init($url);
            if ($ch === false) {
                throw new RuntimeException('curl_init failed');
            }
            $headers = $this->defaultHeaders;
            if ($jsonBody !== null) {
                $headers[] = 'Content-Type: application/json';
            }
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $jsonBody,
            ]);
            $body = curl_exec($ch);
            if ($body === false) {
                $error = curl_error($ch);
                $errno = curl_errno($ch);
                curl_close($ch);
                $retryable = $errno === CURLE_OPERATION_TIMEDOUT
                    || $errno === CURLE_COULDNT_CONNECT;
                if ($attempt++ < $this->retries && $retryable) {
                    usleep(500_000); // backoff ثابت ۰٫۵ ثانیه
                    continue;
                }
                throw new RuntimeException("HTTP {$method} failed: {$error}", $errno);
            }
            $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            curl_close($ch);

            return [
                'status' => $status,
                'body' => (string) $body,
                'json' => json_decode((string) $body, true),
            ];
        }
    }
}
