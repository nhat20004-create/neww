<?php
$products = [
    [
        "name" => "Bánh mì",
        "price" => 15000,
        "quantity" => 2
    ],
    [
        "name" => "Sữa",
        "price" => 30000,
        "quantity" => 1
    ],
    [
        "name" => "Trứng",
        "price" => 25000,
        "quantity" => 3
    ]
];

$tongTien = 0;

echo "===== DANH SÁCH SẢN PHẨM =====" . PHP_EOL . PHP_EOL;

foreach ($products as $product) {
    $thanhTien = $product["price"] * $product["quantity"];
    $tongTien += $thanhTien;

    echo $product["name"] . " - "
        . number_format($product["price"]) . " VNĐ x "
        . $product["quantity"] . " = "
        . number_format($thanhTien) . " VNĐ" . PHP_EOL;
}

echo PHP_EOL;

$giamGia = 0;

if ($tongTien >= 100000) {
    $giamGia = $tongTien * 0.1;
}

$thanhToan = $tongTien - $giamGia;

echo "Tạm tính: " . number_format($tongTien) . " VNĐ" . PHP_EOL;
echo "Giảm giá: " . number_format($giamGia) . " VNĐ" . PHP_EOL;
echo "Thanh toán: " . number_format($thanhToan) . " VNĐ" . PHP_EOL;
?>