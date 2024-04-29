<?php
session_start();
if (isset($_COOKIE['userSession']) && $_COOKIE['userSession'] != "") {
    $userID = $_COOKIE['userSession'];
} else {
    // exit
    return false;
}
require_once "include/side.php";
require_once "consts/main.php";
require_once "admin/include/database.php";
require_once "functions/notifications.php";
require_once "functions/users.php";
require_once "functions/chat.php";
$d = new database;
$u = new user;
$ch = new chat;
$imgpath =  "assets/images/chat/";
require_once "pages/chat/passer.php";
