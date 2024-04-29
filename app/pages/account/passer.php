<?php 
    if(isset($_POST['buy']) && isset($_POST['ID']) && $_POST['ID'] != ""){
        $accountID = htmlspecialchars($_POST['ID']);
        echo $a->buy_account($userID, $accountID);
    }
?>