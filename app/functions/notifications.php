<?php  
    class Notifications extends database {


        function get_pending_all_notification(string $userID, $for = "", $type = "sn.notificationID IS NULL and sn.userID") {
            // var_dump($userID);
            $more_where = "";
            $more_value = "";
            if($for != "") {
                $more_where = "and n_for = ?";
            }
            $nofications = $this->getall("
            notifications as n LEFT JOIN sent_notifications as sn ON n.ID = sn.notificationID and n.userID = sn.userID", 
            "n.userID = ? or n.userID = ? $more_where and n.status = ? and n.exclude NOT LIKE '%$userID%' and $type IS NULL order by n.date DESC", 
            array_values(array_filter([$userID, "all", $for, 'active'])), 
            "n.*",
            fetch: "moredetails");
            // $nofications = $this->getall("
            // notifications", 
            // "userID = ? or userID = ? $more_where and status = ? and exclude NOT LIKE '%$userID%' and $type IS NULL order by date DESC", 
            // array_values(array_filter([$userID, "all", $for, 'active'])), 
            // "n.*",
            // fetch: "moredetails");
           return $nofications;
        }

        function get_type_notifications($userID = "all", $date = null, $type = "global_report", $exclude = "", $update = false) {
            if($date == null) { $date = date("Y-m-d"); }
            if($exclude != ""){ $exclude = "and n.exclude NOT LIKE '%$exclude%'"; }
            // if($userID = "all") {
            // }
            $notification =  $this->getall("notifications as n LEFT JOIN sent_notifications as sn ON n.ID = sn.notificationID and n.userID = sn.userID", "n.userID = ? and n.n_for = ? and n.date_set = ? and n.status = ? $exclude and sn.notificationID IS NULL and sn.userID IS NULL order by n.date DESC", [$userID, $type, $date, 'active'], "n.*");
            if(is_array($notification) && $update != false) {
                if($userID == "all"){
                    $this->exclude_user($notification['ID'], $update);
                }else{
                    $this->sent_notification($notification['ID'], $update);
                }
            }
            return $notification;
        }

        function exclude_user($ID, $userID) {
            $data = $this->getall("notifications", "ID = ? and exclude NOT LIKE '%$userID%'", [$ID], "userID, status, exclude");
            if(!is_array($data)) { return false; }
            $info = [];
            if($data['userID'] != "all") { $info['status'] = "deactive"; }
            if($data['exclude'] == "NULL") { $info['exclude'] = $userID; }else{ $info['exclude'] = $data['exclude'].' , '.$userID; }
            if($this->update("notifications", $info, "ID = '$ID'")){  return true; }
        }
        function clear_notification($userID) {
            $data = $this->getall("notifications", "forID = ? and exclude NOT LIKE '%$userID%'", [$ID], "userID, status, exclude");
            if(!is_array($data)) { return false; }
            $info = [];
            if($data['userID'] != "all") { $info['status'] = "deactive"; }
            if($data['exclude'] == "NULL") { $info['exclude'] = $userID; }else{ $info['exclude'] = $data['exclude'].' , '.$userID; }
            if($this->update("notifications", $info, "forID = '$userID'")){  return true; }
        }

        

        function sent_notification($nID, $userID) {
            // var_dump($nID);
            if($this->getall("sent_notifications", "userID = ? and notificationID = ?", [$userID, $nID], fetch: "") > 0){ return true; };
            if($this->quick_insert("sent_notifications", ["userID"=>$userID, "notificationID"=>$nID, "sent_time"=>time()])) { return true; }
            return false;
        }

        function display_chat_notification($notifications) {
            if($notifications->rowCount() == 0){ return false; }
                // body, title, message, icon, url
                $n = $notifications->rowCount();
                $title = $n." Notificatiion(s) unread";
                $body = "You have $n unread  notification(s) on ".company_name.'. Click to read them.';
                $icon = ROOT."assets/images/logos/".$this->get_settings("favicon");
                $url =  ROOT."index?p=notifications";
                $tag = "No_of_pending_notifications_".uniqid();
                $return = ["function"=>["display_notification", "data"=>["title=$title&body=$body&icon=$icon&url=$url&tag=$tag", "null"]]];
                return json_encode($return);
        }

        function pop_message($message, $userID = null) {
            if(!is_array($message)) { return false; }
            $title = $message['title'];
            $body = $message['description'];
            $icon = $message['icon'];
            $url =  $message['url'];
            $tag = "new_message".uniqid();
            $return = ["function"=>["display_notification", "data"=>["title=$title&body=$body&icon=$icon&url=$url&tag=$tag", "null"]]];
            if($userID !=  null) {
                $this->send_email($userID, $title, $body);
            }
            return json_encode($return);
        }
        function show_notification_list(array $data) {
            if($data['status'] !=  "active"){ return false; }
            $icon = '<div class="rounded-circle bg-light-primary text-center align-items-center d-flex justify-content-center" style="width: 48px; height: 48px;"><span class="ti ti-messages fs-5 text-primary"></span></div>';
            if($data['icon'] != "" || $data['icon'] != null) {
                $icon = '<img src="'.$data['icon'].'" alt="user" class="rounded-circle" width="48" height="48" />';
            }
            echo '<a href="'.$data['url'].'&note='.$data['ID'].'" class="pt-3 d-flex align-items-center dropdown-item">
            <span class="me-3">
                '.$icon.'
            </span>
            <div class="w-75 d-inline-block v-middle">
                <h6 class="mb-1 fw-semibold fs-3">'.$this->short_text($data['title'], 25).'</h6>
                <span class="d-block fs-3">'.$this->short_text($data['description'], 40).'</span>
                <span class="d-block fs-1">'.$this->ago($data['time_set']).'</span>
            </div>
        </a>';
        }

        // function get_all_pending_notifications($userID, $for = "") {
        //     $more_value = "";
        //     if($for != "") {
        //         $more_where = "and n_for = ?";
        //     }
        //     $nofications = $this->getall("
        //     notifications as n LEFT JOIN sent_notifications as sn ON n.ID = sn.notificationID and n.userID = sn.userID", 
        //     "n.userID = ? or n.userID = ? $more_where and n.exclude != ? and n.status = ? and sn.notificationID IS NULL and sn.seen_time IS NULL order by n.date DESC", 
        //     array_values(array_filter([$userID, "all", $for, $userID, 'active'])), 
        //     fetch: "moredetails");
        //    return $nofications;
        // }
    }