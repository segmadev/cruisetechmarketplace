<?php
$change_password_from = [
    "current_password" => ["input_type" => "password", "global_class" => "w-100"],
    "password" => ["input_type" => "password", "global_class" => "w-100"],
    "confirm_password" => ["input_type" => "password", "global_class" => "w-100"],
];

$profile_form = [
    "first_name" => [ "global_class" => "w-100"],
    "last_name" => [ "global_class" => "w-100"],
    "email" => ["input_type" => "email",  "global_class" => "w-100"],
    "phone_number" => ["input_type" => "number",  "global_class" => "w-100"],
    "gender" => ["placeholder" => "Select your gender", "is_required" => true, "options" => ["Male" => "Male", "Female" => "Female"], "type" => "select",  "global_class" => "w-100"],
    "input_data"=>$user,
];


$kyc_form = [
    "valid_ID"=>["input_type"=>"file", "path"=>"assets/images/kyc/", "file_name"=>$userID, "description"=>"Whatever information you submit will only be use for Verification."],
    "kyc_status"=>["is_required"=>false, "input_type"=>"hidden"],
    "input_data"=>$user
];