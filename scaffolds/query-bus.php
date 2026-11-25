<?php

declare(strict_types=1);

/**
 * Query Bus — قرینه خواندنی CommandBus (جداسازی CQRS)
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class QueryBus
{
    /** @var array<string, callable> */
    private array $handlers = [];

    public function register(string $queryClass, callable $handler): void
    {
        $this->handlers[$queryClass] = $handler;
    }

    public function ask(object $query): mixed
    {
        $handler = $this->handlers[$query::class]
            ?? throw new LogicException('No handler for ' . $query::class);

        return $handler($query);
    }
}
