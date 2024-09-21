<?php 
if(count($rental_services) == 0 || $rental_services == "") {
    echo $c->empty_page("No Services Available at the moment.");
}else{
    $used = [];
    $services_list = [];
    $likeds = $r->getLikes($userID);
    foreach ($rental_services as $key => $service) {
        $service = (array)$service;
        if(!isset($used[$key])) {
            $key = ($network == 6) ? $key : $service["id"] ?? $key;
            if(!isset($service['id']) && !isset($service['ID'])) $service['id'] = $key;
            if($network == 6) {
                if($key != "wa" && $key != "tg") $service['cost'] = $r->getLowestPrice((array)$service);
                if($key == "wa" || $key == "tg") {
                    $service['cost'] = (array)$service;
                    unset($service['cost']['id']);
                }
                if($service['cost'] == 0) continue;
                $service['maxPrice'] = $service['cost'];
                $service['name'] = $r->getKeyValue($key, 'countrie/services.json');
                if($service['name'] == null || $service['name'] == "") continue;
            }

            if($network == 5 && !isset($service['name'])) {
                $service['name'] = $r->smsActivateTwoGetServices($countryCode, $key);
            }

            if(in_array($key, $likeds)) {
                array_unshift($services_list, $service);
            }else{
                array_push($services_list, $service);
            }
        }   
    }
    echo '<div class="card-body p-3 row">';
    foreach ($services_list as $service) {
            $key =  $service["id"] ?? $service['ID'];
            require "pages/rentals/service.php";
        }
    echo "</div>";
}
?>