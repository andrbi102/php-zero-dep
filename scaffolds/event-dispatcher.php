<?php

declare(strict_types=1);

/**
 * Event Dispatcher سبک PSR-14 — زیر ۵۰ خط، دست‌نویس
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class EventDispatcher
{
    /** @var array<string, array<int, callable>> */
    private array $listeners = [];

    public function listen(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function dispatch(object $event): object
    {
        foreach ($this->listeners[$event::class] ?? [] as $listener) {
            $listener($event);
        }

        return $event;
    }
}
