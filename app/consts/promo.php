<?php 

$assign_promo = [
    "promoID"=>[
        "title"=>"Select Promo",
        "type"=>"select",
        "options"=>$i->get_user_promo($userID),
        "global_class"=>"w-100",  "description"=>"<b class='text-danger'>NOTE:</b> The Purchase price of promo selected will be debited from your balance."
    ],
];