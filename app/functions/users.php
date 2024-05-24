<?php 
class user extends Notifications {
    public $userdata;
    public $userID;
    protected $profile_link_root = PATH."assets/images/profile/";

    // public function __construct(String $userID = null, array $userdata = []){
    //     if($this->userID == null && isset($_SESSION['userID'])) {
    //         $this->userID = htmlspecialchars($_SESSION['userID']);
    //     }
    //     if($this->userID != null && empty($this->userdata)) {
    //         $data = $this->getall("users", "ID = ?", [$userID]);
    //         if(is_array($data)) {
    //             $this->userdata = $data;
    //         }
    //     }
    // }

    function send_email_verify() {

    }

    function generate_bot_withdraw() {    
        $no = rand(10, 15);
        // write a condition to check number for the day b4 runnig
        if($this->getall("withdraw", "date >= ? and status = ?", [date("Y-m-d"), "bot"], fetch: "") >= 15){
            return;
        }
        for ($i=0; $i < $no; $i++) { 
            $date = $this->generateRandomDateTime( date('Y-m-d H:i:s', strtotime('midnight')), date('Y-m-d H:i:s', strtotime('tomorrow') - 1));
            $user = $this->generate_withdrawal_user(time());
            $data = [];
            $data['ID'] = uniqid();
            $data['userID'] =  $user['ID'];
            $data['amount'] =  rand(200, 800000);
            $data['wallet'] = "64fe21832f8b4";
            $data['status'] = "bot";
            $data['date'] = $date;
            $this->quick_insert("withdraw", $data);
        }
    }
    function get_all_emails() {
        // SELECT GROUP_CONCAT(email SEPARATOR ',') AS all_emails FROM table_name;
        return $this->getall("users", "acct_type = ?", ["user"], "GROUP_CONCAT(email SEPARATOR ', ') AS all_emails")['all_emails'];
    }
    function get_profile_icon_link($userID = null, $what = "users") {
        $gender = "default";
        $user = $this->getall("$what", "ID  = ?", [$userID], "profile_image", "details");
        if(isset($user["profile_image"]) && $user["profile_image"] !=  null) {
           if(str_contains($user["profile_image"], "https") || str_contains($user["profile_image"], "http")){
               return $user["profile_image"];
           }
            return $this->profile_link_root.$user["profile_image"];
        }

        if($userID == null) { return  $this->profile_link_root."default.jpg" ;}

        if(isset($user['gender']) && $user['gender'] != "" | null) {
            $gender = $user['gender'];
        }elseif($what == "users") {
            $check = $this->getall("users", "ID = ?", [$userID], "gender");
            if(isset($check['gender'])) {
                $gender = $check['gender'];
            }
        }
        
        return $this->profile_link_root.lcfirst($gender).".jpg";        
    }
    
    function change_password($data, $userID) {
        if(!is_array($data)) { return null; }
        $info = $this->validate_form($data);
        if(!is_array($info)) { return null; }
        $user = $this->getall("users", "ID = ?", [$userID], "password", "details");
        if(isset($user['password']) && password_verify($info['current_password'], $user['password'])) {
          $update =  $this->update("users", ["password"=>password_hash($info['password'], PASSWORD_DEFAULT)], "ID = '$userID'", "Password Changed.");
          if($update) {
            $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Password changed", "description" => "Your password was changed."];
            $this->new_activity($actInfo);
          }
        }else{
            $this->message('Current password incorrect', 'error');
        }
    }

    function update_profile($data, $userID) {
        $info = $this->validate_form($data);
        if(!is_array($info)) {
            return null;
        }
        $user = $this->getall("users", "ID = ?", [$userID], fetch: "details");
        if(!is_array($user)) { $this->message("User ot found", "error"); return null;}
        if($user['email'] != $info['email'])  {
            if($this->getall("users", "email = ?", [$info['email']]) > 0) {
                $this->message("User with email alrady exit",  "error");
                return null;
            }
            $info['email_verify'] = 0;
        }
        // check phone number 
        if($this->getall("users", "ID != ? and phone_number = ?", [$userID, $info['phone_number']]) > 0) {$this->message("User with phone number alrady exit",  "error"); return null;}
        if($this->update("users", $info, "ID = '$userID'", "Profile updated")){
            $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Account Update", "description" => "Your account got updated."];
            $this->new_activity($actInfo);
        }

    }

