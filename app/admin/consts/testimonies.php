<?php 
    $testimonies = [
        "ID"=>["input_type"=>"hidden", "is_required"=>false],
        "profile_image"=>["input_type"=>"file", "path"=>"../assets/images/profile/"],
        "name"=>["unique"=>""],
        "position"=>["placeholder"=>"Trader"],
        "testimony"=>["type"=>"textarea"],
    ];
    // $d->create_table("testimonies", $testimonies);

    if(isset($details)) {
        $testimonies['input_data'] = $details;
        if(isset($testimonies['profile_image']['file_name']) && isset($testimonies['input_data']['profile_image']))  {
            $testimonies['profile_image']['file_name'] = $testimonies['input_data']['profile_image'];
        }
    }
?>