<?php
$dsn = "mysql:host=localhost;dbname=baithuchanhsql;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $dsSinhVien = $pdo->query("SELECT id, ten_lop, giang_vien, thoi_gian FROM lop order by id desc")->fetchAll();
    echo json_encode($dsSinhVien);
    
}catch (PDOException $e) {
    echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
}
?>