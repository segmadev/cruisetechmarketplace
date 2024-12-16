<?php
$categories = null;
$category = [];
if ($action == "list") {
    $categories = $d->getall("category", fetch: "");
}

if ($action == "edit" && $_GET['id']) {
    $id = htmlspecialchars($_GET['id']);
    $category = $d->getall("category", "ID = ?", [$id], fetch: "details");
}

if ($action == "view") {
    $filterDate = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d', strtotime('-1 day'));
    $oflineReport = $ca->fetchAccountsAndLogins($filterDate);
}