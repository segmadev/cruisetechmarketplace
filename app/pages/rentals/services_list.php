<div class="table-responsive">
    <table class="table search-table align-middle text-nowrap">
        <thead class="header-item">
            <th>Service</th>
            <th>Price</th>
            <th></th>
        </thead>
        <tbody>
            <?php 
            $script[] = "sweetalert";
            $important = ["wa", "tn", "ig", "am", "tw", "gf", "go", "googlemessenger", "lf", "fu", "ts", "mm"];
            for ($i=0; $i < count($important) ; $i++) { 
                if(isset($rental_services[$important[$i]])){
                    $service = $rental_services[$important[$i]];
                    unset($rental_services[$important[$i]]);
                    $key = $important[$i];
                    require "pages/rentals/service.php";
                }
                
            }
            foreach($rental_services as $key => $service) {
               require "pages/rentals/service.php";
            }?>
          

        </tbody>
    </table>
</div>