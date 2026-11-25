<?php

declare(strict_types=1);

/**
 * سرور MCP تک‌فایل — JSON-RPC روی stdio، هر Tool یک فایل مستقل
 *
 * اجرا:  php scaffolds/mcp-server.php serve ./tools
 * جایگزین آماده: VibeShell (github.com/d3cline/VibeShell — PHP 7.4+)
 *
 * MCP یک پروتکل است نه پکیج PHP: سرور در فرایند جداگانه اجرا می‌شود
 * و کد PHP تولیدشده هیچ import از MCP ندارد (SPEC بخش ۱۱).
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level tool
 */
final class McpServer
{
    /** @var array<string, array{name:string, description:string, input:array, execute:callable}> */
    private array $tools = [];

    /**
     * بارگذاری همه فایل‌های tools/*.php — هر فایل آرایه Tool برمی‌گرداند.
     */
    public function loadTools(string $dir): void
    {
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            $tool = require $file;
            if (!is_array($tool) || !isset($tool['name'], $tool['description'], $tool['input'], $tool['execute'])) {
                throw new RuntimeException('invalid tool file: ' . basename($file));
            }
            $this->tools[$tool['name']] = $tool;
        }
    }

    public function handle(string $input): string
    {
        $message = json_decode($input, true);
        if (!is_array($message) || !isset($message['id'], $message['method'])) {
            return $this->error(null, -32700, 'Parse error');
        }

        return match ($message['method']) {
            'tools/list' => $this->result($message['id'], ['tools' => array_values(array_map(
                static fn (array $t): array => [
                    'name' => $t['name'],
                    'description' => $t['description'],
                    'input' => $t['input'],
                ],
                $this->tools
            ))]),
            'tools/call' => $this->call($message),
            default => $this->error($message['id'], -32601, 'Method not found'),
        };
    }

    /** @param array<string, mixed> $message */
    private function call(array $message): string
    {
        $name = $message['params']['name'] ?? null;
        $args = $message['params']['arguments'] ?? [];
        if (!is_string($name) || !isset($this->tools[$name])) {
            return $this->error($message['id'], -32602, 'Unknown tool');
        }
        if (!is_array($args)) {
            return $this->error($message['id'], -32602, 'arguments must be an object');
        }
        try {
            $output = (string) $this->tools[$name]['execute']($args);
        } catch (InvalidArgumentException $e) {
            return $this->error($message['id'], -32602, $e->getMessage());
        } catch (Throwable) {
            return $this->error($message['id'], -32603, 'Internal error');
        }

        return $this->result($message['id'], [
            'content' => [['type' => 'text', 'text' => $output]],
        ]);
    }

    private function result(int|string $id, array $result): string
    {
        return (string) json_encode(
            ['jsonrpc' => '2.0', 'id' => $id, 'result' => $result],
            JSON_UNESCAPED_UNICODE
        );
    }

    private function error(int|string|null $id, int $code, string $message): string
    {
        return (string) json_encode(
            ['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => $code, 'message' => $message]],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function serve(): void
    {
        while (true) {
            $line = fgets(STDIN);
            if ($line === false) {
                break;
            }
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            fwrite(STDOUT, $this->handle($line) . "\n");
        }
    }
}

if (PHP_SAPI === 'cli' && isset($argv[1], $argv[2]) && $argv[1] === 'serve' && is_dir($argv[2])) {
    $server = new McpServer();
    $server->loadTools($argv[2]);
    $server->serve();
}
