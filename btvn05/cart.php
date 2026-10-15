<?php
include "connection.php";

if (!isset($_SESSION["gio_hang"]) || !is_array($_SESSION["gio_hang"])) {
    $_SESSION["gio_hang"] = [];
}

$mauCu = false;
foreach ($_SESSION["gio_hang"] as $item) {
    if (!is_array($item)) {
        $mauCu = true;
        break;
    }
}
if ($mauCu) {
    $_SESSION["gio_hang"] = [];
}

$thongBao = "";
$loi = "";

if (($_GET["action"] ?? "") === "remove") {
    $key = $_GET["key"] ?? "";
    if (isset($_SESSION["gio_hang"][$key])) {
        unset($_SESSION["gio_hang"][$key]);
    }
    header("Location: cart.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "add") {
        $id = $_POST["id"] ?? "";
        $soLuong = (int)($_POST["so_luong"] ?? 1);
        if ($soLuong < 1) {
            $soLuong = 1;
        }

        $size = $_POST["size"] ?? "M";
        if (!isset(bang_size()[$size])) {
            $size = "M";
        }
        $topping = loc_topping($_POST["topping"] ?? []);

        if (ctype_digit((string)$id)) {
            $stmt = $pdo->prepare("SELECT id FROM mon WHERE id = ? AND trang_thai = 1");
            $stmt->execute([$id]);
            if ($stmt->fetch()) {
                $key = khoa_gio($id, $size, $topping);
                if (!isset($_SESSION["gio_hang"][$key])) {
                    $_SESSION["gio_hang"][$key] = [
                        "id" => (int)$id,
                        "size" => $size,
                        "topping" => $topping,
                        "so_luong" => 0
                    ];
                }
                $_SESSION["gio_hang"][$key]["so_luong"] += $soLuong;
                $thongBao = "Đã thêm món vào giỏ hàng.";
            }
        }
    }

    if ($action === "update") {
        $soLuongs = $_POST["so_luong"] ?? [];
        if (is_array($soLuongs)) {
            foreach ($soLuongs as $key => $sl) {
                if (!isset($_SESSION["gio_hang"][$key])) {
                    continue;
                }
                $sl = (int)$sl;
                if ($sl < 1) {
                    unset($_SESSION["gio_hang"][$key]);
                } else {
                    $_SESSION["gio_hang"][$key]["so_luong"] = $sl;
                }
            }
        }
        $thongBao = "Đã cập nhật giỏ hàng.";
    }

    if ($action === "remove") {
        $key = $_POST["key"] ?? "";
        if (isset($_SESSION["gio_hang"][$key])) {
            unset($_SESSION["gio_hang"][$key]);
        }
        $thongBao = "Đã xóa món khỏi giỏ.";
    }

    if ($action === "checkout") {
        if (empty($_SESSION["gio_hang"])) {
            $loi = "Giỏ hàng đang trống.";
        } else {
            $ten = trim($_POST["ten_khach"] ?? "");
            $sdt = trim($_POST["so_dien_thoai"] ?? "");
            $diaChi = trim($_POST["dia_chi"] ?? "");
            $ghiChu = trim($_POST["ghi_chu"] ?? "");

            if ($ten === "" || $sdt === "" || $diaChi === "") {
                $loi = "Vui lòng nhập họ tên, số điện thoại và địa chỉ.";
            } else {
                $ids = [];
                foreach ($_SESSION["gio_hang"] as $item) {
                    $ids[] = (int)$item["id"];
                }
                $ids = array_values(array_unique($ids));
                $placeholders = implode(",", array_fill(0, count($ids), "?"));
                $stmt = $pdo->prepare("SELECT * FROM mon WHERE trang_thai = 1 AND id IN ($placeholders)");
                $stmt->execute($ids);
                $mons = [];
                foreach ($stmt->fetchAll() as $mon) {
                    $mons[$mon["id"]] = $mon;
                }

                $chiTiet = [];
                $tong = 0;
                foreach ($_SESSION["gio_hang"] as $item) {
                    if (!isset($mons[$item["id"]])) {
                        continue;
                    }
                    $mon = $mons[$item["id"]];
                    $donGia = gia_mot_ly($mon["gia"], $item["size"], $item["topping"]);
                    $thanhTien = $donGia * (int)$item["so_luong"];
                    $tong += $thanhTien;
                    $chiTiet[] = [
                        "ten_mon" => $mon["ten_mon"],
                        "don_gia" => $donGia,
                        "so_luong" => (int)$item["so_luong"],
                        "thanh_tien" => $thanhTien,
                        "size" => $item["size"],
                        "topping" => implode(", ", $item["topping"])
                    ];
                }

                if (count($chiTiet) === 0) {
                    $loi = "Các món trong giỏ không còn bán.";
                    $_SESSION["gio_hang"] = [];
                } else {
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare(
                        "INSERT INTO don_hang (ten_khach, so_dien_thoai, dia_chi, ghi_chu, tong_tien)
                         VALUES (?, ?, ?, ?, ?)"
                    );
                    $stmt->execute([$ten, $sdt, $diaChi, $ghiChu, $tong]);
                    $donId = (int)$pdo->lastInsertId();

                    $stmtCt = $pdo->prepare(
                        "INSERT INTO chi_tiet_don (don_hang_id, ten_mon, don_gia, so_luong, thanh_tien, size, topping)
                         VALUES (?, ?, ?, ?, ?, ?, ?)"
                    );
                    foreach ($chiTiet as $ct) {
                        $stmtCt->execute([
                            $donId,
                            $ct["ten_mon"],
                            $ct["don_gia"],
                            $ct["so_luong"],
                            $ct["thanh_tien"],
                            $ct["size"],
                            $ct["topping"]
                        ]);
                    }
                    $pdo->commit();

                    $_SESSION["gio_hang"] = [];
                    $_SESSION["don_vua_dat"] = $donId;
                    header("Location: lookup.php?sdt=" . urlencode($sdt) . "&ok=1");
                    exit;
                }
            }
        }
    }
}

