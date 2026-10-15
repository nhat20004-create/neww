<?php
include __DIR__ . "/../connection.php";

if (!empty($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit;
}

$loi = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM `admin` WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    $matKhauDung = false;
    if ($admin) {
        $luu = (string)$admin["password"];
        $matKhauDung = hash_equals($luu, $password) || password_verify($password, $luu);
    }

    if ($matKhauDung) {
        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_ten"] = $admin["ho_ten"];
        header("Location: index.php");
        exit;
    }

    $loi = "Sai tên đăng nhập hoặc mật khẩu.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <?php in_banner("Quản trị tiệm Ngọt", "Chỉ chủ tiệm mới được đăng nhập để quản lý món và đơn hàng.", true); ?>

    <div class="form-box">
        <h2>Đăng nhập</h2>
        <?php if ($loi !== "") { ?>
            <div class="alert alert-err"><?= htmlspecialchars($loi) ?></div>
        <?php } ?>

        <form method="POST">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" required>

            <label>Mật khẩu</label>
            <input type="password" name="password" required>

            <div class="form-actions">
                <a class="back" href="../index.php">Về trang khách</a>
                <button type="submit">Đăng nhập</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
