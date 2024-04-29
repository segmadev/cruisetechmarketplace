<?php
    $compound_profits_form = [
        "compound_profits"=>["type"=>"select", "options"=>$i->get_user_roll_over($userID),
        "global_class"=>"w-100",  "description"=>"<b class='text-danger'>NOTE:</b> The Purchase price of Compound profits selected will be debited from your balance."
    ],
    "investmentID"=>["input_type"=>"hidden"],
    "userID"=>["input_type"=>"hidden"],
    ];
