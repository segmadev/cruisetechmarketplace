<?php 
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $details = $d->getall("how_it_works", "ID = ?", [$id]);
    }

    if($action == "list") {
        $how_it_workss = $d->getall("how_it_works", fetch:  "moredetails");
    }
    require_once "pages/content/ini.php";
