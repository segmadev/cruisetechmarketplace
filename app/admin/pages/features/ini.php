<?php 
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $feature = $d->getall("features", "ID = ?", [$id]);
    }

    if($action == "list") {
        $featuresList = $d->getall("features", fetch:  "moredetails");
    }
    require_once "pages/content/ini.php";
