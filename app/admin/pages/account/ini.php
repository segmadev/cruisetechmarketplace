<?php 
require_once "../functions/users.php";
require_once "../functions/account.php";
require_once "functions/account.php";
$a = new accounts;
$platforms = $d->getall("platform", fetch:"moredetails");
$account = [];
if($action == "edit") {
    $id = htmlspecialchars($_GET['id'] ?? "");
    $account = $d->getall("account", "ID = ?", [$id]);
    $logins = $d->getall("logininfo", "accountID = ?", [$id], fetch: "all");
}