<?php
$name = "Nguyen Van A";
$score = 7.5;

$ketqua = "dat";

if ($score >= 8) {
    $xeploai = "gioi";
} else if ($score >= 6.5) {
    $xeploai = "kha";
} else if ($score >= 5) {
    $xeploai = "trung binh";
} else {
    $xeploai = "khong dat";
    $ketqua = "khong dat";
}

echo "hoc vien: " . $name . PHP_EOL;
echo "diem: " . $score . PHP_EOL;
echo "xep loai: " . $xeploai . PHP_EOL;
echo "ket qua: " . $ketqua . PHP_EOL;
?>