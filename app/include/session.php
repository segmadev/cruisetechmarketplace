<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// if($_SERVER[‘HTTPS’] != "on") {
 $redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// header("Location:$redirect");
// }
// if (!isset($_SESSION['userSession'])) {
//     $_SESSION['urlgoto'] = $redirect;
//     header("location: login?urlgoto=$redirect");
// }

    if(isset($_GET['logout']) && isset($_COOKIE['userSession'])) {
       logout();
       echo '<script>window.location.href = "login.php";</script>';
       exit();
    }
    
    if(isset($_COOKIE['userSession'])){
        $userID = $_COOKIE['userSession'];
        // exit();

    }else{
        // session_destroy();
        $_SESSION['urlgoto'] = $redirect;
        logout();
        echo '<script>window.location.href = "login.php?urlgoto='.$redirect.'";</script>';
        echo "<a href='login'>Click here to Login.</a>";
        exit();
    }

   function logout() {
    unset($_COOKIE['userSession']);
    setcookie('userSession', null, time() - 3600, '/');
   }
?>

