<?php
$platforms = null;
$platform = [];
if($action == "list") {
    $platforms = $d->getall("platform", fetch:"");
}

if($action == "edit" && $_GET['id']) {
    $id = htmlspecialchars($_GET['id']);
    $platform = $d->getall("platform", "ID =?", [$id], fetch:"details");

}