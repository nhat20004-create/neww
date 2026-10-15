<?php
include "auth.php";

$id = $_GET["id"] ?? null;
$dsAnh = ds_file_anh();

if (!$id || !ctype_digit((string)$id)) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM mon WHERE id = ?");
$stmt->execute([$id]);
$mon = $stmt->fetch();

if (!$mon) {
    header("Location: index.php");
    exit;
}

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

    $stmt = $pdo->prepare(
        "UPDATE mon
         SET ten_mon = ?, gia = ?, mo_ta = ?, danh_muc = ?, trang_thai = ?, hinh_anh = ?
         WHERE id = ?"
    );
    $stmt->execute([$ten, $gia, $moTa, $danhMuc, $trangThai, $hinhAnh, $id]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa món</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Sửa món</h2>
        <p class="subtitle">Mã món: <?= (int)$mon["id"] ?></p>

        <?php if (!empty($mon["hinh_anh"])) { ?>
            <img class="card-img" src="../hinhanh/<?= htmlspecialchars($mon["hinh_anh"]) ?>" alt="">
        <?php } ?>

        <form method="POST">
            <label>Tên món</label>
            <input type="text" name="ten_mon"
                   value="<?= htmlspecialchars($mon["ten_mon"]) ?>" required>

            <label>Giá size S (VNĐ)</label>
            <input type="number" name="gia" min="0" step="1000"
                   value="<?= htmlspecialchars((string)$mon["gia"]) ?>" required>

            <label>Mô tả</label>
            <input type="text" name="mo_ta"
                   value="<?= htmlspecialchars($mon["mo_ta"]) ?>">

            <label>Danh mục</label>
            <input type="text" name="danh_muc"
                   value="<?= htmlspecialchars($mon["danh_muc"]) ?>" required>

            <label>Ảnh món</label>
            <select name="hinh_anh">
                <option value="">-- Không ảnh --</option>
                <?php foreach ($dsAnh as $anh) { ?>
                    <option value="<?= htmlspecialchars($anh) ?>"
                        <?= ($mon["hinh_anh"] ?? "") === $anh ? "selected" : "" ?>>
                        <?= htmlspecialchars($anh) ?>
                    </option>
                <?php } ?>
            </select>

            <label>Trạng thái</label>
            <select name="trang_thai">
                <option value="1" <?= (int)$mon["trang_thai"] === 1 ? "selected" : "" ?>>Đang bán</option>
                <option value="0" <?= (int)$mon["trang_thai"] === 0 ? "selected" : "" ?>>Ẩn khỏi menu</option>
            </select>

            <div class="form-actions">
                <a class="back" href="index.php">Quay lại</a>
                <button type="submit">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
