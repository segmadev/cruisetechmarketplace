<?php 
    $promo_form = [
        "number_of_days"=>["global_class"=>"w-100","input_type"=>"number", "description"=>"What is the number of day(s) the promo should last for?."],
        "rate"=>["global_class"=>"w-100", "input_type"=>"number", "description"=>"What is the rate in which you want to increase profit <b class='text-primary'>e.g(2)X</b>"],
        "purchase_price"=>["global_class"=>"w-100", "unique"=>"rate", "input_type"=>"number"],
        "assigned_users"=>["is_required"=>false, "atb"=>"multiple='multiple'", "class"=>"select2", "global_class"=>"w-100", "type"=>"select"],
    ];

    $assign_promo = [
        "ID"=>["input_type"=>"number"],
        "promoID"=>[],
        "userID"=>[],
        "start_date"=>[],
        "end_date"=>[],
        "status"=>[],
    ];
    $d->create_table("promo_assigned", $assign_promo);