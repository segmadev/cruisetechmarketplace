<?php 
    $script[] = "modal";
    $script[] = "fetcher";
    $rental_services = $r->getServices();
    if(isset($_GET['orderID']) && isset($_GET['accountID']) && isset($_GET['close']) && !empty($_GET['orderID'])) {
        $r->closeRental(htmlspecialchars($_GET['orderID']));
        echo $d->loadpage("index?p=rentals&action=view&accountID=".htmlspecialchars($_GET['accountID']));
        exit();
    }
    $no_rented = $d->getall("orders", "userID = ? and accountID != ? and order_type = ?", [$userID, "", "rentals"], fetch: "");
    $countDuration = (int)$d->get_settings("rental_number_expire_time");