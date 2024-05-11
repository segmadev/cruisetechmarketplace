<?php
$categories = null;
$category = [];
if($action == "list") {
    $categories = $d->getall("category", fetch:"");
}

if($action == "edit" && $_GET['id']) {
    $id = htmlspecialchars($_GET['id']);
    $category = $d->getall("category", "ID = ?", [$id], fetch:"details");

}