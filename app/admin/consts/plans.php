<?php 
require_once "../consts/countries.php";
$invertal = ["daily"=>"Daily", "weekly"=>"Weekly", "monthly"=>"Monthly"];
$plans_form = [
    "ID"=>["input_type"=>"hidden"],
    "min_amount"=>["input_type"=>"number"],
    "max_amount"=>["input_type"=>"number", "is_required"=>false, "description"=>"Set value to 0 for any amount."],
    "return_range_from"=>["input_type"=>"number", "placeholder"=>"3%", "description"=>"What do you want the return on investment range from"],
    "return_range_to"=>["input_type"=>"number", "placeholder"=>"10%"],
    "retrun_interval"=>["placeholder"=>"Select return interval","options"=>$invertal, "type"=>"select", "description"=>"How offen should the retrun on investment be."],
    "first_withdraw_in"=>["placeholder"=>"Note: In weeks", "input_type"=>"number", "description"=>"Invstor will not be able to withdraw until what value you input <b class='text-primary'>(In weeks)</b>"],
    "withdraw_interval"=>["input_type"=>"number", "placeholder"=>"Note: In weeks", "description"=>"How offen can investor withdraw after first withdraw? Note: Value will be calc In <b class='text-primary'>(In weeks)</b>"],
    "close_after"=>["placeholder"=>"Note: In months","input_type"=>"number", "description"=>"Investor can close this investment after value you set <b class='text-primary'>(In months)</b>",],
    "status"=>["placeholder"=>"Select Status","atb"=>"data-flag=\"ad\"", "options"=>["active"=>"Active", "disable"=>"Disable"],"type"=>"select"],
    "plan_name"=>["is_required"=>false, "placeholder"=>"Optional"],
    // "compound_profits"=>["type"=>"select", "options"=>[], "id"=>"compound_profits", "is_required"=>false, "description"=>"<a href=''>Add New compound_profits</a>"],
    "plan_instructions"=>["type"=>"textarea", "id"=>"richtext", "global_class"=>"col-md-12"],
    // "countries"=>$countries_data,
    "input_data"=>$data,
];


$promo_form = [
    "rate"=>["type"=>"select", "options"=>["2"=>"2X", "3"=>"3X", "4"=>"4X", "5"=>"5X", "6"=>"6X", "7"=>"7X", "8"=>"8X", "9"=>"9X", "10"=>"10X"]],
    "purchase_price"=>["input_type"=>"number"],
];
$d->create_table("promo", $promo_form);