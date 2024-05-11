<?php 
$category_form = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    "name"=>["unique"=>"", "placeholder"=>"Facebook Dating.."],
];
$category_form['input_data'] = $category;
$d->create_table("category", $category_form);
?>