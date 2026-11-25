<?php

declare(strict_types=1);

/**
 * Event Store با PDO — جدول events با ایندکس aggregate_id
 *
 * توجه Dialect: DDL این اسکلت گویش SQLite است (AUTOINCREMENT)؛
 * برای MySQL/PostgreSQL متناسب‌سازی کنید (SERIAL/BIGSERIAL).
 *
 * @package PhpZeroDep\Scaffolds
 * @version 0.0.2
 * @security-level data
 */
final class EventStore
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS events ('
            . 'id INTEGER PRIMARY KEY AUTOINCREMENT, '
            . 'aggregate_id VARCHAR(64) NOT NULL, '
            . 'type VARCHAR(128) NOT NULL, '
            . 'payload TEXT NOT NULL, '
            . 'occurred_at TEXT NOT NULL)'
        );
        $this->pdo->exec(
            'CREATE INDEX IF NOT EXISTS idx_events_agg ON events (aggregate_id, id)'
        );
    }

    public function append(string $aggregateId, string $type, array $payload): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO events (aggregate_id, type, payload, occurred_at) '
            . 'VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([
            $aggregateId,
            $type,
            (string) json_encode($payload, JSON_UNESCAPED_UNICODE),
            date('c'),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @return array<int, array{id:int, aggregate_id:string, type:string, payload:mixed, occurred_at:string}>
     */
    public function stream(string $aggregateId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, aggregate_id, type, payload, occurred_at '
            . 'FROM events WHERE aggregate_id = ? ORDER BY id'
        );
        $stmt->execute([$aggregateId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as &$row) {
            $row['id'] = (int) $row['id'];
            $row['payload'] = json_decode((string) $row['payload'], true);
        }

        return $rows;
    }

    /**
     * بازپخش رویدادها برای بازسازی وضعیت aggregate.
     *
     * @template T of object
     * @param T $aggregate
     * @param callable(T, array<string, mixed>):void $reduce
     * @return T
     */
    public function replay(string $aggregateId, object $aggregate, callable $reduce): object
    {
        foreach ($this->stream($aggregateId) as $event) {
            $reduce($aggregate, $event);
        }

        return $aggregate;
    }
}
