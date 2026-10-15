<?php
include "auth.php";

$dsAnh = ds_file_anh();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten = trim($_POST["ten_mon"] ?? "");
    $gia = $_POST["gia"] ?? 0;
    $moTa = trim($_POST["mo_ta"] ?? "");
    $danhMuc = trim($_POST["danh_muc"] ?? "Trà sữa");
    $trangThai = (int)($_POST["trang_thai"] ?? 1);
    $hinhAnh = trim($_POST["hinh_anh"] ?? "");
    if (!in_array($hinhAnh, $dsAnh, true)) {
        $hinhAnh = "";
    }

    if ($ten !== "") {
        $sql = "INSERT INTO mon (ten_mon, gia, mo_ta, danh_muc, trang_thai, hinh_anh)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ten, $gia, $moTa, $danhMuc, $trangThai, $hinhAnh]);
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm món</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Thêm món</h2>
        <form method="POST">
            <label>Tên món</label>
            <input type="text" name="ten_mon" placeholder="Nhập tên món" required>

            <label>Giá size S (VNĐ)</label>
            <input type="number" name="gia" min="0" step="1000" placeholder="Nhập giá" required>

            <label>Mô tả</label>
            <input type="text" name="mo_ta" placeholder="Mô tả ngắn">

            <label>Danh mục</label>
            <input type="text" name="danh_muc" value="Trà sữa" required>

            <label>Ảnh món</label>
            <select name="hinh_anh">
                <option value="">-- Không ảnh --</option>
                <?php foreach ($dsAnh as $anh) { ?>
                    <option value="<?= htmlspecialchars($anh) ?>"><?= htmlspecialchars($anh) ?></option>
                <?php } ?>
            </select>

            <label>Trạng thái</label>
            <select name="trang_thai">
                <option value="1">Đang bán</option>
                <option value="0">Ẩn khỏi menu</option>
            </select>

            <div class="form-actions">
                <a class="back" href="index.php">Quay lại</a>
                <button type="submit">Thêm món</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
