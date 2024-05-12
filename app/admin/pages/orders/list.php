<?php 
    if($orders->rowCount() <= 0) {
        echo $c->empty_page("No order yet. You havn't make any orders yet", h1: "No order");
    }else{
       
        require_once "../pages/orders/table.php";
    }
?>