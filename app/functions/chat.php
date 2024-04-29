<?php
class chat extends user
{
private $chat_holder = [];

    function bot_generate_message(array $messages, $groupID = 2,) {
        // get all messages
        $messages = [
           
        ];
        foreach ($messages as $key => $message) {
            $i = 0;
            if($this->getall("message", "message = ?", [$message],  fetch: "") > 0) { continue; }
            $user = $this->getall("users", "acct_type = ? and status =  ? ORDER BY RAND()", ["bot", "active"]);
            $date = $this->generateRandomDateTime();
            $data = ["chatID"=>"", "senderID" => $user['ID'], "receiverID" => $groupID, "message" => $message, "is_group" => "yes", "time_sent" => strtotime($date), "date" => $date];
            if($this->quick_insert("message", $data)) { $i++; }
            $this->message("$i messages genrated.", "success");
        }
    }



    function create_bot_conversation($groupID = 2, $startDate = '2022-01-01 09:00:00', $endDate = null, $ignore_repetition = "no")
    {
        $i = 0;
        $path = 'upload/temp/message.json';
        if(!file_exists($path)) { return $this->message("Error getting your file", "error");  }
        $jsonString = file_get_contents($path);
        $messages = json_decode($jsonString, true);
        // var_dump($messages);
        foreach ($messages as $message) {
            $user = $this->getall("users", "acct_type = ? and status =  ? ORDER BY RAND()", ["bot", "active"]);
            $date = $this->generateRandomDateTime($startDate, $endDate);
            $data = ["chatID" => "", "senderID" => $user['ID'], "receiverID" => $groupID, "message" => "", "is_group" => "yes", "time_sent" => strtotime($date), "reply_to" => "", "date" => $date];
            if (is_array($message) && isset($message['main_message']) && isset($message['response'])) {
                $data['message'] = $message['main_message'];
                // var_dump($this->getall("message", "receiverID = ? and message = ?",  [$groupID, $message['main_message']], fetch: "details"));
                if($ignore_repetition == "no") {
                    if($this->getall("message", "receiverID = ? and message = ?",  [$groupID, $message['main_message']], fetch: "") > 0) { continue; }
                }

                if (!$this->quick_insert("message", $data)) {
                    continue;
                }
                if (isset($message['response']) && !$this->reply_to_message($groupID, $data['message'], $message['response'])) {
                    continue;
                }
                if (isset($message['message']) && !$this->reply_to_message($groupID, $message['response'], $message['message'], $user['ID'])) {
                    continue;
                }
            }else if(isset($message['message']) || isset($message['main_message'])){
                if(isset($message['main_message'])) $message['message'] = $message['main_message'];
                $data['message'] = $message['message'];
                if($ignore_repetition == "no") {
                    if($this->getall("message", "receiverID = ? and message = ?",  [$groupID, $message['message']], fetch: "") > 0) { continue; }
                }
                if($data['message'] == "" || $data['time_sent'] <= 0) {
                    continue;
                }
                if (!$this->quick_insert("message", $data)) {
                    continue;
                }
            }
            $i++;
        }
        // dispose message file after use
        // unlink($path);
        file_put_contents($path, "");
        $this->message("$i of conversations created", "success");
    }

    function check_chat_message_exit(){

    }
    function rand_update_last_seen()
    {
        $no = rand(50, 400);
        if ($this->update("users", ["last_seen" => time()], "acct_type = 'bot' LIMIT $no")) {
            return true;
        }
    }


    function reply_to_message($groupID, $message, $response, $userID = "admin") {
        $data = $this->getall('message', "receiverID = ?  and message = ?", [$groupID, $message]);
        if(!is_array($data)) { return false; }
        $data['date'] = $this->addMinutes($data['date'], rand(1, 5));
        $replyID = $data["ID"];
        $data['message'] = $response;
        $data['reply_to'] = $replyID;
        $data["time_sent"] = strtotime($data['date']);
        $data['senderID'] = $userID;
        unset($data['ID']);
        if($data['time_sent'] <= 0) { return false; }
        if($this->quick_insert("message", $data)) { return true; }
        return false;
    }
    function delete_message($id){
        if (!$this->validate_admin()) {
            return false;
        }
        $message = $this->getall("message", "ID = ?", [$id]);
        if (!is_array($message)) {
            return false;
        }

        if($message['fileID'] != "") {
            $this->unlink_file($message["fileID"]);
        }
        $message = $message['message'];
        $this->delete("message", "ID = ?", [$id]);
        $this->delete("notifications", "n_for = ? and description = ?", ["message", $message]);
        // removediv(id, type="id")        
        
        $return = [
            "message" => ["Success", "Message Deleted. You might have to repload page to see update.", "success"],
            "function" => ["removediv", "data" => ["#chat-ID-".$id, "placeholder"]],
        ];
        return json_encode($return);
    }

   
    function new_chat($data, $action  = "insert")
    {
        $info = $this->validate_form($data, "chat", $action);
        if (is_array($info)) {
            return null;
        }
        return true;
    }

