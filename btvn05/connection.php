<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dsn = "mysql:host=localhost;dbname=tiemtrasua;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage());
}

dam_bao_cau_truc($pdo);

function dinh_dang_tien($so)
{
    return number_format((float)$so, 0, ',', '.') . ' VNĐ';
}

function bang_size()
{
    return [
        "S" => 0,
        "M" => 5000,
        "L" => 10000
    ];
}

function bang_topping()
{
    return [
        "Trân châu đen" => 5000,
        "Trân châu trắng" => 5000,
        "Thạch dừa" => 6000,
        "Pudding trứng" => 8000,
        "Kem cheese" => 10000
    ];
}

function loc_topping($ds)
{
    if (!is_array($ds)) {
        return [];
    }

    $hopLe = [];
    foreach ($ds as $ten) {
        if (isset(bang_topping()[$ten])) {
            $hopLe[] = $ten;
        }
    }
    $hopLe = array_values(array_unique($hopLe));
    sort($hopLe);
    return $hopLe;
}

function gia_mot_ly($giaGoc, $size, $toppings)
{
    $gia = (float)$giaGoc;
    $dsSize = bang_size();
    $gia += $dsSize[$size] ?? $dsSize["M"];

    foreach (loc_topping($toppings) as $ten) {
        $gia += bang_topping()[$ten];
    }

    return $gia;
}

function khoa_gio($id, $size, $toppings)
{
    return $id . "|" . $size . "|" . implode(",", loc_topping($toppings));
}

function dem_gio_hang()
{
    if (empty($_SESSION["gio_hang"]) || !is_array($_SESSION["gio_hang"])) {
        return 0;
    }

    $tong = 0;
    foreach ($_SESSION["gio_hang"] as $item) {
        if (is_array($item) && isset($item["so_luong"])) {
            $tong += (int)$item["so_luong"];
        } else {
            $tong += (int)$item;
        }
    }
    return $tong;
}

function in_banner($tieuDe, $moTa, $laAdmin = false)
{
    $logo = $laAdmin ? "../hinhanh/logo.png" : "hinhanh/logo.png";
    ?>
    <header class="header">
        <div class="brand">
            <img class="logo" src="<?= htmlspecialchars($logo) ?>" alt="Ngọt - Trà sữa & đồ uống">
            <div>
                <h1><?= htmlspecialchars($tieuDe) ?></h1>
                <p><?= htmlspecialchars($moTa) ?></p>
            </div>
        </div>
    </header>
    <?php
}

function ds_file_anh()
{
    $thuMuc = __DIR__ . "/hinhanh";
    $ds = [];
    if (!is_dir($thuMuc)) {
        return $ds;
    }

    foreach (scandir($thuMuc) as $ten) {
        if ($ten === "logo.png") {
            continue;
        }
        if (preg_match("/\.(png|jpe?g|webp)$/i", $ten)) {
            $ds[] = $ten;
        }
    }
    return $ds;
}

function cot_bang($pdo, $bang)
{
    $ds = [];
    foreach ($pdo->query("SHOW COLUMNS FROM `$bang`") as $cot) {
        $ds[] = $cot["Field"];
    }
    return $ds;
}

function dam_bao_cau_truc(PDO $pdo)
{
    $cotMon = cot_bang($pdo, "mon");
    if (!in_array("hinh_anh", $cotMon, true)) {
        $pdo->exec("ALTER TABLE mon ADD hinh_anh VARCHAR(255) NOT NULL DEFAULT ''");
    }

    $cotCt = cot_bang($pdo, "chi_tiet_don");
    if (!in_array("size", $cotCt, true)) {
        $pdo->exec("ALTER TABLE chi_tiet_don ADD size VARCHAR(10) NOT NULL DEFAULT 'M'");
    }
    if (!in_array("topping", $cotCt, true)) {
        $pdo->exec("ALTER TABLE chi_tiet_don ADD topping VARCHAR(255) NOT NULL DEFAULT ''");
    }

    $anhTheoTen = [
        "Trà sữa trân châu đường đen" => "trasuachanchauduongden.png",
        "Trà sữa matcha" => "matcha.png",
        "Trà sữa khoai môn" => "trasuakhoaimon.png",
        "Trà đào cam sả" => "tradaocamsa.png",
        "Trà vải lài" => "travailai.png",
        "Hồng trà sữa kem cheese" => "hongtrakemcheese.png",
        "Sữa tươi trân châu đường đen" => "suatuoichanchauduongden.png",
        "Trà sữa oolong" => "trasuaolong.png",
        "Trà chanh mật ong" => "trachanhmatong.png",
        "Trà dâu tây" => "tradautay.png",
        "Trà xoài nhiệt đới" => "traxoainhietdoi.png"
    ];

    $capNhatAnh = $pdo->prepare("UPDATE mon SET hinh_anh = ? WHERE ten_mon = ? AND (hinh_anh = '' OR hinh_anh IS NULL)");
    foreach ($anhTheoTen as $ten => $anh) {
        $capNhatAnh->execute([$anh, $ten]);
    }

    $monMoi = [
        ["Trà chanh mật ong", 32000, "Chanh tươi, mật ong, mát lạnh", "Trà trái cây", "trachanhmatong.png"],
        ["Trà dâu tây", 38000, "Dâu tươi, trà thanh, vị chua ngọt", "Trà trái cây", "tradautay.png"],
        ["Trà xoài nhiệt đới", 39000, "Xoài chín, trà xanh, hương nhiệt đới", "Trà trái cây", "traxoainhietdoi.png"]
    ];

    $tim = $pdo->prepare("SELECT id FROM mon WHERE ten_mon = ?");
    $them = $pdo->prepare(
        "INSERT INTO mon (ten_mon, gia, mo_ta, danh_muc, trang_thai, hinh_anh)
         VALUES (?, ?, ?, ?, 1, ?)"
    );

    foreach ($monMoi as $mon) {
        $tim->execute([$mon[0]]);
        if (!$tim->fetch()) {
            $them->execute($mon);
        }
    }
}
