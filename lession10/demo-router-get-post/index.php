<?php
// DEMO slide 13-14: router theo parameter + đọc $_REQUEST.   Thử:
//   index.php?show=product&page=1   index.php?page=2&show=news   index.php?show=person   index.php
$show = $_REQUEST['show'] ?? null;

?>
<h2>Router theo parameter</h2>
<p>Thử: <a href="?show=product&page=1">product</a> |
   <a href="?show=news&page=2">news</a> |
   <a href="?page=2&show=news">news (đảo thứ tự)</a> |
   <a href="?show=person">person (không có)</a> |
   <a href="index.php">không tham số</a> |
   <a href="form.php">Demo GET vs POST →</a></p>
<hr>
<?php
if ($show === null) {
    echo "Trang chủ (không có tham số show)";
} else {
    switch ($show) {
        case 'product':
            require __DIR__ . '/controller/product.php';
            break;
        case 'news':
            require __DIR__ . '/controller/news.php';
            break;
        default:
            http_response_code(404);
            echo "We do not have the thing you require";
    }
}
?>
<hr>
<h3>var_dump($_REQUEST) – slide 13</h3>
<pre><?php var_dump($_REQUEST); ?></pre>
