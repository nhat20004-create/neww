<?php
// Biến lưu kết quả và lỗi để hiển thị bên dưới
$ketQua = null;
$loi = "";

// Giữ lại giá trị đã nhập để hiển thị lại trong form
$soA = "";
$soB = "";
$phepTinh = "+";

// Chỉ xử lý khi người dùng bấm nút gửi form (phương thức POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    var_dump(json_encode($_POST));
    // $_POST["name"] : lấy dữ liệu từ input có name tương ứng
    $soA = trim($_POST["so_a"] ?? "");
    $soB = trim($_POST["so_b"] ?? "");
    $phepTinh = $_POST["phep_tinh"] ?? "+";

    // Kiểm tra dữ liệu (validate)
    if ($soA === "" || $soB === "") {
        $loi = "Vui lòng nhập đầy đủ 2 số!";
    } elseif (!is_numeric($soA) || !is_numeric($soB)) {
        $loi = "Dữ liệu nhập vào phải là số!";
    } elseif ($phepTinh === "/" && (float)$soB == 0) {
        $loi = "Không thể chia cho 0!";
    } else {
        $a = (float)$soA;   // ép kiểu chuỗi -> số
        $b = (float)$soB;

        switch ($phepTinh) {
            case "+": $ketQua = $a + $b; break;
            case "-": $ketQua = $a - $b; break;
            case "*": $ketQua = $a * $b; break;
            case "/": $ketQua = $a / $b; break;
            default:  $loi = "Phép tính không hợp lệ!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>PHP 02 - Xử lý form</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 420px; margin: 40px auto; padding: 0 16px; }
        form { border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        label { display: block; margin-top: 10px; }
        input, select, button { width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 16px; background: #8892bf; color: #fff; border: none; cursor: pointer; }
        .loi { color: #e74c3c; }
        .ket-qua { color: #27ae60; font-size: 20px; }
    </style>
</head>
<body>
    <h1>Máy tính đơn giản</h1>

    <!-- method="post": gửi dữ liệu ẩn, action="": gửi về chính trang này -->
    <form method="post" action="">
        <label>Số thứ nhất:
            <input type="text" name="so_a1" value="<?= htmlspecialchars($soA) ?>">
        </label>

        <label>Phép tính:
            <select name="phep_tinh">
                <?php foreach (["+" => "Cộng (+)", "-" => "Trừ (-)", "*" => "Nhân (×)", "/" => "Chia (÷)"] as $kyHieu => $ten): ?>
                    <option value="<?= $kyHieu ?>" <?= $phepTinh === $kyHieu ? "selected" : "" ?>><?= $ten ?></option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Số thứ hai:
            <input type="text" name="so_b" value="<?= htmlspecialchars($soB) ?>">
        </label>

        <button type="submit">Tính</button>
    </form>

    <?php if ($loi !== ""): ?>
        <p class="loi"><?= $loi ?></p>
    <?php elseif ($ketQua !== null): ?>
        <!-- htmlspecialchars(): chống chèn mã HTML/JS độc hại khi in dữ liệu người dùng nhập -->
        <p class="ket-qua">
            <?= htmlspecialchars("$soA $phepTinh $soB") ?> = <b><?= round($ketQua, 4) ?></b>
        </p>
    <?php endif; ?>
</body>
</html>
