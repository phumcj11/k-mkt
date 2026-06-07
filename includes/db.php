<?php
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('ไม่สามารถเชื่อมต่อ Database ได้ — รัน deploy.bat เพื่อตั้งค่า pcj_kmkt');
        }
    }
    return $pdo;
}

function db_available(): bool
{
    try {
        db();
        return true;
    } catch (Throwable $e) {
        return false;
    }
}
