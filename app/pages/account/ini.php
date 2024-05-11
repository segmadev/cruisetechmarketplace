<?php 
// $script[] = "productlist";
$orders = [
"ID"=>[],
"userID"=>[],
"accountID"=>[],
"loginIDs"=>[],
"amount"=>[],
"no_of_orders"=>[],
];
$d->create_table("orders", $orders);
      require_once "functions/account.php";
      $a = new Account;
    if(isset($_GET['id']) && $action == "details") {
       $account = $a->get_account(htmlspecialchars($_GET['id']));
    }
    if(isset($_GET['id']) && $action == "view") {
       $account = $a->get_account(htmlspecialchars($_GET['id']), 3);
       if($account != "" && $account['sold_to'] != $user['ID'])  $account = "";
    }
?>