<?php

declare(strict_types=1);

/**
 * اجراکننده Migration — قابل اجرا از CLI و از مرورگر (هاست اشتراکی)
 *
 * هر migration یک فایل .sql با نام مرتب (001_create_users.sql و ...).
 * نام فایل‌های اعمال‌شده در جدول migrations ثبت می‌شود.
 *
 * CLI:  php db-migrate.php <sqlite-path> <migrations-dir>
 * Web:  همین کلاس را از یک endpoint محافظت‌شده (مثلاً با توکن
 *       محیط‌از-پیکربندی) فراخوانی کنید — خروجی همیشه escape می‌شود.
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level data
 */
final class Migrator
{
    public function __construct(
        private PDO $pdo,
        private string $dir,
    ) {
    }

    public function ensureTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations ('
            . 'name VARCHAR(255) PRIMARY KEY, '
            . 'applied_at TEXT NOT NULL)'
        );
    }

    /** @return array<int, string> نام فایل‌های .sql اعمال‌نشده به ترتیب */
    public function pending(): array
    {
        $applied = array_map(
            'strval',
            $this->pdo->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN)
        );
        $files = glob($this->dir . '/*.sql') ?: [];
        sort($files);

        return array_values(array_filter(
            $files,
            static fn (string $f): bool => !in_array(basename($f), $applied, true)
        ));
    }

    /** @return array<int, string> نام migrationهای اعمال‌شده در این اجرا */
    public function migrate(): array
    {
        $this->ensureTable();
        $appliedNow = [];
        $insert = $this->pdo->prepare(
            'INSERT INTO migrations (name, applied_at) VALUES (?, ?)'
        );
        foreach ($this->pending() as $file) {
            $sql = (string) file_get_contents($file);
            $this->pdo->exec($sql);
            $insert->execute([basename($file), date('c')]);
            $appliedNow[] = basename($file);
        }

        return $appliedNow;
    }
}

if (PHP_SAPI === 'cli' && isset($argv[2])) {
    $pdo = new PDO('sqlite:' . $argv[1], null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $migrator = new Migrator($pdo, $argv[2]);
    $applied = $migrator->migrate();
    echo $applied === [] ? "nothing to migrate\n" : implode("\n", $applied) . "\n";
}
