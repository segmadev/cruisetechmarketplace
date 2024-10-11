<?php 
$test_from = [
    "upload_image"=>["input_type"=>"file", "path"=>"upload/", "file_name"=>uniqid(), "formart"=>["pdf", "doc", "png"]]
];
if(isset($_POST['upload_image'])) {
    $d->validate_form($test_from);
}