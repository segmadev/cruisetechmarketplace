<?php 
session_start();
define("PATH", '../');
    require_once "include/auth-ini.php";
    if(isset($_POST['verify_otp'])) {
        // session_start();
        require_once "include/session.php";
        require_once "include/auth-ini.php";
        echo $a->verify_otp($adminToken);
    }

    if(isset($_POST['signin'])) {
        echo $a->signin();
    }
