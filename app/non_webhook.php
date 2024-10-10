<?php 
    header("Content-Type: application/json; charset=UTF-8");
    require_once "consts/main.php";
    require_once "admin/include/database.php";
    require_once "functions/rentals.php";
    $r = new rentals;
    if(isset($_GET['broker']) && $_GET['broker'] == "daisysms") {
        echo $r->daisysmsWebhook();
    }else {
        echo $r->nonHandleCallBack();
    }