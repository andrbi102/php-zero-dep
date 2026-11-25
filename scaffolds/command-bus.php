<?php

declare(strict_types=1);

/**
 * Command Bus حداقلی (CQRS) — دست‌نویس
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class CommandBus
{
    /** @var array<string, callable> */
    private array $handlers = [];

    public function register(string $commandClass, callable $handler): void
    {
        $this->handlers[$commandClass] = $handler;
    }

    public function dispatch(object $command): mixed
    {
        $handler = $this->handlers[$command::class]
            ?? throw new LogicException('No handler for ' . $command::class);

        return $handler($command);
    }
}
