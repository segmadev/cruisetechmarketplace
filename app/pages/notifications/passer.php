<?php
    if(isset($_POST['get_pending_chat_notifications'])) {
       echo $n->display_chat_notification($n->get_pending_all_notification($userID));
    }

    if(isset($_POST['get_recent_notification'])) {
        require_once "pages/notifications/list.php";
    }
    if(isset($_POST['get_no_notification'])) {
        $no = $n->get_pending_all_notification($userID, type: "sn.seen_time")->rowCount();
        $return = [
            "function" => ["changetext", "data" => ["note-no", $no]],
        ];
        echo  json_encode($return);
    }

    if(isset($_POST['get_no_messages'])) {
        $no = $n->get_pending_all_notification($userID, for: "message", type: "sn.seen_time")->rowCount();
        $return = [
            "function" => ["changetext", "data" => ["message-no", $no]],
        ];
        echo  json_encode($return);
    }

    if(isset($_POST['get_recent_notification'])) {
        require_once "pages/notifications/list.php";
    }
    if(isset($_POST['get_notifications'])) {
        $what = htmlspecialchars($_POST['get_notifications']);
        switch ($what) {
            case 'get_pending_daily_my_report_notifications':
                echo $n->pop_message($n->get_type_notifications($userID, type: "my_report", exclude: $userID, update: $userID), $userID);
                break;
            case 'get_pending_daily_global_report_notifications':
                echo $n->pop_message($n->get_type_notifications(exclude: $userID, update: $userID), $userID);
                break;
            default:
                # code...
                break;
        }
    }
?>