<?php
include "connection.php";

function class_trang_thai($st)
{
    if ($st === "Đang làm") return "st-lam";
    if ($st === "Đang giao") return "st-giao";
    if ($st === "Hoàn thành") return "st-xong";
    if ($st === "Đã hủy") return "st-huy";
    return "st-cho";
}

$sdt = trim($_GET["sdt"] ?? $_POST["sdt"] ?? "");
$ok = isset($_GET["ok"]);
$dsDon = [];

if ($sdt !== "") {
    $stmt = $pdo->prepare(
        "SELECT * FROM don_hang WHERE so_dien_thoai = ? ORDER BY ngay_dat DESC"
    );
    $stmt->execute([$sdt]);
    $dsDon = $stmt->fetchAll();

    foreach ($dsDon as $i => $don) {
        $stmtCt = $pdo->prepare("SELECT * FROM chi_tiet_don WHERE don_hang_id = ?");
        $stmtCt->execute([$don["id"]]);
        $dsDon[$i]["chi_tiet"] = $stmtCt->fetchAll();
    }
}

$soGio = dem_gio_hang();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu đơn hàng</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Tra cứu đơn hàng", "Nhập số điện thoại đã dùng khi đặt để xem trạng thái đơn."); ?>

    <nav class="nav">
        <a href="index.php">Menu</a>
        <a href="cart.php">Giỏ hàng (<?= $soGio ?>)</a>
        <a class="active" href="lookup.php">Tra cứu đơn</a>
        <a href="admin/login.php">Quản trị</a>
    </nav>

    <?php if ($ok) { ?>
        <div class="alert alert-ok">Cảm ơn quý khách đã đặt hàng! Vui lòng nhập số điện thoại bên dưới để theo dõi đơn.</div>
    <?php } ?>

    <div class="form-box">
        <h2>Nhập số điện thoại</h2>
        <form method="GET">
            <label>Số điện thoại</label>
            <input type="text" name="sdt" value="<?= htmlspecialchars($sdt) ?>" required>
            <div class="form-actions">
                <button type="submit">Tra cứu</button>
            </div>
        </form>
    </div>

    <?php if ($sdt !== "") { ?>
        <div class="toolbar" style="margin-top:22px">
            <h2>Kết quả cho <?= htmlspecialchars($sdt) ?></h2>
        </div>

        <?php if (count($dsDon) === 0) { ?>
            <div class="table-card">
                <p class="empty">Không tìm thấy đơn hàng nào với số điện thoại này.</p>
            </div>
        <?php } else { ?>
            <?php foreach ($dsDon as $don) { ?>
                <div class="table-card" style="margin-bottom:16px">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                            <tr>
                                <th colspan="4">
                                    Đơn #<?= (int)$don['id'] ?>
                                    · <?= htmlspecialchars($don['ngay_dat']) ?>
                                    · <span class="badge <?= class_trang_thai($don['trang_thai']) ?>">
                                        <?= htmlspecialchars($don['trang_thai']) ?>
                                    </span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="4">
                                    <?= htmlspecialchars($don['ten_khach']) ?>
                                    · <?= htmlspecialchars($don['dia_chi']) ?>
                                    <?php if ($don['ghi_chu'] !== "") { ?>
                                        · Ghi chú: <?= htmlspecialchars($don['ghi_chu']) ?>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php foreach ($don['chi_tiet'] as $ct) { ?>
                                <tr>
                                    <td class="product-name">
                                        <?= htmlspecialchars($ct['ten_mon']) ?>
                                        <div class="muted">
                                            Size <?= htmlspecialchars($ct['size'] ?? 'M') ?>
                                            <?php if (!empty($ct['topping'])) { ?>
                                                · <?= htmlspecialchars($ct['topping']) ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><?= (int)$ct['so_luong'] ?> ly</td>
                                    <td><?= dinh_dang_tien($ct['don_gia']) ?></td>
                                    <td class="price"><?= dinh_dang_tien($ct['thanh_tien']) ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td colspan="3"><strong>Tổng tiền</strong></td>
                                <td class="price"><?= dinh_dang_tien($don['tong_tien']) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>

    <div class="footer">Ngọt - Tra cứu đơn theo số điện thoại</div>
</div>
</body>
</html>
