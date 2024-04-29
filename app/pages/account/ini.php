<?php 
    if(isset($_GET['id']) && $action == "details") {
       $account = $a->get_account(htmlspecialchars($_GET['id']));
    }
    if(isset($_GET['id']) && $action == "view") {
       $account = $a->get_account(htmlspecialchars($_GET['id']), 3);
       if($account != "" && $account['sold_to'] != $user['ID'])  $account = "";
    }
?>