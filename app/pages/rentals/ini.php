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
    if($number_type == "short_term" && $network == 1) {
        $broker = "daisysms";
        $rental_services = $r->getServices(fromCookie: true);
    }
    if($number_type == "short_term" && $network == 2) {
        $broker = "nonvoipusnumber";
        $rental_services = $r->getServices($broker, "short_term", fromCookie: true);
        // var_dump($rental_services);
    }
    if($number_type == "short_term" && $network == 3 && $countryCode != "") {
        $broker = "anosim";
        $rental_services = (array)$r->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
        $countries = (array)$r->anosmsCountries();
    }
    // if($number_type == "short_term" && $network == 4 && $countryCode != "") {
    //     $broker = "sms_activation";
    //     $rental_services = $r->getServices($broker, "short_term", countryID: $countryCode);
    //     $countries = $r->smsActivationCountries();
    // }

    if($number_type == "short_term" && $network == 5 && $countryCode != "") {
        $broker = "sms_activate_two";
        $rental_services = $r->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
        $countries = $r->getCountry("smsActicateTwoCountries");
    }
    if($number_type == "short_term" && $network == 6 && $countryCode != "") {
        $broker = "sms_bower";
        $rental_services = $r->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
        // var_dump($rental_services);
        $countries = $r->getCountry(broker: "smsBowerCountries");
        // if(isset($countries[0])) $countries = $countries[0];
        // var_dump($countries);
        // exit();
    }
    if($number_type == "long_term") {
        $broker = "nonvoipusnumber";
        $rental_services = ($network != 1) ? $r->nonGetservices("long_term", network: $network) : $r->getServices($broker, "long_term", fromCookie: true) ;
    }
    if($number_type == "3days") {
        $broker = "nonvoipusnumber";
        $rental_services = $r->getServices($broker, "3days", fromCookie: true);
    }
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
    