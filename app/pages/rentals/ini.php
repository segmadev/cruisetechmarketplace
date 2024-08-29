<?php 
    $script[] = "modal";
    $script[] = "fetcher";
    $number_type = htmlspecialchars($_GET['type'] ?? "short_term");
    $network = 1;
    if(isset($_GET['network'])) $network = htmlspecialchars((int)$_GET['network'] ?? 1);
    if($number_type == "short_term" && $network == 1) {
        $broker = "daisysms";
        $rental_services = $r->getServices();
    }
    if($number_type == "short_term" && $network == 2) {
        $broker = "nonvoipusnumber";
        $rental_services = $r->nonGetservices("short_term");
    }
    if($number_type == "long_term") {
        $broker = "nonvoipusnumber";
        $rental_services = $r->nonGetservices("long_term", network: $network);
    }
    if($number_type == "3days") {
        $broker = "nonvoipusnumber";
        $rental_services = $r->nonGetservices("3days");
    }
    if(isset($_GET['orderID']) && isset($_GET['accountID']) && isset($_GET['close']) && !empty($_GET['orderID'])) {
        $r->closeRental($userID, htmlspecialchars($_GET['orderID']));
        echo $d->loadpage("index?p=rentals&action=view&accountID=".htmlspecialchars($_GET['accountID']));
        exit();
    }
    $no_rented = $d->getall("orders", "userID = ? and accountID != ? and order_type = ?", [$userID, "", "rentals"], fetch: "");
    $countDuration = (int)$d->get_settings("rental_number_expire_time");
    