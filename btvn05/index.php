<?php
include "connection.php";

$tuKhoa = trim($_GET["q"] ?? "");
$danhMuc = trim($_GET["danh_muc"] ?? "");

$sql = "SELECT * FROM mon WHERE trang_thai = 1";
$params = [];

if ($tuKhoa !== "") {
    $sql .= " AND ten_mon LIKE ?";
    $params[] = "%" . $tuKhoa . "%";
}

if ($danhMuc !== "") {
    $sql .= " AND danh_muc = ?";
    $params[] = $danhMuc;
}

$sql .= " ORDER BY danh_muc ASC, ten_mon ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dsMon = $stmt->fetchAll();

$dsDanhMuc = $pdo->query("SELECT DISTINCT danh_muc FROM mon WHERE trang_thai = 1 ORDER BY danh_muc")->fetchAll();
$soGio = dem_gio_hang();
$dsSize = bang_size();
$dsTopping = bang_topping();

$quayLai = "index.php";
$qs = [];
if ($tuKhoa !== "") {
    $qs["q"] = $tuKhoa;
}
if ($danhMuc !== "") {
    $qs["danh_muc"] = $danhMuc;
}
if (count($qs) > 0) {
    $quayLai .= "?" . http_build_query($qs);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngọt - Trà sữa & đồ uống</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Ngọt", "Trà sữa & đồ uống — Ngọt ngào theo cách của bạn.."); ?>

    <nav class="nav">
        <a class="active" href="index.php">Menu</a>
        <a href="cart.php">Giỏ hàng (<?= $soGio ?>)</a>
        <a href="lookup.php">Tra cứu đơn</a>
        <a href="admin/login.php">Quản trị</a>
    </nav>

    <div class="toolbar">
        <h2>Menu hôm nay</h2>
        <form class="search-form" method="GET">
            <input type="text" name="q" placeholder="Tìm món..."
                   value="<?= htmlspecialchars($tuKhoa) ?>">
            <select name="danh_muc">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($dsDanhMuc as $dm) { ?>
                    <option value="<?= htmlspecialchars($dm['danh_muc']) ?>"
                        <?= $danhMuc === $dm['danh_muc'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dm['danh_muc']) ?>
                    </option>
                <?php } ?>
            </select>
            <button class="btn view" type="submit">Tìm</button>
        </form>
    </div>

    <?php if (count($dsMon) === 0) { ?>
        <div class="table-card">
            <p class="empty">Không tìm thấy món phù hợp.</p>
        </div>
    <?php } else { ?>
        <div class="menu-grid">
            <?php foreach ($dsMon as $mon) { ?>
                <div class="card">
                    <?php if (!empty($mon['hinh_anh'])) { ?>
                        <img class="card-img"
                             src="hinhanh/<?= htmlspecialchars($mon['hinh_anh']) ?>"
                             alt="<?= htmlspecialchars($mon['ten_mon']) ?>">
                    <?php } ?>
                    <p class="muted"><?= htmlspecialchars($mon['danh_muc']) ?></p>
                    <h3><?= htmlspecialchars($mon['ten_mon']) ?></h3>
                    <p class="muted mo-ta"><?= htmlspecialchars($mon['mo_ta']) ?></p>
                    <p class="price">Từ <?= dinh_dang_tien($mon['gia']) ?></p>
                    <a class="btn add" href="#chon-<?= (int)$mon['id'] ?>">Thêm</a>
                </div>
            <?php } ?>
        </div>

        <?php foreach ($dsMon as $mon) { ?>
            <div class="modal" id="chon-<?= (int)$mon['id'] ?>">
                <div class="modal-box">
                    <h2>Chọn size & topping</h2>
                    <p class="subtitle"><?= htmlspecialchars($mon['ten_mon']) ?> · Từ <?= dinh_dang_tien($mon['gia']) ?></p>

                    <form action="cart.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="id" value="<?= (int)$mon['id'] ?>">

                        <div class="options">
                            <div class="opt-title">Size</div>
                            <div class="size-row">
                                <?php foreach ($dsSize as $size => $phuThu) { ?>
                                    <label>
                                        <input type="radio" name="size" value="<?= htmlspecialchars($size) ?>"
                                            <?= $size === "M" ? "checked" : "" ?>>
                                        <?= htmlspecialchars($size) ?>
                                        <?php if ($phuThu > 0) { ?>
                                            (+<?= number_format($phuThu / 1000, 0) ?>k)
                                        <?php } ?>
                                    </label>
                                <?php } ?>
                            </div>

                            <div class="opt-title">Topping (không bắt buộc)</div>
                            <div class="topping-list">
                                <?php foreach ($dsTopping as $tenTop => $giaTop) { ?>
                                    <label>
                                        <input type="checkbox" name="topping[]"
                                               value="<?= htmlspecialchars($tenTop) ?>">
                                        <?= htmlspecialchars($tenTop) ?>
                                        (+<?= number_format($giaTop / 1000, 0) ?>k)
                                    </label>
                                <?php } ?>
                            </div>
                        </div>

                        <label>Số lượng</label>
                        <input type="number" name="so_luong" min="1" value="1">

                        <div class="form-actions">
                            <a class="back" href="<?= htmlspecialchars($quayLai) ?>">Hủy</a>
                            <button type="submit">Thêm vào giỏ</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="footer">Ngọt - Trà sữa & đồ uống</div>
</div>
</body>
</html>
