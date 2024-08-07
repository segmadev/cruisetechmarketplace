<?php
if(isset($_GET['userID'])) {
    $orders = $d->getall("orders", "userID = ? order by date DESC", [htmlspecialchars($_GET['userID'])], fetch:"all" );
}

if(isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $order = $d->getall("orders", "ID = ?", [$id]);
    $account = $d->getall("account", "ID = ? order by date DESC", [$order['accountID']]);
    $logins = explode(',', $order['loginIDs']);
}

if(!isset($orders) && !isset($order)) {
    $orders = $d->getall("orders", "ID != ? order by date DESC", [""], fetch:"all");
}

require_once "functions/account.php";
$a = new accounts;
?>