<?php 
if(count($rental_services) == 0 || $rental_services == "") {
    echo $c->empty_page("No Services Available at the moment.");
}else{
    $used = [];
    $likeds = $r->getLikes($userID);
    echo '<div class="card-body p-3 row">';
    foreach ($rental_services as $key => $service) {
        $service = (array)$service;
        if(!isset($used[$key])) {
            $key = $service["id"];
            require "pages/rentals/service.php";
        }   
    }
    echo "</div>";
}
?>