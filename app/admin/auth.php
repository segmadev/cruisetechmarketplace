<?php 
define("PATH", '../');
    require_once "include/auth-ini.php";
  

    if(isset($_POST['signin'])) {
        echo $a->signin();
    }
