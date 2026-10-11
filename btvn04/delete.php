<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"] ?? "";

    if (ctype_digit((string)$id) && (int)$id > 0) {
        $stmt = $pdo->prepare("DELETE FROM sanpham WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: index.php");
exit;
?>