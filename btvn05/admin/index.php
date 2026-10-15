<?php
include "auth.php";

$sql = "SELECT * FROM mon ORDER BY id ASC";
$result = $pdo->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý món</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Quản lý món", "Xin chào " . ($_SESSION["admin_ten"] ?? "Chủ tiệm") . " — thêm, sửa, xóa món trên menu.", true); ?>

    <nav class="nav">
        <a class="active" href="index.php">Quản lý món</a>
        <a href="orders.php">Đơn hàng</a>
        <a href="stats.php">Thống kê</a>
        <a href="../index.php">Xem trang khách</a>
        <a href="logout.php">Đăng xuất</a>
    </nav>

    <div class="toolbar">
        <h2>Danh sách món</h2>
        <a href="create.php" class="btn add">+ Thêm món</a>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên món</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->rowCount() > 0) { ?>
                    <?php while ($row = $result->fetch()) { ?>
                        <tr>
                            <td class="id"><?= htmlspecialchars($row['id']) ?></td>
                            <td>
                                <?php if (!empty($row['hinh_anh'])) { ?>
                                    <img class="thumb"
                                         src="../hinhanh/<?= htmlspecialchars($row['hinh_anh']) ?>"
                                         alt="">
                                <?php } ?>
                            </td>
                            <td class="product-name">
                                <?= htmlspecialchars($row['ten_mon']) ?>
                                <div class="muted"><?= htmlspecialchars($row['mo_ta']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($row['danh_muc']) ?></td>
                            <td class="price"><?= dinh_dang_tien($row['gia']) ?></td>
                            <td>
                                <?php if ((int)$row['trang_thai'] === 1) { ?>
                                    <span class="badge st-xong">Đang bán</span>
                                <?php } else { ?>
                                    <span class="badge st-huy">Ẩn</span>
                                <?php } ?>
                            </td>
                            <td class="actions">
                                <a class="btn edit" href="edit.php?id=<?= urlencode($row['id']) ?>">Sửa</a>
                                <form action="delete.php" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa món này?')">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                                    <button type="submit" class="btn delete">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="empty">Chưa có món nào. Hãy thêm món đầu tiên!</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">Ngọt - Quản trị</div>
</div>
</body>
</html>
