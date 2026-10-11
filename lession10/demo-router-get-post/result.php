<?php
$method = $_SERVER['REQUEST_METHOD'];
?>
<h2>Kết quả: request này dùng <?= $method ?></h2>
<p>URL trên thanh địa chỉ: <code><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></code></p>
<table border="1" cellpadding="6">
  <tr><th></th><th>$_GET</th><th>$_POST</th><th>$_REQUEST</th></tr>
  <tr><td>Nội dung</td>
      <td><pre><?= htmlspecialchars(print_r($_GET, true)) ?></pre></td>
      <td><pre><?= htmlspecialchars(print_r($_POST, true)) ?></pre></td>
      <td><pre><?= htmlspecialchars(print_r($_REQUEST, true)) ?></pre></td></tr>
</table>
<ul>
  <li>GET: dữ liệu nằm trên URL → <b>$_GET</b> có dữ liệu, $_POST rỗng. Mật khẩu lộ trên thanh địa chỉ.</li>
  <li>POST: URL sạch, dữ liệu nằm trong body → <b>$_POST</b> có dữ liệu, $_GET rỗng. Xem ở DevTools &gt; Network &gt; Payload.</li>
  <li>$_REQUEST gom cả hai nên cả hai trường hợp đều thấy dữ liệu.</li>
</ul>
<p><a href="form.php">← Thử lại</a></p>
