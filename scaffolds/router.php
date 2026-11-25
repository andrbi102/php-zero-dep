<?php

declare(strict_types=1);

/**
 * روتر امن بدون regex خطرنه — تطبیق سگمنت‌به‌سگمنت
 *
 * پارامترها با {name} تعریف می‌شوند؛ مقدار خام به handler می‌رسد و
 * مصرف‌کننده موظف است با Validator اعتبارسنجی کند (جداسازی مسیر از اعتبارسنجی).
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class Router
{
    /** @var array<string, array<string, callable>> METHOD => pattern => handler */
    private array $routes = [];

    /** @var array<int, callable> */
    private array $middleware = [];

    public function add(string $method, string $pattern, callable $handler): void
    {
        $this->routes[strtoupper($method)][$pattern] = $handler;
    }

    public function get(string $pattern, callable $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    /** افزودن middleware به زنجیره (onion ساده) */
    public function use(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @param string $method مثال: 'GET'
     * @param string $path   مثال: '/user/42'
     */
    public function dispatch(string $method, string $path): void
    {
        $router = $this;
        $core = static function (array $ctx) use ($router, $method, $path): void {
            $router->match($method, $path, $ctx);
        };
        $next = $core;
        foreach (array_reverse($this->middleware) as $mw) {
            $next = static function (array $ctx) use ($mw, $next): void {
                $mw($ctx, $next);
            };
        }
        $next(['path' => $path, 'method' => $method]);
    }

    private function match(string $method, string $path, array $ctx): void
    {
        $segments = self::split($path);
        $allowed = [];
        foreach ($this->routes as $routeMethod => $patterns) {
            foreach ($patterns as $pattern => $handler) {
                $params = self::matchPattern(self::split($pattern), $segments);
                if ($params === null) {
                    continue;
                }
                if ($routeMethod !== $method) {
                    $allowed[] = $routeMethod;
                    continue;
                }
                $handler(array_merge($ctx, ['params' => $params]));
                return;
            }
        }
        if ($allowed !== []) {
            header('Allow: ' . implode(', ', array_unique($allowed)));
            http_response_code(405);
            exit('405 Method Not Allowed');
        }
        http_response_code(404);
        exit('404 Not Found');
    }

    /**
     * @param array<int, string> $patternSegs
     * @param array<int, string> $pathSegs
     * @return array<string, string>|null null یعنی عدم تطبیق
     */
    private static function matchPattern(array $patternSegs, array $pathSegs): ?array
    {
        $n = count($patternSegs);
        if ($n !== count($pathSegs)) {
            return null;
        }
        $params = [];
        for ($i = 0; $i < $n; $i++) {
            $seg = $patternSegs[$i];
            if ($seg !== '' && $seg[0] === '{' && str_ends_with($seg, '}')) {
                $params[substr($seg, 1, -1)] = $pathSegs[$i];
            } elseif ($seg !== $pathSegs[$i]) {
                return null;
            }
        }
        return $params;
    }

    /** @return array<int, string> */
    private static function split(string $path): array
    {
        return array_values(array_filter(
            explode('/', trim($path, '/')),
            static fn (string $s): bool => $s !== ''
        ));
    }
}