    function get_chat($chatID, $userID)
    {
        if (isset($_SESSION['adminSession']) && side == "admin") {
            return $this->getall("chat", "ID = ?", [$chatID]);
        } else {
            $chat =  $this->getall("chat", "ID = ?", [$chatID]);
            if($chat["user1"] == $userID || $chat['user2'] == $userID ||  $chat['user2'] == "all") {
                return $chat;
            }
            return [];
        }
    }
    function get_sender($chat, $userID)
    {
        if ($userID !=  "admin") {
            if ($chat['user1'] == $userID) {
                $message_form['input_data']['receiverID'] = $chat['user2'];
            } else {
                $message_form['input_data']['receiverID'] = $chat['user1'];
            }
        } else {
            if ($chat['is_group'] == "yes") {
            }
        }
    }
    function new_message($data, $action = "insert")
    {
        
        $data['time_sent'] = [];
        if($this->validate_admin() && isset($_POST['time_sent']) && $_POST['time_sent'] != ""){
            $_POST['time_sent'] = strtotime($_POST['time_sent']);
        }else{
            $_POST['time_sent'] = time();
        }
       
        // if(!isset($_POST['message']) || $_POST['message'] == null || $_POST['message'] == ""){
        //     return false;
        // }
        $info = $this->validate_form($data, "message", $action);
        // var_dump($info);
        if (!is_array($info)) {
            return false;
        }
        if ($info['is_group'] == "yes") {
            $this->group_chat_notification($info['senderID'], $info['message'],  time(),   $info['receiverID']);
        } else {
            $this->user_chat_notification($info, time());
        }
        $message = $this->getall("message", "time_sent = ? and senderID = ?", [$info['time_sent'], $info['senderID']]);
        return $this->display_message($message, $info['senderID']);
    }

    function read_all_message($userID, $chatID)
    {
        $chat = $this->getall("chat", 'ID = ?', [$chatID], "is_group");
        if (!is_array($chat)) {
            return 0;
        }
        if ($chat['is_group'] == "yes") {
            $chat = $this->getall("chat", "ID = ?", [$chatID], "user2");
            if (is_array($chat)) {
                $groupID = $chat['user2'];
                return $this->update("message",  ["seen_by" => $userID], " senderID != '$userID' and receiverID = '$groupID' and seen_by = 'null'");
                // return $this->getall("message", "senderID != ? and receiverID = ? and seen_by = ?", [$userID, $chat['user2'], 'null'], fetch: "");
            }
        }

        // $this->blulk_exclude_user($chatID, $userID);
        $update =  $this->update("message", ["seen_by" => $userID], "chatID = '$chatID' and receiverID = '$userID' and seen_by = 'null'");
        return $update;
    }

    function get_unseen_message($userID, $chatID)
    {
        // get all from messages where chatid =  $chatID join seen_messages  id is not
        $chat = $this->getall("chat", 'ID = ?', [$chatID], "is_group");
        if (!is_array($chat)) {
            return 0;
        }
        if ($chat['is_group'] == "yes") {
            $chat = $this->getall("chat", "ID = ?", [$chatID], "user2");
            if (is_array($chat)) {
                return $this->getall("message", "senderID != ? and receiverID = ? and seen_by = ?", [$userID, $chat['user2'], 'null'], fetch: "");
            }
        }
        return $this->getall("message", "chatID = ? and receiverID = ? and seen_by = ?", [$chatID, $userID, 'null'], fetch: "");
        // return $this->getall("message as m join (SELECT * FROM seen_messages as sm GROUP BY sm.ID) sm", 'm.chatID = ? and m.receiverID = ?',[$chatID, $userID], "m.*", fetch: "");
        // return $this->getall("message as m join seen_messages", 'seen_messages.messageID and m.chatID = ? and m.receiverID = ?',[$chatID, $userID], "m.*", fetch: "");

    }

    function list_chat_users($chats, $userID)
    {
        if ($chats->rowCount() > 0) {
            foreach ($chats as $row) {
                // var_dump($row);
                $active = "";
                if (isset($chatID) && $chatID == $row['ID']) {
                    $active = "bg-light";
                }
                echo $this->list_user($row, $userID, $active);
            }
        }
    }

