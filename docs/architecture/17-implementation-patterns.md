# الگوهای پیاده‌سازی کلیدی (کد)

**Event Dispatcher (PSR-14 سبک، < ۵۰ خط):** `scaffolds/event-dispatcher.php`

```php
final class EventDispatcher {
    private array $listeners = [];
    public function listen(string $eventClass, callable $listener): void {
        $this->listeners[$eventClass][] = $listener;
    }
    public function dispatch(object $event): object {
        foreach ($this->listeners[$event::class] ?? [] as $listener) {
            $listener($event);
        }
        return $event;
    }
}
```

**Pipeline (onion، < ۳۰ خط):** `scaffolds/pipeline.php` — با
`array_reduce` معکوس و callable خالص.

**Command Bus (CQRS حداقلی):** `scaffolds/command-bus.php` — نگاشت
نام کلاس به handler با پرتاب LogicException

### ✅ Checklist

- [ ] همه سه الگو از scaffolds برداشته می‌شوند، نه بازنویسی
- [ ] هر الگو تست واحد مستقل دارد (examples/04..06)

### ❌ Anti-patterns
- Dispatcher با reflection و magic strings
- Pipeline با ارث‌بری و کلاس‌های abstract زنجیره‌ای

---

> مهارت `php-zero-dep` — نسخه `0.0.1`
