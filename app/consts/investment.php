<?php 
$currency = currency;
    $investment_form = [
        "ID"=>["input_type"=>"hidden"],
        "planID"=>["input_type"=>"hidden"],
        "userID"=>["input_type"=>"hidden",],
        "amount"=>["input_type"=>"number", "description"=>"What is the amount you want to invest in this plan? ($currency)", "placeholder"=>"000"],
        
    ];
    $topup_form = [
        "ID"=>["input_type"=>"hidden"],
        "amount"=>["input_type"=>"number", "description"=>"What is the amount you want to topup? ($currency)", "placeholder"=>"000"],
    ];
    // $d->create_table("investment", $investment_form);
