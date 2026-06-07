<?php
if (!defined('KMKT_ROOT')) {
    define('KMKT_ROOT', dirname(__DIR__));
}

$localConfig = KMKT_ROOT . '/config.local.php';
if (!file_exists($localConfig)) {
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, "Missing config.local.php — copy from config.local.php.example\n");
        exit(1);
    }
    http_response_code(500);
    die('Configuration missing. Copy config.local.php.example to config.local.php');
}

$config = require $localConfig;

define('DB_HOST', $config['db_host']);
define('DB_NAME', $config['db_name']);
define('DB_USER', $config['db_user']);
define('DB_PASS', $config['db_pass']);
define('DB_CHARSET', $config['db_charset'] ?? 'utf8mb4');
define('SITE_URL', rtrim($config['site_url'], '/'));
define('UPLOAD_DIR', $config['upload_dir'] ?? KMKT_ROOT . '/uploads');
define('UPLOAD_URL', rtrim($config['upload_url'] ?? '/uploads', '/'));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
