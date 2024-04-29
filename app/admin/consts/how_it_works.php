<?php
$how_it_works = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    "image"=>["input_type"=>"file", "is_required"=>false, 
    "file_name"=>uniqid(), 
    "path"=>"../assets/images/banners/"],
    "title"=>["unique"=>""],
    "description"=>["type"=>"textarea"],
    "link"=>["is_required"=>false],
];
if(isset($details)) {
    $how_it_works['input_data'] = $details;
    if(isset($how_it_works['image']['file_name']) && isset($how_it_works['input_data']['image']))  {
        $how_it_works['image']['file_name'] = $how_it_works['input_data']['image'];
    }
}
// $d->create_table("how_it_works", $how_it_works);
?>