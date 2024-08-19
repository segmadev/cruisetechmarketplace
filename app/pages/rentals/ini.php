<?php 
    $script[] = "modal";
    $script[] = "fetcher";
    $rental_services = $r->getServices();
    $no_rented = $d->getall("orders", "userID = ? and accountID != ? and order_type = ?", [$userID, "", "rentals"], fetch: "");
    $countDuration = (int)$d->get_settings("rental_number_expire_time");