    function get_group($userID, $groupID) {
        return $this->getall("chat", "user1 = ? and user2 = ?", [$userID, $groupID], fetch: "moredetails");
    }
    function get_chats($userID, $time)
    {
        // echo $userID;
        if (isset($_SESSION['adminSession']) &&  side == "admin") {
            // $chats = $this->getall("chat c RIGHT JOIN ( SELECT m.chatID, m.message, m.time_sent as time_sent, MAX(m.date) AS min_date FROM message m LEFT JOIN chat ON chat.ID = m.chatID WHERE time_sent >= $time and time_sent is not null GROUP BY m.chatID ) m ON c.ID = m.chatID ", "c.user1 = ? or c.user2 = ? or c.is_group = ? and m.time_sent >= ? and m.time_sent IS NOT NULL ORDER BY m.min_date DESC", ["admin","admin", "no", $time], select: "c.*, m.time_sent", fetch: "moredetails");
        $chats = $this->getall("chat c RIGHT JOIN (SELECT m.chatID,  m.senderID, m.receiverID, m.message, m.time_sent as time_sent, MAX(m.date) AS min_date FROM message m  LEFT JOIN chat ON chat.ID = m.chatID WHERE time_sent >= $time and time_sent is not null GROUP BY chat.ID) m ON c.ID = m.chatID or c.user2 = m.receiverID and c.user2 != m.senderID", "c.user1 = ? or c.user2 = ? or c.is_group = ? and m.time_sent >= ? and m.time_sent IS NOT NULL ORDER BY m.min_date DESC", ["admin", "admin", "no", $time], "c.*", fetch: "moredetails");
           
            return $chats;  
        }
        $time = "0";
        // echo $userID;
        // echo "yes";
        // $chats = $this->getall("chat c RIGHT JOIN ( SELECT m.chatID, message, m.time_sent as time_sent, MAX(m.date) AS min_date FROM message  m  WHERE time_sent >= $time GROUP BY m.chatID ) m ON c.ID = m.chatID", "c.user1 = ? or c.user2 = ? and m.time_sent >= ? and m.time_sent IS NOT NULL ORDER BY m.min_date DESC", [$userID, $userID, $time], "c.*,  m.time_sent", fetch: "moredetails");
        $chats = $this->getall("chat c RIGHT JOIN (SELECT m.chatID,  m.senderID, m.receiverID, m.message, m.time_sent as time_sent, MAX(m.date) AS min_date FROM message m  LEFT JOIN chat ON chat.ID = m.chatID WHERE time_sent >= $time and time_sent is not null GROUP BY chat.ID) m ON c.ID = m.chatID", "c.user1 = ? or c.user2 = ? and  m.time_sent >= ? and  m.time_sent < ? and m.time_sent IS NOT NULL ORDER BY m.time_sent DESC", [$userID, $userID, $time, time()], "c.*", fetch: "moredetails");
    //    var_dump($chats->rowCount());
    //    var_dump($time);
        // $chats = $this->getall("chat", "ID = ? ORDER BY date DESC LIMIT $start, $limit", [], fetch: 'moredetails');
        return $chats;
    }
    function get_admin_group($time) {
        $chats = $this->getall("chat c RIGHT JOIN ( SELECT m.chatID, m.message, m.time_sent as time_sent, MAX(m.date) AS min_date FROM message m WHERE time_sent >= $time and time_sent is not null GROUP BY m.chatID ) m ON c.ID = m.chatID", "c.user1 = ? or c.is_group = ? and m.time_sent >= ? and m.time_sent IS NOT NULL ORDER BY m.min_date DESC", ["admin", "no", $time], select: "c.*, m.time_sent", fetch: "moredetails");
    }

    // $2y$10$04dmPY/uRtiOJ2UIChW4/eGQA4cVYeQX5RPGnJqfQe/Xz.1E6ob7a
    function get_last_seen($userID)
    {
        $last_seen = $this->getall("users", "ID = ?", [$userID], "last_seen");
        if (!is_array($last_seen)) {
         if($this->getall("groups", "ID = ?", [$userID], fetch: "") > 0) {
            $this->rand_update_last_seen();    
            return $this->get_group_last_seen();
           }
           return "";
        }
        return $this->proccess_last_seen($last_seen['last_seen']);
    }

    function get_group_last_seen(){
        $time = time();
        $diff = time() - 10;

        // $no = $this->getall("users", "last_seen >= ? LIMIT ".rand(10000, 500000), [$diff], fetch: "");
        $no = rand(10, 20);
        $rand = rand(1, 3);
        $get_last_seen = (int)$this->get_settings("get_last_seen");
        if($rand <= 1 && $get_last_seen <= 1500) { $get_last_seen = $get_last_seen + $no; }else if($rand == 2 && $get_last_seen > 850){ $get_last_seen = $get_last_seen - $no; }
        if($get_last_seen < 600) {
            $get_last_seen = rand(800, 1500);
        }
        $this->update("settings", ["meta_value"=>$get_last_seen], "meta_name = 'get_last_seen'");
        return '<span class="p-1 badge rounded-pill bg-success"><span class="visually-hidden">'.$get_last_seen.'</span></span> '.$get_last_seen.' Users Online';
    }
    function proccess_last_seen($last_seen)
    {

        $now = time();
        $difference     = $now - (int)$last_seen;
        if ($difference < 10) {
            return '<span class="p-1 badge rounded-pill bg-success"><span class="visually-hidden">kdkd</span></span> Online';
        }


        return '<span class="p-1 badge rounded-pill bg-dark"><span class="visually-hidden">kdkd</span></span> Offline ' . $this->ago($last_seen);
    }

