<?php

declare(strict_types=1);

/**
 * اعتبارسنجی ورودی — زنجیره‌ای و قابل‌گزارش
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level security
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /**
     * @param array<string, mixed> $data معمولاً $_POST یا $_GET فیلترشده
     */
    public function __construct(private array $data)
    {
    }

    public function required(string $field): self
    {
        $value = $this->data[$field] ?? null;
        if ($value === null || $value === '' || $value === []) {
            $this->errors[$field] = "فیلد «{$field}» الزامی است";
        }

        return $this;
    }

    public function length(string $field, int $min, int $max): self
    {
        $value = (string) ($this->data[$field] ?? '');
        $len = mb_strlen($value);
        if ($len < $min || $len > $max) {
            $this->errors[$field] = "طول «{$field}» باید بین {$min} و {$max} باشد";
        }

        return $this;
    }

    public function email(string $field): self
    {
        $value = filter_var((string) ($this->data[$field] ?? ''), FILTER_VALIDATE_EMAIL);
        if ($value === false) {
            $this->errors[$field] = "«{$field}» ایمیل معتبر نیست";
        }

        return $this;
    }

    public function int(string $field, ?int $min = null, ?int $max = null): self
    {
        $raw = $this->data[$field] ?? null;
        $filtered = filter_var($raw, FILTER_VALIDATE_INT);
        if ($raw === null || !is_int($filtered)) {
            $this->errors[$field] = "«{$field}» باید عدد صحیح باشد";

            return $this;
        }
        if (($min !== null && $filtered < $min) || ($max !== null && $filtered > $max)) {
            $this->errors[$field] = "«{$field}» خارج از بازه مجاز است";
        }

        return $this;
    }

    /**
     * @param array<int, string> $allowed لیست سفید مقادیر مجاز
     */
    public function in(string $field, array $allowed): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if (!in_array($value, $allowed, true)) {
            $this->errors[$field] = "مقدار «{$field}» مجاز نیست";
        }

        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
