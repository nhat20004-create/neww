<?php
$dsn = "mysql:host=localhost;dbname=quanlybanhang;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}
?>
