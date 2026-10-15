<?php
include __DIR__ . "/../connection.php";

unset($_SESSION["admin_id"], $_SESSION["admin_ten"]);
header("Location: login.php");
exit;
