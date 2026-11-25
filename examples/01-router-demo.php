<?php

declare(strict_types=1);

/**
 * مثال ۱ — روتر با پارامتر مسیر
 * اجرا: php examples/01-router-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.1
 */
require __DIR__ . '/../scaffolds/router.php';

$router = new Router();
$router->get('/hello/{name}', static function (array $ctx): void {
    // مقدار خام مسیر است؛ در پروژه واقعی ابتدا Validator سپس خروجی escape:
    echo 'Hello, ', htmlspecialchars((string) $ctx['params']['name'], ENT_QUOTES, 'UTF-8'), "!\n";
});

echo "match /hello/world:\n";
$router->dispatch('GET', '/hello/world');
echo "OK\n";
