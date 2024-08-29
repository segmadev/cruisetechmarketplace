<?php 
    if(count($rental_services) == 0 || $rental_services == "") {
        echo $c->empty_page("No Services Available at the moment.");
    }else{
        $script[] = "sweetalert";
        // $important = [];
        $likeds = $r->getLikes($userID);
        $important_loop = $likeds;
        $used = [];
            echo '<div class="card-body p-3 row">';
            foreach ($important_loop as $key => $value) {
                if(array_search($value, array_column($rental_services, 'product_id'))){
                    $keyhold = array_search($value, array_column($rental_services, 'product_id'));
                    $service = $rental_services[$keyhold];
                    $used[$keyhold] = true;
                    // if(isset($rental_services[$keyhold])) unset($rental_services[$keyhold]);
                    $key = $service->product_id;
                    require "pages/rentals/service.php"; 
                    //  var_dump("Key: $value", );
                }
            }

        foreach ($rental_services as $key => $service) {
            if(!isset($used[$key])) {
                $key = $service->product_id;
                require "pages/rentals/service.php";
            }   
        }
        echo '</div>';
    }