    function no_users_in_group($groupID)
    {
        return $this->getall("chat", "user2 = ? and is_group = ? and is_bot = ?", [$groupID, "yes", "yes"], fetch: "");
    }

    function get_new_messages($userID) {
        if(isset($_POST['get_chat']) && isset($_POST['lastchat'])) {
            $lastchat = htmlspecialchars($_POST['lastchat']);
            $chatID =  htmlspecialchars($_POST['chatID']);
            $limit = htmlspecialchars($_POST['get_chat']);
            $messages =  $this->get_all_messages($chatID, $userID, $lastchat, $limit, where: "time_sent > ?", orderby: 'time_sent ASC');
            if (isset($messages) && $messages->rowCount()  > 0) {
                $Dmessage = "";
                foreach ($messages as $row) {
                    $Dmessage .= $this->display_message($row, $userID);
                }
                return $Dmessage;
            }else{
                return 'null';
            }
        }
    }

    function get_old_messages($userID) {
        if(isset($_POST['get_chat']) && isset($_POST['firstchat'])) {
            $firstchat = htmlspecialchars($_POST['firstchat']);
            $chatID =  htmlspecialchars($_POST['chatID']);
            $limit = htmlspecialchars($_POST['get_chat']);
            $messages =  $this->get_all_messages($chatID, $userID, $firstchat, $limit, where: "time_sent < ?", orderby: 'time_sent DESC', type: "old");
            // $messages 
            if (isset($messages) && $messages->rowCount()  > 0) {
                $rmessages  = [];
                foreach ($messages as $row) {
                    $rmessages[] = $row;
                }
                $messages = array_reverse($rmessages);
                $Dmessage = "";
                    foreach ($messages as $row) {
                        $Dmessage .= $this->display_message($row, $userID);
                    }
                    return $Dmessage;
                }else {
                    return "null";
                }
            
        }
    }
    function get_all_messages($chatID, $userID,  $start = 0, $limit = 10, $chat = "", $orderby = "date ASC", $where = "time_sent > ?", $type = "new")
    {
        $more = "and time_sent <= ?";
        $moreValue = time();
        if($this->validate_admin() || $type == "old") {
            $more = "";
            $moreValue = "";
        }
        if($this->validate_admin()) {
            $chat = $this->get_chat($chatID, $userID);
        }

        if ($chat == "") {
            $chat =  $this->getall("chat", "ID = ? and user1 = ? or user2 = ? or user2 = ?", [$chatID, $userID, $userID, "all"]);
        }


        if ($chat['is_group'] == 'yes') {
            if($start == "first") {
                $start = $this->getall("message", "receiverID = ? $more and message IS NOT NULL", $this->flitter_array([$chat['user2'], $moreValue]), fetch: "") - $limit;
                if($start < 0) {$start = 0;}
                $messages = $this->getall("message", "receiverID = ? $more and message IS NOT NULL order by $orderby LIMIT $start, $limit", $this->flitter_array([$chat['user2'], $moreValue]), fetch: "moredetails");
            }else {
                $messages = $this->getall("message", "receiverID = ? and $where $more and message IS NOT NULL order by $orderby LIMIT  $limit", $this->flitter_array([$chat['user2'], $start, $moreValue]), fetch: "moredetails");
            }
        }else{
            if($start == "first") {
                $start = $this->getall("message", "chatID = ?", [$chatID], fetch: "") - $limit;
                if($start < 0) {$start = 0;}
            }
            $messages = $this->getall("message", "chatID = ? and $where $more and message IS NOT NULL order by $orderby LIMIT $limit", $this->flitter_array([$chatID, $start, $moreValue]), fetch: "moredetails");
        }
        return $messages;
    }

    function flitter_array(array $array) {
       return array_filter($array, function($value) {
            return $value !== "" || $value === 0;
        });

    }
    function delete_chat($messageID, $userID)
    {
        $delete = $this->delete("message", "ID = ? and senderID = ?", [$messageID, $userID]);
        if (!$delete) {
            return false;
        }
        $return = [
            "message" => ["Success", "Message Deleted. You might have to repload page to see update.", "success"],
            "function" => ["removediv", "data" => ["#chat-ID-".$messageID, "placeholder"]],
        ];
        return json_encode($return);
    }

    function group_chat_notification($userID, $message, $time_sent, $groupID)
    {
        $group = $this->getall("groups", "ID = ?", [$groupID]);
        if (!is_array($group)) {
            return false;
        }
        $data = [];
        $data['userID'] = $group['users'];
        $data['n_for'] = "message";
        $data['url'] = "index?p=chat&action=view&groupID=" . $groupID;
        $data['title'] = "New Message from " . $group['name'];
        $data['description']  = $this->short_text($message);
        $data['exclude'] = $userID;
        $data['time_set'] = $time_sent;
        $data['icon'] = $this->get_profile_icon_link($groupID, "groups");
        $this->new_notification($data);
    }

