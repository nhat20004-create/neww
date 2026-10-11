<?php
include "connection.php";

$sql = "SELECT * FROM sanpham ORDER BY id ASC";
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cửa hàng kính mắt</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            background: linear-gradient(135deg, #0f172a, #1d4ed8);
            color: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        }

        .header h1 {
            margin: 0 0 10px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            color: #dbeafe;
            line-height: 1.6;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }

        .toolbar h2 {
            margin: 0;
            font-size: 21px;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: none;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        .add {
            background: #059669;
        }

        .edit {
            background: #2563eb;
        }

        .delete {
            background: #dc2626;
        }

        .table-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th {
            background: #eaf1ff;
            color: #1e3a8a;
            padding: 16px 14px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 15px 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .id {
            color: #64748b;
            font-weight: bold;
        }

        .product-name {
            font-weight: bold;
            color: #0f172a;
        }

        .price {
            color: #047857;
            font-weight: bold;
            white-space: nowrap;
        }

        .quantity {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: bold;
        }

        .actions {
            white-space: nowrap;
        }

        .actions form {
            display: inline;
        }

        .empty {
            text-align: center;
            padding: 35px;
            color: #64748b;
        }

        .footer {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-top: 22px;
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px auto;
                padding: 0 12px;
            }

            .header {
                padding: 22px;
            }

            .header h1 {
                font-size: 23px;
            }

            .toolbar {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <header class="header">
        <h1>👓 CỬA HÀNG KÍNH MẮT</h1>
        <p>Quản lý sản phẩm kính cận, kính râm và kính thời trang.</p>
    </header>

    <div class="toolbar">
        <h2>Danh sách sản phẩm</h2>
        <a href="create.php" class="btn add">+ Thêm sản phẩm</a>
    </div>

    <div class="table-card">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá bán</th>
                        <th>Số lượng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result->rowCount() > 0) { ?>
                    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td class="id">
                                <?= htmlspecialchars($row['id']) ?>
                            </td>

                            <td class="product-name">
                                <?= htmlspecialchars($row['ten_san_pham']) ?>
                            </td>

                            <td class="price">
                                <?= number_format((float)$row['gia'], 0, ',', '.') ?> VNĐ
                            </td>

                            <td>
                                <span class="quantity">
                                    <?= htmlspecialchars($row['so_luong']) ?>
                                </span>
                            </td>

                            <td class="actions">
                                <a class="btn edit"
                                   href="edit.php?id=<?= urlencode($row['id']) ?>">
                                    Sửa
                                </a>

                                <form action="delete.php" method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                    <input type="hidden" name="id"
                                           value="<?= htmlspecialchars($row['id']) ?>">

                                    <button type="submit" class="btn delete">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="empty">
                            Chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên!
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Cửa hàng kính mắt - Hệ thống quản lý sản phẩm
    </div>

</div>

</body>
</html>
