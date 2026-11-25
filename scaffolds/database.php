<?php

declare(strict_types=1);

/**
 * Wrapper امن PDO با تشخیص Dialect
 *
 * قواعد SPEC: همه کوئری‌ها Prepared Statement؛ Dialect شناسایی شود
 * (JSONB فقط PostgreSQL؛ در SQLite نوع JSON یعنی TEXT).
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level data
 */
final class Database
{
    private PDO $pdo;

    private string $driver;

    /**
     * @param string $dsn مثال: 'sqlite::memory:' یا 'mysql:host=localhost;dbname=app;charset=utf8mb4'
     */
    public function __construct(string $dsn, string $user = '', string $pass = '', array $options = [])
    {
        $defaults = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $options + $defaults);
        $this->driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function isSqlite(): bool
    {
        return $this->driver === 'sqlite';
    }

    public function isMysql(): bool
    {
        return str_contains($this->driver, 'mysql');
    }

    public function isPgsql(): bool
    {
        return $this->driver === 'pgsql';
    }

    /**
     * SELECT از پیش‌آماده — همیشه آرایه پارامتر، هرگز الحاق رشته.
     *
     * @param array<string|int, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * INSERT/UPDATE/DELETE از پیش‌آماده.
     *
     * @param array<string|int, mixed> $params
     * @return int تعداد سطرهای تغییر یافته
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount();
    }

    /**
     * تراکنش با rollback خودکار روی هر Throwable.
     *
     * @template T
     * @param callable(self):T $fn
     * @return T
     */
    public function transaction(callable $fn): mixed
    {
        $this->pdo->beginTransaction();
        try {
            $result = $fn($this);
            $this->pdo->commit();

            return $result;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}
