<?php

declare(strict_types=1);

/**
 * دیسپچر HMVC — فقط این نقطه ماژول‌ها را اجرا می‌کند
 *
 * ضد الگو: new Controller() مستقیم از ماژول دیگر (SPEC بخش ۴).
 * نام action با الگوی anchored و محدود اعتبارسنجی می‌شود.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class HmvcDispatcher
{
    /** @var array<string, callable(string, array<string,mixed>):mixed> */
    private array $modules = [];

    /**
     * @param callable(string, array<string, mixed>):mixed $handler fn(string $action, array $params): mixed
     */
    public function register(string $module, callable $handler): void
    {
        $this->modules[$module] = $handler;
    }

    public function dispatch(string $module, string $action, array $params = []): mixed
    {
        $handler = $this->modules[$module]
            ?? throw new RuntimeException("Unknown module '{$module}'");
        // anchored + فقط حروف کوچک/عدد/زیرخط — regex محدود و امن
        if (!preg_match('/^[a-z][a-z0-9_]{0,63}$/', $action)) {
            throw new InvalidArgumentException('Invalid action name');
        }

        return $handler($action, $params);
    }
}
