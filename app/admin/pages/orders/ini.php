<?php
if (isset($_GET['userID'])) {
    $orders = $d->getall("orders", "userID = ? order by date DESC limit 100", [htmlspecialchars($_GET['userID'])], fetch: "all");
}
// var_dump($_GET);
if (isset($_GET['id']) || isset($_GET['ID'])) {
    $id = htmlspecialchars($_GET['id'] ?? $_GET['ID']);
    $order = $d->getall("orders", "ID = ?", [$id]);
    $account = $d->getall("account", "ID = ? order by date DESC limit 100", [$order['accountID']]);
    $logins = explode(',', $order['loginIDs']);
}

if (!isset($orders) && !isset($order)) {
    $orders = $d->getall("orders", "ID != ? order by date DESC limit 100", [""], fetch: "all");
}

require_once "functions/account.php";
$a = new accounts;