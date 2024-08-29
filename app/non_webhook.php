<?php 
    header("Content-Type: application/json; charset=UTF-8");
    require_once "consts/main.php";
    require_once "admin/include/database.php";
    require_once "functions/rentals.php";
    $r = new rentals;
    echo $r->nonHandleCallBack();

    