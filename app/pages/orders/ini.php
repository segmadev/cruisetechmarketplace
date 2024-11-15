<?php 
    require_once "functions/account.php";
    $a = new Account;
    if(!isset($_GET['id'])) {   
        if(isset($_GET['type'])) {
            $orders = $d->getall("orders", "userID = ? and order_type = ? order by date DESC limit 700", [$userID, htmlspecialchars($_GET['type'])], fetch:"moredetails");
        }else{
            $orders = $d->getall("orders", "userID = ? order by date DESC limit 700", [$userID], fetch:"moredetails");
        }
    }

    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $order = $d->getall("orders", "ID = ? and userID = ? order by date DESC limit 700", [$id, $userID]);
        $account = $d->getall("account", "ID = ?", [$order['accountID']]);
        $logins = explode(',', $order['loginIDs']);
    }
?>