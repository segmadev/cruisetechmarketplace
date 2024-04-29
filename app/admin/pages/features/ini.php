<?php 
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $feature = $d->getall("key_features", "ID = ?", [$id]);
    }

    if($action == "list") {
        $features = $d->getall("key_features", fetch:  "moredetails");
    }
    require_once "pages/content/ini.php";
