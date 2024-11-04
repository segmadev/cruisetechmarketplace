<?php
$features = [
    "ID"=>["input_type"=>"hidden", "is_required"=>false],
    // "icon"=>["description"=>"Go to <a target='_BLANK' href='https://dashlite.net/demo6/components/misc/nioicon.html' class='text-primary'>Nioicon Font</a> copy the icon of your choice and paste it here.", "placeholder"=>"<em class=\"icon ni ni-info\"></em>"],
    "title"=>["unique"=>""],
    "description"=>["type"=>"textarea"],
    "link"=>["is_required"=>false],
];
if(isset($feature)) {
    $key_features['input_data'] = $feature;
}
?>