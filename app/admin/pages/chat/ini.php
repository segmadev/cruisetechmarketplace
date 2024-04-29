<?php 
$from_generate = [
    "startDate"=>["input_type"=>"datetime-local"],
    "endDate"=>["input_type"=>"datetime-local"],
    "groupID"=>["type"=>"select"],
    "ignore_repetition"=>["type"=>"select", "options"=>["no"=>"No", "yes"=>"Yes"]],
    // "message_file"=>["input_type"=>"file", "path"=>"upload/temp/", "file_name"=>"message", "formart"=>["json"], "global_class"=>"w-100 h-10"],
    "message"=>["type"=>"textarea", "placeholder"=>"Paste the JSON code here.", "global_class"=>"w-100 h-10", "class"=>"bg-dark text-light"],
];

$from_generate['groupID']['options'] = $d->options_list($d->getall("groups", fetch: "moredetails"));
require_once "../functions/chat.php";
$ch = new chat;
require_once "../pages/chat/ini.php";
require_once "../consts/chat.php";
// exit();
?>