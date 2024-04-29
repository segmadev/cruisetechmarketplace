<?php 
    require_once "functions/plans.php";
    $p = new plans;
    if(isset($action) && $action == "list") {
        $promos = $d->getall("promo", fetch: "moredetails");
    }

    if(isset($_GET['id']) && !empty($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $promo = $d->getall("promo", "ID = ?", [$id]);
    }