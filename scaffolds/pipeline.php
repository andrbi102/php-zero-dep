<?php

declare(strict_types=1);

/**
 * Pipeline مدل onion — زیر ۳۰ خط، با callable خالص
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class Pipeline
{
    /** @param array<int, callable> $stages هر stage: fn(mixed $payload, callable $next): mixed */
    public function __construct(private array $stages)
    {
    }

    public function process(mixed $payload): mixed
    {
        $next = array_reduce(
            array_reverse($this->stages),
            static fn (callable $carry, callable $stage): callable =>
                static fn (mixed $p): mixed => $stage($p, $carry),
            static fn (mixed $p): mixed => $p
        );

        return $next($payload);
    }
}
