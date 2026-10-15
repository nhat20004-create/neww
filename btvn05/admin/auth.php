<?php
include __DIR__ . "/../connection.php";

if (empty($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
