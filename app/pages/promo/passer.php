<?php 
    if(isset($_POST['new_promo'])) {
       echo $i->activate_promo($assign_promo, $userID);
    }