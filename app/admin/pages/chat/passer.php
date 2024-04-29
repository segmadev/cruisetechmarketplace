<?php 
    require_once "../pages/chat/video-chat.php";
    // chat issets
    if(isset($_POST['send_message']) && !isset($_POST['video'])){
        $send = send_message($ch, $message_form);
        if($send) {
            $return = [
                "message" => ["Success", "Message Sent", "success"],
                "function"=>["onset_chat", "data"=>["message-input-box", ""]]
            ];
            echo json_encode($return);
        }
    }

    function send_message($ch, $message_form) {
        $d = new database;
        $chatID  = htmlspecialchars($_POST['chatID']);
        $chat =  $ch->get_chat($chatID, "admin");
        
        if(!isset($_POST['custom']) || !$d->validate_admin() || $_POST['receiverID'] == "") {
            if($_POST['senderID'] == $chat['user1']) {
                $_POST['receiverID'] = $chat['user2'];
            }else{
                $_POST['receiverID'] = $chat['user1'];
            }
        }
        return $ch->new_message($message_form);
    }

    // get users in groups
    if(isset($_POST['get_group_users'])) {
        $groupID = htmlspecialchars($_POST['get_group_users']);
        $start = htmlspecialchars($_POST['start']);
        $limit = htmlspecialchars($_POST['limit']);
        echo $ch->get_group_users($groupID, $start, $limit);
    }
    if(isset($_POST['loadmedia']) && !empty($_POST['loadmedia'])) {
        $chatID = htmlspecialchars($_POST['loadmedia']);
        
    }
    if(isset($_POST['update_last_seen'])) {
        $update_last_seen = $ch->update_last_seen($userID, time()); 
    }
    if(isset($_POST['get_user_chat_list'])) {
         $chats = $ch->get_chats($userID, htmlspecialchars($_POST['time'] ?? 0)); 
        echo $ch->list_chat_users($chats, $userID);
    }
    if(isset($_POST['get_last_seen'])) {
        echo $ch->get_last_seen(htmlspecialchars($_POST['get_last_seen']));
    }
    // get old messages
    echo $ch->get_old_messages($userID);
    // get recent messages
    echo $ch->get_new_messages($userID);
    // admin actions
    if(isset($_POST["delete_message"])) {
        echo $ch->delete_message(htmlspecialchars($_POST['delete_message']));
    }


    if(isset($_POST['edit_message'])) {
        $messageID = htmlspecialchars($_POST['edit_message']);
        echo $ch->edit_message($messageID);
    }

    if(isset($_POST['generate_conversation'])) {
        $data = $d->validate_form($from_generate);
        if(!is_array($data)) { return null; }
        // var_dump($data);
        // return null;
        $path = 'upload/temp/message.json';
        // if(!file_exists($path)) { 
            
        //   }
        
        file_put_contents($path, $_POST['message']);
        echo $ch->create_bot_conversation($data['groupID'], $data['startDate'], $data['endDate'], $data['ignore_repetition']);
    }
    ?>