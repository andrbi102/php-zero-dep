<?php

declare(strict_types=1);

/**
 * مثال ۵ — Pipeline مدل onion
 * اجرا: php examples/05-pipeline-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/pipeline.php';

$pipeline = new Pipeline([
    static fn (mixed $p, callable $next): mixed => '[' . $next($p) . ']',
    static fn (mixed $p, callable $next): mixed => $next(strtoupper((string) $p)),
    static fn (mixed $p, callable $next): mixed => $next((string) $p . '!'),
]);

$result = $pipeline->process('zero-dep');
echo $result, "\n";
echo $result === '[ZERO-DEP!]' ? "OK\n" : "FAIL\n";
