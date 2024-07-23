<?php
// define("ROOT", $_SERVER['DOCUMENT_ROOT']."/invest2/");
require_once "include/session.php";
require_once "include/side.php";
require_once "../consts/main.php";
require_once "../include/phpmailer/PHPMailerAutoload.php";
require_once "include/database.php";
$d = new database;
$admin = $d->getall("admins", "token = ?", [$adminToken]);
if(!is_array($admin)) {
    $d->message("Unable to identify admin", "error");
    exit();
}
$adminID = $admin['ID'];
require_once "../consts/general.php";
require_once "../consts/Regex.php";
require_once "../content/content.php";
require_once "../functions/notifications.php";
require_once "../functions/users.php";
require_once "functions/users.php";
$u = new users;
$c = new content;
$route = "";
$page = "dashboard";
$script = [];
$userID = "admin";
if (isset($_GET['p'])) {
    $page = htmlspecialchars($_GET['p']);
}

if (isset($_GET['action'])) {
    $route = htmlspecialchars($_GET['action']);
}