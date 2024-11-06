<?php 
$category_form = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    "name"=>["unique"=>"", "placeholder"=>"Facebook Dating.."],
    "cat_type"=>["type"=>"select", "Title"=>"Category type", "options"=>["1"=>"Online", "0"=>"Offline"], "is_required"=>false],
];
$category_form['input_data'] = $category;
$d->create_table("category", $category_form);
?>