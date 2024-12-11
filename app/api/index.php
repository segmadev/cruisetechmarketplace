<?php
// phpinfo();
// exit();
header("Content-Type: application/json; charset=UTF-8");
// Remove the "/cruise/" prefix from the URI
$iniPath = str_replace("/cruise/", "", $_SERVER['REQUEST_URI']);
var_dump($iniPath);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//     require_once "inis/ini.php";
//     require_once "router.php";