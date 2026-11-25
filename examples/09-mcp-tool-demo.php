<?php

declare(strict_types=1);

/**
 * مثال ۹ — سرور MCP: فهرست ابزارها و فراخوانی با اعتبارسنجی ورودی
 * اجرا: php examples/09-mcp-tool-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/mcp-server.php';
require __DIR__ . '/../scaffolds/tools/hello.php'; // $tool

$server = new McpServer();
$server->loadTools(__DIR__ . '/../scaffolds/tools');

$list = $server->handle('{"jsonrpc":"2.0","id":1,"method":"tools/list"}');
echo $list, "\n";

$ok = $server->handle('{"jsonrpc":"2.0","id":2,"method":"tools/call","params":{"name":"hello","arguments":{"name":"PHP"}}}');
echo $ok, "\n";

$bad = $server->handle('{"jsonrpc":"2.0","id":3,"method":"tools/call","params":{"name":"hello","arguments":{"name":""}}}');
echo $bad, "\n";

echo str_contains($ok, 'Hello, PHP') && str_contains($bad, 'non-empty string') ? "OK\n" : "FAIL\n";
