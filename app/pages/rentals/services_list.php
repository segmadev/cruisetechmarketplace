<div class="card-body p-3 row">
            <?php 
            $script[] = "sweetalert";
            $likeds = $r->getLikes($userID);
            $important = ["wa", "tn", "ig", "am", "tw", "gf", "go", "googlemessenger", "lf", "fu", "ts", "mm"];
            $likedsFiltered = array_diff($important, $likeds);
            $important = array_merge($likeds, $likedsFiltered);
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
          
</div>

<script>
     document.querySelectorAll('.heart-button').forEach(button => {
            button.addEventListener('click', () => {
                const heartPath = button.querySelector('.heart-path');
                // Toggle fill color
                if (heartPath.getAttribute('fill') === '#fa5a15') {
                    heartPath.setAttribute('fill', 'none');
                } else {
                    heartPath.setAttribute('fill', '#fa5a15');
                }
            });
        });
</script>