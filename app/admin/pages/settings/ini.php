<?php
if (isset($_GET['action'])) {
    $route = htmlspecialchars($_GET['action']);
}

if(isset($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);
    $data = $d->getall("settings", "ID = ?", [$id], fetch: "details");
}
require_once "functions/settings.php";
$s = new settings;
require_once "consts/settings.php";
// $s->new_settings($settings_form);
// $s->new_settings($settings_withdraw_form);
// $s->new_settings($settings_depost_form);