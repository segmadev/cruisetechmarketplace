    <?php
                if (count($rental_services) <= 0) {
                    echo $c->empty_page("No Services Available at the moment.");
                } else {
                    require_once "pages/rentals/services_list.php";
                }
                ?>