<?php

declare(strict_types=1);

/**
 * تست دود — همه اسکلت‌ها بارگذاری و رفتار پایه بررسی می‌شود.
 * اجرا: php tests/smoke-test.php   (خروجی 0 یعنی PASS)
 *
 * @package PhpZeroDep\Tests
 * @version 0.0.1
 */
$root = dirname(__DIR__) . '/scaffolds';
$failed = 0;

function check(string $label, bool $condition): void
{
    global $failed;
    echo $condition ? "  PASS  {$label}\n" : "  FAIL  {$label}\n";
    if (!$condition) {
        $failed++;
    }
}

$scaffolds = [
    'autoloader.php', 'router.php', 'container.php', 'database.php',
    'session.php', 'csrf.php', 'validator.php', 'view.php', 'logger.php',
    'error-handler.php', 'rate-limiter.php', 'cache.php', 'http-client.php',
    'crypto.php', 'graph.php', 'event-dispatcher.php', 'event-store.php',
    'command-bus.php', 'query-bus.php', 'pipeline.php', 'hmvc-dispatcher.php',
    'serverless-handler.php', 'mcp-server.php', 'tracing.php', 'db-migrate.php',
];

foreach ($scaffolds as $file) {
    check("exists scaffolds/{$file}", is_file("{$root}/{$file}"));
}

foreach ($scaffolds as $file) {
    if (is_file("{$root}/{$file}")) {
        require "{$root}/{$file}";
    }
}

$classes = [
    'Autoloader', 'Router', 'Container', 'Database', 'SecureSession', 'Csrf',
    'Validator', 'View', 'Logger', 'ErrorHandler', 'RateLimiter', 'FileCache',
    'HttpClient', 'Crypto', 'Graph', 'EventDispatcher', 'EventStore',
    'CommandBus', 'QueryBus', 'Pipeline', 'HmvcDispatcher', 'ServerlessHandler',
    'McpServer', 'Tracing', 'Migrator',
];
foreach ($classes as $class) {
    check("class {$class} loaded", class_exists($class));
}

// — رفتار پایه —
$graph = new Graph();
$graph->addEdge('a', 'b');
$graph->addEdge('a', 'c');
$graph->addEdge('b', 'd');
check('graph bfs order', $graph->bfs('a') === ['a', 'b', 'c', 'd']);
check('graph acyclic', !$graph->hasCycle());

$pipeline = new Pipeline([
    static fn ($p, $next) => '[' . $next($p) . ']',
    static fn ($p, $next) => $next(strtoupper((string) $p)),
]);
check('pipeline wraps+uppercases', $pipeline->process('x') === '[X]');

final class DemoCommand
{
}

$bus = new CommandBus();
$bus->register(DemoCommand::class, static fn (): string => 'done');
check('command bus dispatch', $bus->dispatch(new DemoCommand()) === 'done');

$v = new Validator(['email' => 'not-an-email']);
$v->required('email')->email('email');
check('validator rejects bad email', $v->fails());

check('view escape', View::e('<script>"x"</script>') === '&lt;script&gt;&quot;x&quot;&lt;/script&gt;');

echo $failed === 0 ? "\nSMOKE TEST: PASS\n" : "\nSMOKE TEST: {$failed} FAILURE(S)\n";
exit($failed === 0 ? 0 : 1);
