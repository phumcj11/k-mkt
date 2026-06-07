<?php
header('Content-Type: application/json; charset=utf-8');

$status = [
    'site' => 'k-mkt.com',
    'php' => PHP_VERSION,
    'config' => file_exists(__DIR__ . '/config.local.php'),
    'database' => false,
    'db_name' => null,
    'admin' => is_dir(__DIR__ . '/admin'),
];

if ($status['config']) {
    try {
        require_once __DIR__ . '/includes/db.php';
        db();
        $status['database'] = true;
        $status['db_name'] = DB_NAME;
        $status['users'] = (int)db()->query('SELECT COUNT(*) FROM users')->fetchColumn();
    } catch (Throwable $e) {
        $status['database_error'] = $e->getMessage();
    }
} else {
    $status['hint'] = 'รัน deploy.bat เพื่อสร้าง config.local.php';
}

echo json_encode($status, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
