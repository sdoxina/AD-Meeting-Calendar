<?php
declare(strict_types=1);

// 1) Composer autoload
require 'vendor/autoload.php';

// 2) Composer bootstrap
require 'bootstrap.php';

// 3) envSetter
$typeConfig = require_once __DIR__ . '/envSetter.util.php';

// ——— Prepare config array ———
$pgConfig = [
    'host' => $typeConfig['pg_host'],
    'port' => $typeConfig['pg_port'],
    'db'   => $typeConfig['pg_db'],
    'user' => $typeConfig['pg_user'],
    'pass' => $typeConfig['pg_pass'],
];

// ——— Connect to PostgreSQL ———
$dsn = "pgsql:host={$pgConfig['host']};port={$pgConfig['port']};dbname={$pgConfig['db']}";

try {
    $pdo = new PDO($dsn, $pgConfig['user'], $pgConfig['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Connected to PostgreSQL\n";
} catch (PDOException $e) {
    echo "❌ Connection Failed: " . $e->getMessage() . "\n";
    exit(1);
}

// ——— Apply schemas ———
$modelFiles = [
    'user.model.sql',
    'meeting.model.sql',
    'meeting_users.model.sql',
    'tasks.model.sql',
];

foreach ($modelFiles as $modelFile) {
    $path = __DIR__ . "/../database/{$modelFile}";
    echo "Applying schema from {$path}…\n";

    $sql = @file_get_contents($path);

    if ($sql === false) {
        echo "❌ Could not read {$path}\n";
        exit(1);
    } else {
        echo "✅ Creation Success from {$path}\n";
    }

    $pdo->exec($sql);
}

// ——— TRUNCATE tables ———
echo "Truncating tables…\n";

$tables = ['meeting_users', 'tasks', 'meetings', 'users'];

foreach ($tables as $table) {
    $pdo->exec("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE;");
    echo "✅ Truncated table: {$table}\n";
}

echo "✅ Schema reset complete. Ready to seed…\n";

// ——— Load Dummy Data ———
define('DUMMIES_PATH', __DIR__ . '/../staticDatas/dummies');

// Example: load USERS dummy data
$users = require_once DUMMIES_PATH . '/users.staticData.php';

// ——— Seeding USERS ———
echo "🌱 Seeding users…\n";

$stmt = $pdo->prepare("
    INSERT INTO users (username, role, first_name, last_name, password)
    VALUES (:username, :role, :fn, :ln, :pw)
");

foreach ($users as $u) {
    $stmt->execute([
        ':username' => $u['username'],
        ':role'     => $u['role'],
        ':fn'       => $u['first_name'],
        ':ln'       => $u['last_name'],
        ':pw'       => password_hash($u['password'], PASSWORD_DEFAULT),
    ]);
}

echo "✅ Users seeded successfully!\n";

echo "🎉 PostgreSQL Seeding Complete!\n";
