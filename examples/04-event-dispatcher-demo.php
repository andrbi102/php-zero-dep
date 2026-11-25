<?php

declare(strict_types=1);

/**
 * مثال ۴ — Event Dispatcher دست‌نویس
 * اجرا: php examples/04-event-dispatcher-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.2
 */
require __DIR__ . '/../scaffolds/event-dispatcher.php';

final class UserRegistered
{
    public function __construct(public readonly string $email)
    {
    }
}

$dispatcher = new EventDispatcher();
$seen = [];
$dispatcher->listen(UserRegistered::class, static function (UserRegistered $e) use (&$seen): void {
    $seen[] = 'welcome:' . $e->email;
});
$dispatcher->listen(UserRegistered::class, static function (UserRegistered $e) use (&$seen): void {
    $seen[] = 'notify:' . $e->email;
});

$dispatcher->dispatch(new UserRegistered('annie@example.com'));
echo implode('; ', $seen), "\n";
echo count($seen) === 2 ? "OK\n" : "FAIL\n";