    function user_chat_notification($message, $time_sent)
    {
        $data = [];
        $user = $this->getall("users", "ID = ?", [$message['senderID']], "first_name");
        if (!is_array($user)) {
            return false;
        }
        $data['userID'] = $message['receiverID'];
        $data['n_for'] = "message";
        $data['forID'] = $message['chatID'];
        $data['url'] = "index?p=chat&action=view&userid=" . $message['senderID'];
        $data['title'] = "New Message from " . $user['first_name'];
        $data['description']  = $this->short_text($message['message']);
        $data['time_set'] = $time_sent;
        $data['icon'] = $this->get_profile_icon_link($message['senderID']);
        $this->new_notification($data);
    }


    function get_chat_message($chatID, $no = 0, $limit = 100)
    {
        $message = $this->getall("message", "chatID = ?  and message != ? and message IS NOT NULL order by date DESC LIMIT $no, $limit", [$chatID, ""], "moredetails");
    }

    function display_message(array $message, $userID)
    {
        if ($message['senderID']  == $userID) {
           return $this->display_send_message($message);
        } else {
            return $this->display_receive_message($message);
        }
    }


    function get_group_users_list($groupID, $start, $limit, $orderBy = "date DESC") {
        if($this->getall("groups", "ID = ? and users = ?", [$groupID, "all"], fetch: "") > 0){
           return $this->getall("users", "acct_type != ? order by $orderBy LIMIT $start, $limit", [''], "ID as user1", fetch: "moredetails");
        }else{
            return $this->getall("chat", "user2 = ? and is_group = ? and is_bot = ? order by $orderBy LIMIT $start, $limit", [$groupID, "yes", "yes"], fetch: "moredetails");
        }
    }
    function get_group_users($groupID, $start, $limit)
    {
        $chats = $this->get_group_users_list($groupID, $start, $limit);
       
        if ($chats->rowCount() > 0) {
            // echo $chats->rowCount();
            foreach ($chats as $row) {
                echo $this->display_chat_users_list($row);
            }
            return;
        }
        return "null";
    }

