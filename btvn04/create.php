<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten = trim($_POST["ten_san_pham"]);
    $gia = $_POST["gia"];
    $soLuong = $_POST["so_luong"];

    $sql = "INSERT INTO sanpham (ten_san_pham, gia, so_luong)
            VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ten, $gia, $soLuong]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            margin: 0;
            padding: 30px 15px;
        }

        .form-box {
            max-width: 420px;
            margin: auto;
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #00000010;
        }

        h2 {
            margin: 0 0 20px;
            color: #1e293b;
            font-size: 22px;
        }

        label {
            display: block;
            margin: 14px 0 6px;
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 22px;
        }

        button, .back {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        button {
            background: #2563eb;
            color: white;
        }

        .back {
            background: #e5e7eb;
            color: #374151;
        }

        button:hover { background: #1d4ed8; }
        .back:hover { background: #d1d5db; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Thêm sản phẩm</h2>

        <form method="POST">
            <label>Tên sản phẩm</label>
            <input type="text" name="ten_san_pham"
                   placeholder="Nhập tên sản phẩm" required>

            <label>Giá (VNĐ)</label>
            <input type="number" name="gia" min="0" step="0.01"
                   placeholder="Nhập giá" required>

            <label>Số lượng</label>
            <input type="number" name="so_luong" min="0"
                   placeholder="Nhập số lượng" required>

            <div class="actions">
                <a class="back" href="index.php">Quay lại</a>
                <button type="submit">Thêm sản phẩm</button>
            </div>
        </form>
    </div>
</body>
</html>