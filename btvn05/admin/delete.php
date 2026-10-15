<?php
include "auth.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"] ?? "";

    if (ctype_digit((string)$id) && (int)$id > 0) {
        $stmt = $pdo->prepare("DELETE FROM mon WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: index.php");
exit;