    function last_message($chatID, $userID, $is_group = "no")
    {
        $message = "";
        $last = "";
        if ($is_group == "no") {
            $last = $this->getall("message", "chatID = ? and message != ? and time_sent <= ? and message IS NOT NULL order by date DESC", [$chatID, "", time()], "message, senderID");
        } else {
            $chat = $this->getall("chat", "ID = ?", [$chatID], "user2");
            if (is_array($chat)) {
                $last = $this->getall("message", "receiverID = ?  and message != ? and time_sent <= ? and message IS NOT NULL order by date DESC", [$chat['user2'], "", time()], "message, senderID");
            }
        }

        if (is_array($last)) {
            if ($last['senderID'] == $userID) {
                $message = "You: ";
            }
            $message = $message . $last['message'];
        }
        return $message;
    }
    function display_chat_users_list($row)
    {

        $id = $row['user1'];
        $what = "users";
        return '
            <a href="index?p=chat&action=view&userid=' . $row['user1'] . '"
                class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user"
                >
                <div class="d-flex align-items-center">
                    <span class="position-relative">
                        <img src="' . $this->get_profile_icon_link($id, $what) . '" alt="user1" width="48" height="48" class="rounded-circle" />
                        
                    </span>
                    <div class="ms-3 d-inline-block w-75">
                        <h6 class="mb-1 fw-semibold chat-title" data-username="' . $this->get_name($id, $what) . '">' . $this->get_name($id, $what) . '</h6>
                        <span class="fs-3 text-truncate text-body-color d-block"></span>
                    </div>
                </div>
            </a>
        ';
    }
    function display_image_message($message)
    {
        return '<div data-chat-id="' . $message['ID'] . '" class="hstack gap-3 align-items-start mb-7 justify-content-start">
            <img src="'.rootFile.'dist/images/profile/user-8.jpg" alt="user8" width="40" height="40"
                class="rounded-circle" />
            <div>
                <h6 class="fs-2 text-muted">Andrew, 2 hours ago</h6>
                <div class="rounded-2 overflow-hidden">
                    <img src="'.rootFile.'dist/images/products/product-1.jpg" alt="" class="w-100">
                </div>
            </div>
        </div>       
       ';
    }
    function display_receive_message($message)
    {
        $upload = "";
        if ($message['upload'] != "" || $message['upload'] != null) {
            
            $upload =  $upload = $this->display_img($message);
        }

        if ($message['fileID'] != "" || $message['fileID'] != null) {
            $upload = $this->handle_file($message['fileID']);
        }

        if($message['message'] == "" || $message == null) {
            return ;
        }
        $this->handleLinkInText($message['message'] = str_replace("�", " ", $message['message']));
        if($message['time_sent'] > time() && $this->validate_admin()) {
            $ago = "<small class='text-danger'>Future: ".date("Y-m-d H:i:s", $message['time_sent'])."</small>";
        }else{
            $ago = $this->ago($message['time_sent']);
        }
        // if($message['message'] == "." && $message['upload'] != ""){
        //     $message['message'] = "";
        // }
        
        return '<div  id="chat-ID-'.$message['ID'].'" data-chat-id="' . $message['time_sent'] . '" class="hstack gap-3 align-items-start mb-7 justify-content-start ">
            <a href="index?p=chat&action=view&userid='.$message['senderID'].'"><img src="' . $this->get_profile_icon_link($message['senderID']) . '" alt="user8" width="40" height="40"
                class="rounded-circle"></a>
            <div>
            <a href="index?p=chat&action=view&userid='.$message['senderID'].'"></a>
            '.$this->display_reply_to($message).'
                ' . $upload . '
                <div class="p-2 bg-light rounded-1 d-inline-block text-dark fs-3"> 
                <h6 class="fs-1 text-muted">' . $this->get_name($message['senderID'], "users") . ', ' . $ago . '</h6>
                <p class="p-0 m-0" id="message-ID-'.$message['ID'].'">' . $message['message'] . ' </p>
                
                </div>
                '.$this->reply_message($message).'
                </div>
            ' . $this->message_options_btn($message) . '
        </div>';
    }

function reply_message(array $message) {
    return '<button onclick="reply_to(\''.$message['ID'].'\', \''.addslashes($message['message']).'\')" class="text-success btn btn-sm w-3 bg-light-success"><i class="ti ti-arrow-back-up"></i> Reply</button>';
}
    function message_options_btn($message, $position = "receive")
    {
        if(!$this->validate_admin()) {  
            if($position != "send")  { return ;}
            return '<div class="btn-group m-0 mb-2">
            <button type="button" class="bg-none border-0 border-2 btn-sm  bg-light-success rounded-5  text-dark" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li><button class="dropdown-item" onclick="reply_to(\''.$message['ID'].'\', \''.addslashes($message['message']).'\')"><i class="ti ti-arrow-back-up"></i> Reply Chat</button></li> 
                <li>
                    <hr class="dropdown-divider" />
                </li>
                
                <li>
                <form action="chat-passer" id="foo">
                                    <input type="hidden" name="delete_message" value="'.$message['ID'].'">
                                    <input type="hidden" name="confirm" value="Are you sure you want to delete this message?">
                                    <input type="hidden" name="page" value="chat" />
                                    <div id="custommessage"></div>
                                <input type="submit" name="delete_message" class="dropdown-item text-danger" value="Delete Message">
                    </form>
                    
                    </li>

            </ul>
        </div>';
        }
        return '<div class="btn-group mb-2">
            <button type="button" class="bg-none border-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <li><a id="edit-message-'.$message['ID'].'" class="dropdown-item" data-url="modal?p=chat&action=new&messageID='.$message['ID'].'" data-title="Edit Message" onclick="modalcontent(this.id)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" href="#">Edit Message</a></li>
                <li><a class="dropdown-item" href="#">View Profile</a></li>
                <li>
                    <a class="dropdown-item text-success" target="_blank" href="accessuser?id=' . $message['senderID'] . '">Gain Access to Account</a>
                </li> 
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li>
                <form action="" id="foo">
                                    <input type="hidden" name="delete_message" value="'.$message['ID'].'">
                                    <input type="hidden" name="confirm" value="Are you sure you want to delete this message?">
                                    <input type="hidden" name="page" value="chat" />
                                    <div id="custommessage"></div>
                                <input type="submit" name="delete_message" class="dropdown-item text-danger" value="Delete Message">
                    </form>
                    <form action="" id="foo">
                        <input type="hidden" name="block_chat_account" value="'.$message['senderID'].'">
                        <input type="hidden" name="confirm" value="Are you sure you want to block this user from accessing chat?">
                        <input type="hidden" name="page" value="users"/>
                        <div id="custommessage"></div>
                        <input type="submit" name="block_user_chat" class="dropdown-item text-danger" value="Block chat account">
                    </form>
                    <form action="" id="foo">
                        <input type="hidden" name="block_account" value="'.$message['senderID'].'">
                        <input type="hidden" name="confirm" value="Are you sure you want to block this user?">
                        <input type="hidden" name="page" value="users"/>
                        <div id="custommessage"></div>
                        <input type="submit" name="block_account" class="dropdown-item text-danger" value="Block account">
                    </form>
                    </li>
            </ul>
        </div>';
    }
    function display_img(array $message)
    {
        // check file type 

        return '<div style="width:150px; height: 200px" id="image-' . $message['ID'] . '" data-url="" data-title="Image Viewer" onclick="imageviwer(\''.ROOT.'assets/images/chat/' . $message['upload'] . '\')" data-bs-toggle="modal" data-bs-target="#bs-image-viwer-modal-md" class="rounded-2 overflow-hidden bg-light-primary position-relative rounded">      
            <p class="position-absolute top-50 start-50 translate-middle h2 text-mute"><i class="ti ti-photo"></i><p>            
                <img src="'.ROOT.'assets/images/chat/' . $message['upload'] . '" alt="uploaded" class="object-fit-contain w-100 position-absolute top-50 start-50 translate-middle"> 
            </div>';
    }