$gioHang = [];
$tongTien = 0;

if (!empty($_SESSION["gio_hang"])) {
    $ids = [];
    foreach ($_SESSION["gio_hang"] as $item) {
        $ids[] = (int)$item["id"];
    }
    $ids = array_values(array_unique($ids));
    $placeholders = implode(",", array_fill(0, count($ids), "?"));
    $stmt = $pdo->prepare("SELECT * FROM mon WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $mons = [];
    foreach ($stmt->fetchAll() as $mon) {
        $mons[$mon["id"]] = $mon;
    }

    foreach ($_SESSION["gio_hang"] as $key => $item) {
        if (!isset($mons[$item["id"]])) {
            continue;
        }
        $mon = $mons[$item["id"]];
        $donGia = gia_mot_ly($mon["gia"], $item["size"], $item["topping"]);
        $thanhTien = $donGia * (int)$item["so_luong"];
        $tongTien += $thanhTien;
        $gioHang[] = [
            "key" => $key,
            "hinh_anh" => $mon["hinh_anh"],
            "ten_mon" => $mon["ten_mon"],
            "size" => $item["size"],
            "topping" => $item["topping"],
            "gia" => $donGia,
            "so_luong" => (int)$item["so_luong"],
            "thanh_tien" => $thanhTien
        ];
    }
}

$soGio = dem_gio_hang();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Ngọt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Giỏ hàng", "Kiểm tra size, topping rồi điền thông tin để đặt hàng."); ?>

    <nav class="nav">
        <a href="index.php">Menu</a>
        <a class="active" href="cart.php">Giỏ hàng (<?= $soGio ?>)</a>
        <a href="lookup.php">Tra cứu đơn</a>
        <a href="admin/login.php">Quản trị</a>
    </nav>

    <?php if ($thongBao !== "") { ?>
        <div class="alert alert-ok"><?= htmlspecialchars($thongBao) ?></div>
    <?php } ?>
    <?php if ($loi !== "") { ?>
        <div class="alert alert-err"><?= htmlspecialchars($loi) ?></div>
    <?php } ?>

    <?php if (count($gioHang) === 0) { ?>
        <div class="table-card">
            <p class="empty">Giỏ hàng trống. <a href="index.php">Xem menu</a></p>
        </div>
    <?php } else { ?>
        <div class="table-card">
            <div class="table-wrapper">
                <form method="POST">
                    <input type="hidden" name="action" value="update">
                    <table>
                        <thead>
                        <tr>
                            <th>Món</th>
                            <th>Size / Topping</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($gioHang as $item) { ?>
                            <tr>
                                <td class="product-name">
                                    <?php if (!empty($item["hinh_anh"])) { ?>
                                        <img class="thumb"
                                             src="hinhanh/<?= htmlspecialchars($item["hinh_anh"]) ?>"
                                             alt="">
                                    <?php } ?>
                                    <?= htmlspecialchars($item["ten_mon"]) ?>
                                </td>
                                <td>
                                    Size <?= htmlspecialchars($item["size"]) ?>
                                    <?php if (!empty($item["topping"])) { ?>
                                        <div class="muted">
                                            <?= htmlspecialchars(implode(", ", $item["topping"])) ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="muted">Không topping</div>
                                    <?php } ?>
                                </td>
                                <td class="price"><?= dinh_dang_tien($item["gia"]) ?></td>
                                <td>
                                    <input type="number"
                                           name="so_luong[<?= htmlspecialchars($item["key"]) ?>]"
                                           min="1" value="<?= (int)$item["so_luong"] ?>"
                                           style="width:80px">
                                </td>
                                <td class="price"><?= dinh_dang_tien($item["thanh_tien"]) ?></td>
                                <td class="col-x">
                                    <a class="btn-x"
                                       href="cart.php?action=remove&amp;key=<?= urlencode($item["key"]) ?>"
                                       title="Xóa món"
                                       onclick="return confirm('Xóa món này khỏi giỏ?')">×</a>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4"><strong>Tổng cộng</strong></td>
                            <td class="price"><?= dinh_dang_tien($tongTien) ?></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="toolbar" style="padding:12px 14px">
                        <button class="btn add" type="submit">Cập nhật số lượng</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="form-box form-wide" style="margin-top:22px">
            <h2>Thông tin đặt hàng</h2>
            <p class="subtitle">Không cần tài khoản. Dùng số điện thoại để tra cứu đơn sau này.</p>
            <form method="POST">
                <input type="hidden" name="action" value="checkout">

                <label>Họ tên</label>
                <input type="text" name="ten_khach" required>

                <label>Số điện thoại</label>
                <input type="text" name="so_dien_thoai" required>

                <label>Địa chỉ nhận</label>
                <input type="text" name="dia_chi" required>

                <label>Ghi chú (ít đá, ít đường...)</label>
                <textarea name="ghi_chu" rows="3"></textarea>

                <div class="form-actions">
                    <a class="back" href="index.php">Tiếp tục chọn món</a>
                    <button type="submit">Đặt hàng</button>
                </div>
            </form>
        </div>
    <?php } ?>

    <div class="footer">Ngọt - Trà sữa & đồ uống</div>
</div>
</body>
</html>
