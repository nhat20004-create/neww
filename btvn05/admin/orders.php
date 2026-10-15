<?php
include "auth.php";

function class_trang_thai($st)
{
    if ($st === "Đang làm") return "st-lam";
    if ($st === "Đang giao") return "st-giao";
    if ($st === "Hoàn thành") return "st-xong";
    if ($st === "Đã hủy") return "st-huy";
    return "st-cho";
}

$dsTrangThai = ["Chờ xác nhận", "Đang làm", "Đang giao", "Hoàn thành", "Đã hủy"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"] ?? "";
    $trangThai = $_POST["trang_thai"] ?? "";

    if (ctype_digit((string)$id) && in_array($trangThai, $dsTrangThai, true)) {
        $stmt = $pdo->prepare("UPDATE don_hang SET trang_thai = ? WHERE id = ?");
        $stmt->execute([$trangThai, $id]);
    }

    header("Location: orders.php");
    exit;
}

$loc = $_GET["trang_thai"] ?? "";
$sql = "SELECT * FROM don_hang";
$params = [];

if ($loc !== "" && in_array($loc, $dsTrangThai, true)) {
    $sql .= " WHERE trang_thai = ?";
    $params[] = $loc;
}

$sql .= " ORDER BY ngay_dat DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dsDon = $stmt->fetchAll();

foreach ($dsDon as $i => $don) {
    $stmtCt = $pdo->prepare("SELECT * FROM chi_tiet_don WHERE don_hang_id = ?");
    $stmtCt->execute([$don["id"]]);
    $dsDon[$i]["chi_tiet"] = $stmtCt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Lịch sử đơn hàng", "Xem đơn, trạng thái và cập nhật tiến độ pha chế / giao hàng.", true); ?>

    <nav class="nav">
        <a href="index.php">Quản lý món</a>
        <a class="active" href="orders.php">Đơn hàng</a>
        <a href="stats.php">Thống kê</a>
        <a href="../index.php">Xem trang khách</a>
        <a href="logout.php">Đăng xuất</a>
    </nav>

    <div class="toolbar">
        <h2>Tất cả đơn</h2>
        <form class="search-form" method="GET">
            <select name="trang_thai">
                <option value="">Mọi trạng thái</option>
                <?php foreach ($dsTrangThai as $st) { ?>
                    <option value="<?= htmlspecialchars($st) ?>" <?= $loc === $st ? "selected" : "" ?>>
                        <?= htmlspecialchars($st) ?>
                    </option>
                <?php } ?>
            </select>
            <button class="btn view" type="submit">Lọc</button>
        </form>
    </div>

    <?php if (count($dsDon) === 0) { ?>
        <div class="table-card">
            <p class="empty">Chưa có đơn hàng nào.</p>
        </div>
    <?php } else { ?>
        <?php foreach ($dsDon as $don) { ?>
            <div class="table-card order-card">
                <div class="order-head">
                    <div>
                        <span class="lbl">Mã đơn</span>
                        <span class="val">#<?= (int)$don['id'] ?></span>
                    </div>
                    <div>
                        <span class="lbl">Thời gian</span>
                        <span class="val"><?= htmlspecialchars($don['ngay_dat']) ?></span>
                    </div>
                    <div>
                        <span class="lbl">Khách hàng</span>
                        <span class="val">
                            <?= htmlspecialchars($don['ten_khach']) ?><br>
                            <?= htmlspecialchars($don['so_dien_thoai']) ?>
                        </span>
                    </div>
                    <div>
                        <span class="lbl">Địa chỉ</span>
                        <span class="val"><?= htmlspecialchars($don['dia_chi']) ?></span>
                    </div>
                </div>

                <?php if ($don['ghi_chu'] !== "") { ?>
                    <div class="order-note">Ghi chú: <?= htmlspecialchars($don['ghi_chu']) ?></div>
                <?php } ?>

                <div class="table-wrapper">
                    <table class="order-table">
                        <thead>
                        <tr>
                            <th>Món</th>
                            <th>Size / Topping</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($don['chi_tiet'] as $ct) { ?>
                            <tr>
                                <td class="product-name"><?= htmlspecialchars($ct['ten_mon']) ?></td>
                                <td>
                                    Size <?= htmlspecialchars($ct['size'] ?? 'M') ?>
                                    <div class="muted">
                                        <?= !empty($ct['topping']) ? htmlspecialchars($ct['topping']) : 'Không topping' ?>
                                    </div>
                                </td>
                                <td><?= (int)$ct['so_luong'] ?> ly</td>
                                <td class="price"><?= dinh_dang_tien($ct['don_gia']) ?></td>
                                <td class="price"><?= dinh_dang_tien($ct['thanh_tien']) ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="order-foot">
                    <div>
                        <strong>Tổng: <?= dinh_dang_tien($don['tong_tien']) ?></strong>
                        <span class="badge <?= class_trang_thai($don['trang_thai']) ?>" style="margin-left:8px">
                            <?= htmlspecialchars($don['trang_thai']) ?>
                        </span>
                    </div>
                    <form method="POST" class="search-form">
                        <input type="hidden" name="id" value="<?= (int)$don['id'] ?>">
                        <select name="trang_thai">
                            <?php foreach ($dsTrangThai as $st) { ?>
                                <option value="<?= htmlspecialchars($st) ?>"
                                    <?= $don['trang_thai'] === $st ? "selected" : "" ?>>
                                    <?= htmlspecialchars($st) ?>
                                </option>
                            <?php } ?>
                        </select>
                        <button class="btn edit" type="submit">Cập nhật</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="footer">Ngọt - Quản trị đơn hàng</div>
</div>
</body>
</html>
