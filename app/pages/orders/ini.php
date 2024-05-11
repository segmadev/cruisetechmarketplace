<?php 
    require_once "functions/account.php";
    $a = new Account;
    if(!isset($_GET['id'])) {   
        $orders = $d->getall("orders", "userID = ?", [$userID], fetch:"moredetails");
    }

    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $order = $d->getall("orders", "ID = ? and userID = ?", [$id, $userID]);
        $account = $d->getall("account", "ID = ?", [$order['accountID']]);
        $logins = explode(',', $order['loginIDs']);
    }
?>