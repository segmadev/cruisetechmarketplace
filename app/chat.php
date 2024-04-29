<?php
// database
// content
// notification
// user
require_once "include/session.php";
define("side", "user");
require_once "consts/main.php";
require_once "consts/Regex.php";
require_once "admin/include/database.php";
$d = new database;
// require_once "consts/general.php";
require_once "content/content.php";
require_once "functions/notifications.php";
require_once "functions/users.php";
require_once "functions/chat.php";
$u = new user;
$c = new content;
$n = new Notifications;
$ch = new chat;

if(isset($_POST['send_message'])){
    $send = $ch->new_message($message_form);
    if($send) {
        $return = [
            "function"=>["onset_chat", "data"=>["message-input-box", ""]]
        ];
        echo json_encode($return);
    }
}