<?php 
    $script[] = "modal";
    $script[] = "fetcher";
    $number_type = htmlspecialchars($_GET['type'] ?? "short_term");
    $countryCode = htmlspecialchars($_GET['countryCode'] ?? 98);
    $countries  = null;
    $network = 1;
    // $r->smsActivateTwoGetServices(countryCode: "16", serviceCode: "wa");
    // $r->smsActivateTwoGetServices(countryCode: "16", serviceCode: "wa");

    if(isset($_GET['network'])) $network = htmlspecialchars((int)$_GET['network'] ?? 1);
    $rentalData = $r->get_services($number_type, $network, $countryCode);
    $rental_services = $rentalData['services'];
    $countries = $rentalData['countries'];
    $broker = $rentalData['broker'];
    if(isset($_GET['orderID']) && isset($_GET['accountID']) && isset($_GET['close']) && !empty($_GET['orderID'])) {
        $r->closeRental($userID, htmlspecialchars($_GET['orderID']));
        echo $d->loadpage("index?p=rentals&action=view&accountID=".htmlspecialchars($_GET['accountID']));
    }
    $no_rented = $d->getall("orders", "userID = ? and accountID != ? and order_type = ?", [$userID, "", "rentals"], fetch: "");
    $countDuration = (int)$d->get_settings("rental_number_expire_time");
    
    if(isset($_GET['activate']) && isset($_GET['orderID']) && $_GET['orderID'] != "") {
        // activate number 
        echo $r->nonActivateNumber($userID, htmlspecialchars($_GET['orderID']));
        echo $d->loadpage("index?p=rentals&action=view&accountID=".htmlspecialchars($_GET['accountID']));
        exit();
    }
    