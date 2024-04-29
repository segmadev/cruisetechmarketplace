<?php 
    if(isset($_GET['userid'])) {
        $user2 = htmlspecialchars($_GET['userid']);
        if($user2 != $userID){
                $ch->new_user_chat($userID, $user2, $chat_form);
        }
    }
