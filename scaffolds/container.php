<?php

declare(strict_types=1);

/**
 * کانتینر تزریق وابستگی حداقلی — دست‌نویس، بدون بازتاب جادویی
 *
 * هر سرویس با یک factory ثبت می‌شود؛ نمونه‌ها به‌صورت singleton نگه داشته
 * می‌شوند. بدون autowiring خودکار: وابستگی‌ها همیشه صریح‌اند.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level core
 */
final class Container
{
    /** @var array<string, callable> */
    private array $factories = [];

    /** @var array<string, object> */
    private array $singletons = [];

    /**
     * @param string $id شناسه سرویس (مثال: Database::class)
     */
    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id): object
    {
        if (isset($this->singletons[$id])) {
            return $this->singletons[$id];
        }
        if (!isset($this->factories[$id])) {
            throw new RuntimeException("No factory registered for '{$id}'");
        }
        $object = ($this->factories[$id])($this);
        $this->singletons[$id] = $object;

        return $object;
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]) || isset($this->singletons[$id]);
    }
}
