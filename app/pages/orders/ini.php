<?php 
    require_once "functions/account.php";
    $a = new Account;
    $orders = $d->getall("account", "sold_to = ?", [$userID], fetch:"moredetails");
?>