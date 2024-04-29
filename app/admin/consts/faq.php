<?php 
    $faq_data = [
        // "ID"=>["input_type"=>"hidden"],
        "title"=>["unique"=>""],
        "status"=>["type"=>"select", "is_required"=>false, "options"=>["Active"=>"Active","Deactive"=>"Deactive"]],
        "description"=>["type"=>"textarea", "id"=>"richtext", "global_class"=>"w-100"],
    ];
    
    $faq_data['input_data']["status"] = "Active";
    $d->create_table("faq", $faq_data);
?>