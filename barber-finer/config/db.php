<?php
declare(strict_types=1);

/*
 * BarberFiner - Database Configuration
 * متغیرهای محیطی از Docker Compose تزریق می‌شوند
 */

$host   = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'barberfiner';
$user   = getenv('DB_USER') ?: 'root';
$pass   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
];

try {
    $conn = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $pass,
        $options
    );
} catch (PDOException $e) {
    // اگر دیتابیس وجود نداشت بسازیم
    if (in_array((int)$e->getCode(), [1049]) || str_contains($e->getMessage(), 'Unknown database')) {
        try {
            $rootConn = new PDO(
                "mysql:host={$host};charset=utf8mb4",
                $user,
                $pass,
                $options
            );
            $rootConn->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $schemaFile = __DIR__ . '/../database/schema.sql';
            if (file_exists($schemaFile)) {
                $rootConn->exec("USE `{$dbname}`");
                $sql = file_get_contents($schemaFile);
                // اجرای چندباره statement ها
                foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
                    if ($stmt !== '') {
                        try { $rootConn->exec($stmt); } catch (Throwable) {}
                    }
                }
            }
            $conn = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $user,
                $pass,
                $options
            );
        } catch (Throwable $ex) {
            error_log('BarberFiner DB Init Error: ' . $ex->getMessage());
            http_response_code(503);
            die('<h2>خطا در اتصال به پایگاه داده. لطفاً دوباره تلاش کنید.</h2>');
        }
    } else {
        error_log('BarberFiner DB Error: ' . $e->getMessage());
        http_response_code(503);
        die('<h2>خطا در اتصال به پایگاه داده.</h2>');
    }
}
