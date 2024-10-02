<?php 
// phpinfo();
// exit();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
      require_once "inis/ini.php";
      $token = null;
      $headers = apache_request_headers();
      require_once ROOT."/router.php";
    //   var_dump($headers);
    //   if(isset($headers['Authorization'])){
    //     $matches = array();
    //     preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);
    //     if(isset($matches[1])){
    //       $token = $matches[1];
    //     }
    //   }
?>