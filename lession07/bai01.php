<?php
$sanpham = "banh mi";
$dongia = 15000;
$soluong = 3;

$tongtien = $dongia * $soluong;

$vat = $tongtien * 0.1; // 10% VAT

$tongtien = $tongtien + $vat;

echo "Tên sản phẩm: " . $sanpham . PHP_EOL;
echo "Đơn giá: " . $dongia . PHP_EOL;
echo "Số lượng: " . $soluong . PHP_EOL;
echo "Tổng tiền: " . $tongtien . PHP_EOL;
?>