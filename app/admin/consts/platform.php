<?php 

$platform_form = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    "name"=>["unique"=>"", "placeholder"=>"Instagram, Facebook, X, Tiktok"],
    "icon"=>["input_type"=>"file", "is_required"=>true,  "path"=>"../assets/images/icons/"],
    "login_url"=>[ "placeholder"=>"https://instagram.com/login/", "description"=>"Enter url to the login page e.g https://instagram.com/login/"],
];
$platform_form['input_data'] = $platform;
$d->create_table("platform", $platform_form);

?>