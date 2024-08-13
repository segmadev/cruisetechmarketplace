<?php 
    $script[] = "modal";
    $rental_services = $r->getServices();
    $no_rented = $d->getall("orders", "userID = ? and accountID != ? and order_type = ?", [$userID, "", "rentals"], fetch: "");