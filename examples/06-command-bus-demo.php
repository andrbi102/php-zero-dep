<?php

declare(strict_types=1);

/**
 * مثال ۶ — Command Bus (CQRS حداقلی)
 * اجرا: php examples/06-command-bus-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/command-bus.php';
require __DIR__ . '/../scaffolds/query-bus.php';

final class RegisterUser
{
    public function __construct(public readonly string $email)
    {
    }
}

final class FindUser
{
    public function __construct(public readonly string $email)
    {
    }
}

$commands = new CommandBus();
$commands->register(RegisterUser::class, static fn (RegisterUser $c): string => 'registered:' . $c->email);

$queries = new QueryBus();
$queries->register(FindUser::class, static fn (FindUser $q): ?string => 'found:' . $q->email);

echo $commands->dispatch(new RegisterUser('annie@example.com')), "\n";
echo $queries->ask(new FindUser('annie@example.com')), "\n";
echo "OK\n";