    function edit_message($messageID)
    {
        if (!$this->validate_admin()) {
            return false;
        }
        if(!isset($_POST['message']) || $_POST['message'] == "") { $this->message("Message can not be empty.", "error"); return false; }
        if(!isset($_POST['time_sent']) || $_POST['time_sent'] == "") { $_POST['time_sent'] = time(); }else{
            $_POST['time_sent'] = strtotime($_POST['time_sent']);
        }
        $message = htmlspecialchars($_POST['message']);
        $update = $this->update("message", ["message" => $message, "time_sent"=>$_POST['time_sent']], "ID = '$messageID'");
        if ($update) {
            $return = [
                "message" => ["Success", "Message Edited", "success"],
                "function" => ["update_chat", "data" => ["message-ID-" . $messageID, $message]],
            ];
            return json_encode($return);
        }
    }

    function handle_file($fileID) {
        $file = $this->getall("files_upload", "ID = ?", [$fileID]);

        if (!is_array($file)) {
            return "";
        }
        // get time
        // if time is more than 1hour and location is seerver
        // then unlink the file and update the current_location to google
        $anhourago = time() - 172800;
        if (
            $file["time_upload"] < $anhourago
            && $file['current_location'] == "server"
            && $file['googleID'] != ""
            && file_exists(PATH .'assets/images/chat/'. $file['file_name'])
        ) {
        //    unlink(PATH .'assets/images/chat/'. $file['file_name']);
           $file['current_location'] = "google";
           $this->update("files_upload", ["current_location"=>"google"], "ID = '$fileID'");
        }
        // $file['current_location'] = "google";
        if ($file['current_location'] == "server") {
            $path = PATH.'assets/images/chat/' . $file['file_name'] . "?autoplay=0";
            return '<div class="rounded-2 overflow-hidden">
                <video src="' . $path . '" style="width: 80%" height="400" controls ></video>
                 <p id="file-'.$fileID.'"></p>
                </div>';
        } else {
            $googleID = $file['googleID'];
            $path = "https://drive.google.com/file/d/$googleID/preview";
            return '<div class="rounded-2 overflow-hidden"><iframe src="' . $path . '" style="width: 70%" height="400" frameborder="0"></iframe></div>';
        }
    }

    function unlink_file($fileID) {
        
        $file = $this->getall("files_upload",  "ID = ?", [$fileID]);
        if(!is_array($file)) { return false; }
        if(file_exists(PATH.'assets/images/chat/' . $file['file_name'])){
            unlink(PATH.'assets/images/chat/' . $file['file_name']);
        }
        $this->delete("files_upload", "ID = ?", [$fileID]);
        return true;
    }

    function display_send_message($message)
    {
        $upload = "";
        if ($message['upload'] != "" || $message['upload'] != null) {
            
            $upload = $this->display_img($message);
        }

        if ($message['fileID'] != "" || $message['fileID'] != null) {
            $upload = $this->handle_file($message['fileID']);
        }

        if($message['message'] == "" || $message == null) {
            return ;
        }

        if($message['time_sent'] > time() && $this->validate_admin()) {
            $ago = "<small class='text-danger'>Future: ".date("Y-m-d H:i:s", $message['time_sent'])."</small>";
        }else{
            $ago = $this->ago($message['time_sent']);
        }
        $message['message'] = $this->handleLinkInText(str_replace("�", " ", urldecode($message['message'])));
        // if($message['message'] == "." && $message['upload'] != ""){
        //     $message['message'] = "";
        // }
        return '
        <div id="chat-ID-'.$message['ID'].'" data-chat-id="' . $message['time_sent'] . '" class="hstack gap-3 align-items-start mb-7 justify-content-end ">
           
        <div class="text-end">
        <div class="p-2  text-dark fs-2 m-0">
        
                '.$this->display_reply_to($message).'
                </div>
                ' . $upload . '
                <div class="p-2 bg-light-info text-dark rounded-1 d-inline-block fs-3 m-0" id="message-ID-'.$message['ID'].'"> ' . $message['message'] . '</div>
                
                <br>
                <p class="text-dark"> ' . $ago . '
                <!-- reply message here -->
                </p>
            </div>
            ' . $this->message_options_btn($message, "send") . '
        
            </div>';
    }
    function display_reply_to($message) {
        if(empty($message['reply_to']) ||  $message['reply_to'] == null) {
            return  "";
        }
        $reply_message = $this->getall("message", "ID = ?", [$message['reply_to']]);
        if(!is_array($reply_message) || $reply_message['message'] == ".") { return ""; }
        return '<h1 onclick="scrollToChat(\'chat-ID-'.$reply_message['ID'].'\');" class="fs-3 h6 bg-light text-dark p-2 m-0 opacity-75">
        <p class="text-success fs-1 m-0 p-0">'.$this->get_name($reply_message['senderID']).'</p>
         <span class="">' . $this->short_text($reply_message['message'], 100) . '</span>
         
        </h1>';

    }

