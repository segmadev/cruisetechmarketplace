<?php
require_once "../functions/users.php";
require_once "functions/users.php";
require_once '../functions/wallets.php';
require_once '../functions/deposit.php';
$u = new users;
if (isset($_GET['id'])) {
    $details = $d->getall("deposit", "ID = ?", [htmlspecialchars($_GET['id'])]);
}

$de = new deposit;
