<?php
$imgpath =  "assets/images/chat/";
if($d->validate_admin()){
    $imgpath  =   "../assets/images/chat/";
}
$message_form = [
    "chatID" => ["input_type" => "hidden"],
    "senderID" => ["title"=>"","input_type" => "hidden", "global_class"=>"w-5"],
    "receiverID" => ["input_type" => "hidden"],
    "message" => ["input_type" => "text", "title" => "", "class" => "form-control message-type-box text-muted border-0 p-0 ms-2", "placeholder" => "Type Message"],
    "upload" => ["input_type" => "file", "file_name" => uniqid("M-"), "path" =>$imgpath, "is_required" => false],
    "fileID" => ["input_type" => "hidden",  "is_required" => false],
    "is_group" => ["input_type" => "hidden"],
    "reply_to" => ["input_type" => "hidden", "is_required"=>false],
];

$file_upload = [
    "ID"=>[],
    "userID"=>[],
    "current_location"=>[],
    "googleID"=>[],
    "file_name"=>[],
    "time_upload"=>[],
];



$group_form = [
    "ID" => ["input_type" => "number"],
    "name" => [],
    "description" => [],
    "profile_image" => [],
    "users" => [],
];

$d->create_table("files_upload", $file_upload);
$d->create_table("chat", $chat_form);
$d->create_table("message", $message_form);
$d->create_table("groups", $group_form);



if (isset($_GET['id'])) {
    if($d->validate_admin() && $chat['is_group'] !=  "yes") {
        $message_form['input_data']['chatID'] = $chatID;
        $message_form['senderID']['options'] = [$chat['user1']=>$u->get_name($chat['user1']), $chat['user2']=>$u->get_name($chat['user2'])];
        $message_form['senderID']['type'] = "select";
        unset($message_form['senderID']['input_type']);
        $replyuserID = $chat['user1'];
        $cmessages = $d->getall("message", "chatID = ? order by time_sent DESC", [$chatID]);
        if(is_array($cmessages) ) {
            if($chat['user1'] != "admin" && $chat['user2'] != "admin"){
                if($cmessages['senderID'] == $chat['user1']) $replyuserID = $chat['user2'];
            }
        }
        $message_form['input_data']['senderID'] = $replyuserID;
    }else{
        $message_form['input_data']['chatID'] = $chatID;
        $message_form['input_data']['senderID'] = $userID;
        // var_dump($chat);
        if ($chat['user1'] == $userID) {
            $message_form['input_data']['receiverID'] = $chat['user2'];
        } else {
            $message_form['input_data']['receiverID'] = $chat['user1'];
        }
    }
    
    $message_form['input_data']['is_group'] = $chat['is_group'];
}