    function list_user($row, $userID, $active = "")
    {
        $id = $row['user1'];
        $isPined = "";
        $what = "users";
        if ($row['user1'] == $userID) {
            $id = $row['user2'];
        }

        if ($row['is_group'] == "yes") {
            $what = "groups";
        }
        if($row['user1'] == "admin" || $row['user2'] == "admin") {
            $isPined = 'isPined';
        }
        if($row['is_group'] != "yes" && isset($_SESSION['adminSession']) && side == "admin") {
            return $this->admin_user_list($row, $userID, $active);
        }
        $no = $this->get_unseen_message($userID, $row['ID']);
        $nameclass = "";
        $badge = "";
        if ($no > 0) {
            $nameclass = "fw-semibold";
        }
        if ($no == 0) {
            $no = "";
        }
        if ($no > 99) {
            $no = "99+";
        }
        if ($no != "") {
            $badge = '<div class="text-danger  text-align-right bg-light-danger badge p-1 mt-2 fs-2 bg-light-danger"><b>' . $no . '</b></div>';
        }
        $displayname = $this->short_text($this->get_name($id, $what), 23);
        if(in_array($displayname,  $this->chat_holder)){
            return "";
        }
        $this->chat_holder[] = $displayname;
        

        echo '<li class="'.$isPined.'">
            <a href="index?p=chat&id=' . $row['ID'] . '"
                class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user ' . $active . ' w-100"
                id="chat_user_' . $row['ID'] . '" data-user-id="' . $row['ID'] . '">
                <div class="d-flex align-items-center">
                    <span class="position-relative">
                        <img src="' . $this->get_profile_icon_link($id, $what) . '" alt="user1" width="40" height="40" class="rounded-circle" />
                        
                    </span>
                    <div class="ms-3 d-inline-block w-100">
                        <h6 class="mb-1 chat-title ' . $nameclass . '" data-username="'.$displayname.'">' . $displayname . '</h6>
                        <span class="fs-3 text-truncate ' . $nameclass . ' text-body-color d-block">' . $this->short_text($this->last_message($row['ID'], $userID, $row['is_group']), 20) . '</span>
                    </div>
                    </div>
                    ' . $badge . '
            </a>
        </li>';
    }

    function admin_user_list($row, $userID, $active = "") {
        $id = $row['user1'];
        $user2 = $row['user2'];
        $what = "users";
        if ($row['user1'] == $userID) {
            $id = $row['user2'];
            $user2 = $row['user1'];
        }

        if ($row['is_group'] == "yes") {
            $what = "groups";
        }
        $no = $this->get_unseen_message($userID, $row['ID']);
        $nameclass = "";
        $badge = "";
        if ($no > 0) {
            $nameclass = "fw-semibold";
        }
        if ($no == 0) {
            $no = "";
        }
        if ($no > 99) {
            $no = "99+";
        }
        if ($no != "") {
            $badge = '<div class="text-danger  text-align-right bg-light-danger badge p-1 mt-2 fs-2 bg-light-danger"><b>' . $no . '</b></div>';
        }
        $displayname = $this->short_text($this->get_name($id, $what), 10) . ' & '.$this->short_text($this->get_name($user2, $what), 10);
        if(in_array($displayname,  $this->chat_holder)){
            return "";
        }
        $this->chat_holder[] = $displayname;
        echo '<li>
            <a title="Coversation between '.$this->get_name($id, $what).' and '.$this->get_name($user2, $what).'" href="index?p=chat&id=' . $row['ID'] . '"
                class="px-4 py-3 bg-hover-light-black d-flex align-items-start justify-content-between chat-user ' . $active . ' w-100"
                id="chat_user_' . $row['ID'] . '" data-user-id="' . $row['ID'] . '">
                <div class="d-flex align-items-center">
                    <span class="position-relative">
                        <img src="' . $this->get_profile_icon_link($user2, $what) . '" alt="user1" width="40" height="40" class="rounded-circle" />
                        
                    </span>
                    <span class="position-relative" style="margin-left: -10px">
                        <img src="' . $this->get_profile_icon_link($id, $what) . '" alt="user1" width="40" height="40" class="rounded-circle border border-3 border-white" />
                        
                    </span>
                    <div class="ms-3 d-inline-block w-100">
                        <h6 class="mb-1 chat-title ' . $nameclass . '" data-username="'.$displayname.'">' . $displayname.'</h6>
                        <span class="fs-3 text-truncate ' . $nameclass . ' text-body-color d-block">' . $this->short_text($this->last_message($row['ID'], $userID, $row['is_group']), 20) . '</span>
                    </div>
                    </div>
                    ' . $badge . '
            </a>
        </li>';
    }
}