    function upload_kyc($data, $userID) {
        $_POST['kyc_status'] = "pending";
        $user = $this->validate_form($data);
        if(!is_array($user)) { return false; }
        $update = $this->update("users", $user, "ID = '$userID'", "KYC submitted for verification. You can still upload a new ID within the verification period.");
        if(!$update) { return null; }
        $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "KYC Upload", "description" => "Upload of ID for KYC verification."];
        $this->new_activity($actInfo);  
    }
    function change_profile_pic($userID) {
        $from = [
            "profile_image"=>["input_type"=>"file", "file_name"=>$userID, "path"=>"assets/images/profile/"],
        ];
        $info = $this->validate_form($from);
        $update = $this->update("users", $info, "ID = '$userID'");
        if(!$update) { return null; }
        $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Change profile picture", "description" => "Profile picture changed."];
        $this->new_activity($actInfo);
        $return = [
            "message" => ["Sucess", "Profile Updated", "success"],
        ];
        return json_encode($return);
    }



    protected function credit_debit($userID, $amount, $what, $action = "credit", $for = "", $forID = "")
    {
        unset($_COOKIE['user_data']);
        $user = $this->getall("users", "ID = ?", [$userID], "$what, acct_type");
        if (!is_array($user)) {
            $this->message("Error getting user", "error");
            return;
        }
        switch ($action) {
            case 'credit':
                # code...
                $user[$what] = (float)$user[$what] + (float)$amount;
                break;
            case 'debit':
                // check if enough balance
                if((float)$user[$what] < (float)$amount){
                    $this->message("Insufficient ".str_replace("_", " ", $what).". Your balance: <b>".$this->money_format($user[$what])."</b>", "error");
                    return false;
                }

                $user[$what] = (float)$user[$what] - (float)$amount;
                break;

            default:
                # code...
                break;
        }
        $acct_type = $user['acct_type'];
        unset($user['acct_type']);
        $update = $this->update("users", $user, "ID = '$userID'");
        if (!$update) {
            $this->message("We have issue performing this task", "error");
            return null;
        }
        if($acct_type != "bot") {
            $trans = ["userID"=>$userID, "forID"=>$forID, "trans_for"=>$for, "action_type"=>$action, "acct_type"=>$what, "amount"=>$amount,  "current_balance"=>$user[$what]];
            $this->quick_insert("transactions", $trans);
        }
        return true;
    }

    function transfer_funds($data) {
            if(!is_array($data)){ return null ;}
            $info = $this->validate_form($data);
            if(!is_array($info)) {return null;}
            $user = $this->getall("users", "ID = ?",[$info['userID']], "balance, trading_balance");
            if(!is_array($user)) { return $this->message("User not found. Reload page and try again.", "error");}
            
            switch ($info['move_from']) {
                case 'trading_account':
                    $to = "trading_balance";
                    $from = "balance";
                    break;
                case 'balance':
                    $from = "trading_balance";
                    $to = "balance";
                    break;
                
                default:
                   return $this->message("Select where to move funds to.", "error");
                    break;
            }

            // check for last transfer date
            // 1 month, the 2 weeks
            // get first investment
            if($from == "trading_balance") {
                $last_date = $this->get_settings("last_trading_transfer", who: $info['userID']);
                if($last_date != "") {
                    $diff = abs(strtotime(date("Y-m-d")) - strtotime($last_date)) / 86400;
                    if($diff < (int)$this->get_settings("subsequent_withdraw_after")) {
                        $this->message("You can only make a withdrawal after ".(int)$this->get_settings("subsequent_withdraw_after")." days of your last withdrwal.", "error");
                        return false;
                    }
                }

                $invest = $this->first_trading($info['userID']);
                if(is_array($invest)) {
                    $last_date = $invest['date'];
                    $diff = abs(strtotime(date("Y-m-d")) - strtotime($last_date)) / 86400;
                    if($diff < (int)$this->get_settings("first_withdraw_after")) {
                        $this->message("You can not make a withdrawal until ".(int)$this->get_settings("first_withdraw_after")." days after your first investment. <a class='text-primary' href='mailto: ".$this->get_settings("support_email")."'> Click here </a> to email us for more information and support", "error");
                        return false;
                    }
                }

            }

            if((float)$info['amount'] > (float)$user[$from]) {
                return $this->message("Insufficient ".str_replace("_", " ", $from).".", "error");
            }
            // $update[$from] = (float)$user[$from] - (float)$info['amount'];
            // $update[$to] = (float)$info['amount'] + (float)$user[$to];
            
            $id = $info['userID'];
            // $update = $this->update("users", $update, "ID = '$id'");

            $update = $this->credit_debit($info['userID'], $info['amount'], $from, "debit", for:"Transfer");
            if(!$update) { return false; }
            $update = $this->credit_debit($info['userID'], $info['amount'], $to, "credit", for:"Transfer");
            if($update) {
                if($from == "trading_balance") {
                    $this->last_from_trading_balance(date("Y-m-d"), $info['userID']);
                }

                $from = str_replace("_", " ", $from);
                $to = str_replace("_", " ", $to);
                $actInfo = ["userID" => $info['userID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Money Transfer", "description" => "Money was transfered from $from to $to."];
                $this->new_activity($actInfo);
                $return = [
                    "message"=> ["Success", "Fund Added", "success"],
                    "function"=>["loadpage", "data"=>["index?p=investment", "null"]]
                ];
                return json_encode($return);
            }
    }

    function first_trading($userID){
        return $this->getall("investment", "userID = ? order by date ASC", [$userID], fetch: 'details');
    }



    function update_last_seen($userID, $time) {
        if($this->getall("users", "ID = ?", [$userID], fetch: "") > 0) {
            if($this->update("users", ["last_seen"=>$time], "ID  = '$userID'")) {
                return true;
            }
            return false;
        }
        return false;
    }
    function last_from_trading_balance($date, $userID){
        if($this->get_settings("last_trading_transfer", who: $userID) != "") {
           $update = $this->update("settings", ["meta_value"=>$date], "meta_name = 'last_trading_transfer' and meta_for = '$userID'");
            if($update) {
                return true;
            }
            return false;
        }

        $insert = $this->quick_insert("settings", ["meta_name"=>"last_trading_transfer", "meta_value"=>$date, "meta_for"=>$userID]);
        if($insert) {
            return true;
        }
        return false;
    }
    function user_data($userID) {
        if (isset($_COOKIE['user_data'])) {
            // echo "Cookies here";
            // return  unserialize($_COOKIE['user_data']);
        }
        // amount_invest number_invet profit_percent lost_percent profit_amount lost_amount trade_balance trade_bonus balance 
        $info = [];
        $user = $this->getall("users", "ID = ?", [$userID], "SUM(balance) as balance");
        $info = array_merge($info, $user);
        // var_dump($info);
        // try {
        //     setcookie("user_data",serialize($info), time()+30*60);
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
        return $info;
    }

    function show_balance($data = null) {
        if(!is_array($data) && $data != null) {
            $data = $this->user_data($data);
        }
        if(is_array($data)) {
            $balance = $this->money_format($data['balance'], currency);
            return "<div class='card bg-primary p-3 text-white'><b>Balance: </b><p class='h2 text-white'><b>$balance</b></p> <p>
            <a href='#' data-bs-toggle='modal' data-bs-target='#bs-example-modal-md' id='modal-viewer-funaccount' data-url='modal?p=deposit&action=new' data-title='Fund Account' onclick='modalcontent(this.id)' class='btn btn-sm bg-blue'>+ Fund Account</a>
          </div> ";
        }
        return null;
    }

    function new_wallet($data) {
        $wallet = $this->validate_form($data);
        if(!is_array($wallet)) { return null; }

    }
    
    function profile_picture_default($userID = null) {
        $profile_picture_link = $this->get_profile_icon_link($userID);
        if(!file_exists(($profile_picture_link))) { return ""; }
        return "<a class='nav-link pe-0' href='javascript:void(0)' id='drop1' data-bs-toggle='dropdown' aria-expanded='false'>
        <div class='d-flex align-items-center'>
          <div class='user-profile-img'>
            <img src='".$profile_picture_link."' class='rounded-circle' width='35' height='35' alt='User Profile picture' />
          </div>
        </div>
      </a>";
    }

     function newuser($data) {
        $_POST['ID'] = uniqid();
        $info =  $this->validate_form($data);
        if(!is_array($info)) { return null; }
        $check = $this->getall("users", "email = ? or phone_number = ?", [$info['email'], $info['phone_number']]);
        if($check > 0){
            echo $this->message("User with email or phone number alrady exit.", "error");
            return null;
        }

        $info['password'] = password_hash($info['password'], PASSWORD_DEFAULT);
        unset($info['confrim_password']);

       $this->quick_insert("users", $info, message: "Accont created successfully please login");
    }

     function edituser($data, $userID = 0) {
        $info =  $this->validate_form($data);
        if(!is_array($info)) { return null; }
            $check = $this->getall("users", "ID != ? and email = ? and phone_number = ?", [$info['ID'], $info['email'], $info['phone_number']]);
        if ($check > 0) {
            echo $this->message("User with email or phone number alrady exit.", "error");
            return null;
        }

        if(!isset($_SESSION['adminsession']) && $userID != $info['ID']){
            echo $this->message("You can not perform this action", "error");
            return null ;
        }
        unset($info['ID']);
        $id = $info['ID'];
        $update = $this->update("users", $info, "ID = '$id'");
        if($update) {
            echo $this->message("Account update successfully", "success");
        }

    }
    function get_profile() {
         
    }

    function fetchusers($start = '0', $limit = 10, $id = null) {
        if($id !== null) {
            $data = $this->getall("users", "ID = ? LIMIT $start, $limit",data:[$id], fetch: "moredetails");
        }else {
            $data = $this->getall("users", "LIMIT $start, $limit", fetch: "moredetails");

        }
        if(is_array($data) || $data != ""){
            return $data;
        }
        return null;
    }

    function  short_user_table($user, $url = "javascript:void(0)")
    {
        if(!is_array($user) && $user != "") {
            $user = $this->getall("users", "ID = ?", [$user]);
        }
        if(!is_array($user)) {
            return "<b class='text-danger'>User Not Found</b>";
        }
        return "<li>
            <a href='$url' onclick='display_content(this);' class='p-3 bg-hover-light-black d-flex align-items-center chat-user' id='chat_user_" . $user['ID'] . "'  data-user-id='" . $user['ID'] . "'>
                <span class='position-relative'>
                    <img src='" . $this->get_profile_icon_link($user['ID']) . "' alt='user-4' width='40' height='40' class='rounded-circle'>
                </span>
                <div class='ms-6 d-inline-block w-75'>
                    <h6 class='mb-1 fw-semibold chat-title' data-username='" . $this->get_full_name($user) . "'>" . $this->get_full_name($user) . " </h6>
                    <!-- <span class='fs-2 text-body-color d-block'>" . $user['email'] . "</span> -->
                </div>
            </a>
        </li>";
    }
    function get_full_name($user) {
        if(!is_array($user)){
            return "Unknown";
        }
        return str_replace("�", " ", $user['first_name'].' '.$user['last_name']);
    }

    function get_name($id, $what = "users", $type = false) {
        switch ($what) {
            case 'users':
                $data = $this->getall("users", "ID = ?", [$id], "first_name, last_name, acct_type");
                $name =  $this->get_full_name($data);
                if($type) {
                    $name .= " - ".$data['acct_type'];
                }
                return $name;
                break;
                case 'groups':
                    $data = $this->getall("groups", "ID = ?", [$id], "name");
                    if(!is_array($data)) {
                        return "Unknown";
                    }
                    return $data['name'];
                    break;
            default:
                return "Unknown";
                break;
        }
    }

    function create_default_group_chat($chat_from, $userID) {
       $groups = $this->getall("groups",  "users = ?", ["all"],  fetch: "moredetails");
    //    var_dump($groups->rowCount());
       if($groups->rowCount() == 0) {
            return true;
        }
        foreach($groups as $row){
            if($this->getall("chat", "user1 = ? and user2 = ?", [$userID, $row['ID']], fetch: "") > 0){
               continue;
           }
            $_POST['user1'] = $userID;
            $_POST['user2'] = $row['ID'];
           $_POST['is_group'] = "yes";
           $this->create_chat($chat_from);
           
        }    
    }

    function insert_default_message($userID, $receiverID, $message = "", $is_group = "yes", $time = 0) {
        if($this->getall("message", "senderID = ? and receiverID = ?", [$userID, $receiverID], fetch: "") > 0) { return true; }
        $chat = $this->getall("chat", "user1 = ? and user2 = ?",[$userID, $receiverID], "ID");
        if(!is_array($chat)) { return false; }
        $this->quick_insert("message", ["chatID"=>$chat['ID'],"senderID"=>$userID, "receiverID"=>$receiverID, "message"=>$message, "is_group"=>"$is_group", "time_sent"=>$time]);
    }
    function create_chat($chat_from) {
        return $this->validate_form($chat_from, "chat", "insert");
    }

    function new_user_chat($userID, $user2, $chat_from, $r = true) {
        $check =  $this->getall("chat", "user1 = ? and user2 = ?", [$userID, $user2]);
        if(is_array($check)) {
            if($r) {
            $this->loadpage('index?p=chat&id='.$check['ID']);
            }
            return ;
        }
        $check =  $this->getall("chat", "user1 = ? and user2 = ?", [$user2, $userID]);
        if(is_array($check)) {
            if($r) {
                $this->loadpage('index?p=chat&id='.$check['ID']);
            }
            return ;
        }

        if(!$this->getall("users", "ID = ?", [$user2], fetch: "")) {
            return false;
        }
        $_POST['user1'] = $userID;
         $_POST['user2'] = $user2;
        if($user2 == "admin") {
            $_POST['user2'] = $userID;
            $_POST['user1'] = $user2;
        }
        $_POST['is_group'] = "no";
        if($this->create_chat($chat_from)){
            
            if($user2 == "admin"){
                
                $this->insert_default_message("admin", $userID, $this->get_settings("default_support_welcome_message"), "no", time());
                
            }
            if($r){
                $this->new_user_chat($userID, $user2, $chat_from);
            }       
        }
    }

    function get_all_chat_notification($userID) {
        
    }

    function activate_referral($userID, $referralID) {
        $data = ['ID'=>["input_type"=>"number"],
            "userID"=>[],
            "referralID"=>[],
            "referral_code"=>[],
            "investID"=>["is_required"=>false],
            "status"=>[],
            "input_data"=>["investID"=>""],
        ];
    $this->create_table('referrals', $data);
    unset($data['ID']);
    unset($data['investID']);
    $_POST['userID'] = $userID;  $_POST['referralID'] = $referralID; 
    $_POST['referral_code'] = $this->genrate_code();
    $_POST['status'] = "active";
    $data = $this->validate_form($data);
    // valiadte is the exact plan not active
    if(!is_array($data)) { return false; }
        if ($this->getall("referrals", 
            "userID = ? and referralID = ? and status = ?", 
            [$data['userID'], $data['referralID'], "active"], 
            fetch: "") > 0) {
            $this->message("This referral is currently active for you.", "error");
            return false;
        }
        if(!$this->quick_insert("referrals", $data, "Referral Activated")){
            return false;
        }
            return true;
        
    }
    
    function get_ref_info($code) {
        // no of active ref
        // no of pending
        // percentage
        // get referralID from referral where referral_code equal code
        // get referral program details through the ID
        // check percentage of active_ref againts the no_of_users in referral_program 
        $info = ["no_allocated"=>0, "no_pending"=>0, "percentage"=>0, "no_of_users"=>0];
        $info['no_allocated'] = $this->getall("referral_allocation", "referral_code = ? and status = ?", [$code, "allocated"], fetch: "");
        $info['no_pending'] = $this->getall("referral_allocation", "referral_code = ? and status = ?", [$code, "pending"], fetch: "");
        $refID = $this->getall("referrals", "referral_code = ?", [$code], "referralID");
        if(!is_array($refID)) { return $info; }
        $ref_program = $this->getall("referral_programs", "ID = ?", [$refID['referralID']], "no_of_users");
        if(!is_array($ref_program)) { return $info; }
        $info['no_of_users'] = $ref_program['no_of_users'];
        $info['percentage'] = $this->cal_percentage($info['no_allocated'], $ref_program['no_of_users']);
        return $info;
        // $info = ["no_allocated", "no_pending", "percentage"]
    }

    function genrate_code() {
        $code = $this->RandomCode($length = 5);
        if($this->getall("referrals", "referral_code = ?", [$code], fetch: "") > 0) { 
            $this->genrate_code(); 
        }else{
            return $code;
        }
    }

    
    function RandomCode($length = 5)
    {
        $code = '';
        $total = 0;
    
        do
        {
            if (rand(0, 1) == 0)
            {
                $code.= chr(rand(97, 122)); // ASCII code from **a(97)** to **z(122)**
            }
            else
            {
                $code.= rand(0, 9); // Numbers!!
            }
            $total++;
        } while ($total < $length);
    
        return strtoupper($code);
    }
}