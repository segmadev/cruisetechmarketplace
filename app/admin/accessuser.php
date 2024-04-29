<?php
session_start();
require_once "inis/ini.php";
if (!$d->validate_admin()) {
    exit();
}
if (!isset($_GET['id'])) {
    exit();
}
$id = htmlspecialchars($_GET['id']);
// set cookies and session for user so adim can access account.
unset($_SESSION['adminSession']);
session_destroy();
session_start();
setcookie("userSession", $id, time() + 60 * 60 * 24 * 30, "/", "", true, true);
$_SESSION['userSession'] = $id;
// $_SESSION['anonymous'] = "admin";
$d->loadpage("../");
