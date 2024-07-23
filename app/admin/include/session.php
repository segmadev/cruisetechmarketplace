<?php 
// error_reporting(0);
// ini_set('display_errors', 0);
// if($_SERVER[‘HTTPS’] != "on") {
// $redirect= "https://".$_SERVER[‘HTTP_HOST’].$_SERVER[‘REQUEST_URI’];
// header("Location:$redirect");
// }

$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    if(!isset($_SESSION['adminSession']) ){
        $_SESSION['urlgoto'] = $redirect;
        echo '<script>window.location.href = "login";</script>';
        exit(); 
    }
    
    if(isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['adminSession']);
        echo '<script>window.location.href = "login";</script>';
        exit(); 

    }
    
    if(isset($_SESSION['adminSession'])){
        $adminToken = $_SESSION['adminSession'];
    }else{
        session_destroy();
        echo '<script>window.location.href = "login";</script>';
        exit(); 
    }
?>