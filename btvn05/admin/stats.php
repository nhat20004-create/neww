<?php
include "auth.php";

$kieu = $_GET["kieu"] ?? "thang";
if ($kieu !== "tuan") {
    $kieu = "thang";
}

$tongDon = (int)$pdo->query("SELECT COUNT(*) FROM don_hang")->fetchColumn();
$doanhThu = (float)$pdo->query(
    "SELECT COALESCE(SUM(tong_tien),0) FROM don_hang WHERE trang_thai <> 'Đã hủy'"
)->fetchColumn();
$choXuLy = (int)$pdo->query(
    "SELECT COUNT(*) FROM don_hang WHERE trang_thai IN ('Chờ xác nhận','Đang làm','Đang giao')"
)->fetchColumn();

if ($kieu === "tuan") {
    $sql = "SELECT YEARWEEK(ngay_dat, 1) AS nhom,
                   MIN(DATE(ngay_dat)) AS ngay_bat_dau,
                   MAX(DATE(ngay_dat)) AS ngay_ket_thuc,
                   COUNT(*) AS so_don,
                   SUM(CASE WHEN trang_thai <> 'Đã hủy' THEN tong_tien ELSE 0 END) AS doanh_thu,
                   SUM(CASE WHEN trang_thai = 'Hoàn thành' THEN 1 ELSE 0 END) AS don_hoan_thanh
            FROM don_hang
            GROUP BY YEARWEEK(ngay_dat, 1)
            ORDER BY nhom DESC
            LIMIT 12";
    $tieuDe = "Thống kê theo tuần (12 tuần gần nhất)";
} else {
    $sql = "SELECT DATE_FORMAT(ngay_dat, '%Y-%m') AS nhom,
                   DATE_FORMAT(ngay_dat, '%m/%Y') AS nhan,
                   COUNT(*) AS so_don,
                   SUM(CASE WHEN trang_thai <> 'Đã hủy' THEN tong_tien ELSE 0 END) AS doanh_thu,
                   SUM(CASE WHEN trang_thai = 'Hoàn thành' THEN 1 ELSE 0 END) AS don_hoan_thanh
            FROM don_hang
            GROUP BY DATE_FORMAT(ngay_dat, '%Y-%m')
            ORDER BY nhom DESC
            LIMIT 12";
    $tieuDe = "Thống kê theo tháng (12 tháng gần nhất)";
}

$bang = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê đơn hàng</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Thống kê đơn hàng", "Xem số đơn và doanh thu theo tháng hoặc theo tuần.", true); ?>

    <nav class="nav">
        <a href="index.php">Quản lý món</a>
        <a href="orders.php">Đơn hàng</a>
        <a class="active" href="stats.php">Thống kê</a>
        <a href="../index.php">Xem trang khách</a>
        <a href="logout.php">Đăng xuất</a>
    </nav>

    <div class="stat-grid">
        <div class="stat-box">
            <div class="muted">Tổng đơn</div>
            <div class="num"><?= $tongDon ?></div>
        </div>
        <div class="stat-box">
            <div class="muted">Đơn đang xử lý</div>
            <div class="num"><?= $choXuLy ?></div>
        </div>
        <div class="stat-box">
            <div class="muted">Doanh thu (chưa tính đơn hủy)</div>
            <div class="num"><?= dinh_dang_tien($doanhThu) ?></div>
        </div>
    </div>

    <div class="toolbar">
        <h2><?= htmlspecialchars($tieuDe) ?></h2>
        <div>
            <a class="btn <?= $kieu === 'thang' ? 'view' : 'gray' ?>" href="stats.php?kieu=thang">Theo tháng</a>
            <a class="btn <?= $kieu === 'tuan' ? 'view' : 'gray' ?>" href="stats.php?kieu=tuan">Theo tuần</a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Số đơn</th>
                        <th>Đơn hoàn thành</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($bang) === 0) { ?>
                    <tr>
                        <td colspan="4" class="empty">Chưa có dữ liệu thống kê.</td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($bang as $row) { ?>
                        <tr>
                            <td class="product-name">
                                <?php if ($kieu === "tuan") { ?>
                                    <?= htmlspecialchars($row["ngay_bat_dau"]) ?>
                                    → <?= htmlspecialchars($row["ngay_ket_thuc"]) ?>
                                <?php } else { ?>
                                    Tháng <?= htmlspecialchars($row["nhan"]) ?>
                                <?php } ?>
                            </td>
                            <td><?= (int)$row["so_don"] ?></td>
                            <td><?= (int)$row["don_hoan_thanh"] ?></td>
                            <td class="price"><?= dinh_dang_tien($row["doanh_thu"]) ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">Ngọt - Thống kê</div>
</div>
</body>
</html>
