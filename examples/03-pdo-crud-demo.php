<?php

declare(strict_types=1);

/**
 * مثال ۳ — CRUD با PDO Prepared Statement روی SQLite
 * اجرا: php examples/03-pdo-crud-demo.php
 *
 * @package PhpZeroDep\Examples
 * @version 0.0.1
 */
require __DIR__ . '/../scaffolds/database.php';

$db = new Database('sqlite::memory:');
echo "driver: ", $db->driver(), "\n";

$db->execute('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, email TEXT NOT NULL)');

// INSERT — همیشه پارامتری، هرگز الحاق رشته
$db->execute('INSERT INTO users (name, email) VALUES (?, ?)', ['آنیه', 'annie@example.com']);
$db->execute('INSERT INTO users (name, email) VALUES (?, ?)', ['سارا', 'sara@example.com']);

// UPSERT (گویش SQLite: ON CONFLICT — در PostgreSQL همان است، در MySQL: ON DUPLICATE KEY)
$db->execute(
    'INSERT INTO users (id, name, email) VALUES (?, ?, ?) ON CONFLICT(id) DO UPDATE SET name = excluded.name',
    [1, 'آنیه به‌روزرسانی‌شده', 'annie@example.com']
);

$rows = $db->query('SELECT id, name, email FROM users ORDER BY id');
foreach ($rows as $row) {
    echo (int) $row['id'], ' | ', (string) $row['name'], ' | ', (string) $row['email'], "\n";
}
echo count($rows) === 2 ? "OK (2 rows)\n" : "FAIL\n";
