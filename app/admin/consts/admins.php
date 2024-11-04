<?php 

$admin_from = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    "first_name"=>[],
    "last_name"=>[],
    "phone_number"=>["input_type"=>"number"],
    "email"=>["input_type"=>"email", "unique"=>""],
    "password"=>["input_type"=>"password"],
    "status"=>["type"=>"select", "options"=>[1=>"Active",0=>"Deactive"]],
    "roleID"=>["title"=>"Assign a role","type"=>"select", "options"=>$d->options_list("roles"), "description"=>"<a href='index?p=roles&action=new'>Click here</a> to Create New role"],
    "input_data"=>$adminDetails
];

if(is_array($adminDetails) && count($adminDetails) > 0) {
   $admin_from['password']['is_required'] = false;
   $admin_from['input_data']['password'] = "";
//    $admin_from['confirm_password']['is_required'] = false;
}
