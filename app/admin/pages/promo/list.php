<?php
    if($promos->rowCount() == 0) {
        echo $c->empty_page("You have no promo created yet.", "<a href='index?p=promo&action=new' class='btn btn-primary'>Create Promo</a>");
    }else{
        require_once "pages/promo/table.php"; 
    }
?>