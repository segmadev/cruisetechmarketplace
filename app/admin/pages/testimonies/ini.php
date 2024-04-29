<?php 
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $details = $d->getall("testimonies", "ID = ?", [$id]);
    }

    if($action == "list") {
        $testimoniess = $d->getall("testimonies", fetch:  "moredetails");
    }
    require_once "pages/content/ini.php";
