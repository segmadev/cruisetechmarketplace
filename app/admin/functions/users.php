<?php
class users extends user
{
    protected $role;
    public function __construct() {
        // Call the parent constructor
        parent::__construct();
        $this->role = new roles;
    }

    function block_user($user_id, $what = "status") {
        if(!$this->validate_admin()) { return false; }
        if(!$this->role->validate_action(["users"=>"block"])) return ;
        $update = $this->update("users", ["$what"=>"blocked"], "ID = '$user_id'");
        if($update) {
            return $this->message("Blocked", "success", "json");
        }
        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
                "action_name" => "block User",
                "description" => "Block User", 
                "action_for"=>"users", 
                "action_for_ID"=>htmlspecialchars($user_id)];
    }

    function total_transaction_type($userID, $type) {
        return $this->getall("transactions", "userID = ? and amount > ? and action_type = ?", [$userID, 0, $type], "SUM(amount) as total", );
    }
    // approve or reject deposit 
    function update_deposit_status() {
        $data = $this->checkmessage(["ID", "amount", "status", "reason_null"]);
        if(!is_array($data)) { return false; }
        $deposit = $this->getall("deposit", "ID = ? and status = ?", [$data['ID'], "pending"]);
        if(!is_array($deposit)) { return $this->message("Deposit is no more pending or not found.", "error", "json");}
        $id = $data['ID'];
        unset($data['ID']);
        $update = $this->update("deposit", $data, "ID = '$id'");
        if(!$update) { return $this->message("Unable to perfrom this action. Reload page and try again", "error", "json");  }
        if($data['status'] == "approved") {
            //  increase user balance
            $update = $this->increase_balance($data['amount'], $deposit['userID']);
            if(!$update) {
                $this->update("deposit", ["status"=>"pending"], "ID = '$id'");
                return $this->message("status not updated: Unable to increase balance", "error", "json");
            }    
        // activate pending investment if avilable
        $this->activate_pending($deposit['userID']);
        // allocate pending referral 
        $this->allocate_pending_referral($deposit['userID'], $data['amount']);
        }
        // check and send email to user
        if($this->get_settings("send_email_to_user_deposit_".$data['status']) == "yes") {
            $user = $this->getall("users", "ID = ?", [$deposit['userID']]);
            if(!is_array($user)) { return $this->message("Deposit status updated. Email error: user not found", "error", "json");}
            $message = $this->get_email_template("deposit_".$data['status'])['template'];
            // ${amount} ${reason} ${website_url} 
            $message = $this->replace_word(['${first_name}'=>$user['first_name'], '${last_name}'=>$user['last_name'], '${amount}'=>$data['amount'], '${reason}'=>$data['reason'], '${website_url}'=>$this->get_settings("website_url")], $message);
            $sendmessage = $this->smtpmailer($user['email'], "Deposit ".$data['status'], $message);
            if(!$sendmessage) { return $this->message("Deposit status updated. Email error: SMTP issue contact the IT.", "success", "json"); }
        }   

        // return success message
        $return = [
                "message" => ["Success", "Deposit Status Updated", "success"],
            ];
            return json_encode($return);
    }


    protected function allocate_pending_referral($userID, $amount_deposited) {
        // check if any pending referral
        $ref_a = $this->getall("referral_allocation", "userID = ? and status = ?",[$userID, "pending"]);
        if(!is_array($ref_a)) { return true; }
        // get refferal id where referral_code
        $ref = $this->getall("referrals", "referral_code = ? and status = ?", [$ref_a['referral_code'], "active"]);
        if(!is_array($ref)) { return true; }
        // get the referral program
        $ref_p = $this->getall("referral_programs", "ID = ?", [$ref['referralID']]);
        if(!is_array($ref_p)) { return true; }
        // get the percentage ad cal the percentage
        $amount = $this->money_percentage($ref_p['percentage_return_on_deposit'], $amount_deposited);
        // ceridt the percentage into your's balance
        $allocate = $this->credit_debit($ref['userID'], $amount, "balance", for: "referrals", forID: $ref['ID']);
        if($allocate) {
            $this->update("referral_allocation", ["status"=>"allocated", "percentage_amount"=>$amount], "ID = '".$ref_a['ID']."'");
        }else{
            return false;
        }
        // check number of referral allocated if equal or grather than the ref_p no_of_user 
        $no_ref_a = $this->getall("referral_allocation", "referral_code = ? and status = ?", [$ref_a['referral_code'], "allocated"], fetch: "");
        if($no_ref_a < $ref_p['no_of_users']) { return true; }
        $new_invest = ["ID"=>uniqid(), "planID"=>$ref_p['planID'], "userID"=>$ref['userID'], "amount"=>$ref_p['plan_amount'], "trade_amount"=>$ref_p['plan_amount']];
        // create a plan for the user with the planID assigned to the ref_p
        if($this->quick_insert("investment", $new_invest)){
            $update = ["investID"=>$new_invest['ID'], "status"=>"completed"];
            $this->update("referrals", $update, "ID = '".$ref['ID']."'");
        } 
        // 8071953984
        // notify the user.
        $notify = [
            "userID"=>$ref['userID'],
            "n_for"=>"referrals",
            "forID"=>$ref['ID'],
            "url"=>"index?p=referral&action=view&id=".$ref['ID'],
            "title"=>"A reward on your referral.",
            "description"=>"You just earn a reward on your referral program.",
            "time_set"=>time(),
            "date_set"=>date("Y-m-d"),
        ];
        $this->new_notification($notify);
    }

    function admin_transfer($transfer_from) {
        if(!$this->role->validate_action(["users"=>"transfer"])) return ;
        $data = $this->validate_form($transfer_from);
        if(!is_array($data)) { exit(); }
        if(isset($data['session_ID']) && $data['session_ID'] != "") {
            $trans = $this->getall("transactions", "userID = ? and forID = ?", [$data['userID'], $data["session_ID"]]);
            if(is_array($trans)) {
                $amount = $this->money_format($trans['amount']);
                $transType = $trans['action_type'];
                $this->message("Session ID used already by same user, $transType with $amount", "error");
                return;
            }
        }else{
            $data['session_ID'] = "admin";
        } 
        
        $transfer = $this->credit_debit($data['userID'], $data['amount'], $data['action_on'], $data['type'], $data['for'], $data['session_ID']);
        if($transfer) {
             $this->message("Done! You can reload page to see effect.", "success");
             $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), 
                "action_name" => $data['type']." user",
                "description" => $data['type']." ".$data['amount']." on user ".$data['action_on'], 
                "action_for"=>"users", 
                "action_for_ID"=>$data['userID']];
                $this->new_activity($actInfo);
        }
        else {$this->message("OOPS! something went wrong", "error");}
    }
    protected function activate_pending($userID) {
        $invest = $this->getall("investment", "userID = ? and status  =?", [$userID, "pending"], fetch: "moredetails");
        if($invest->rowCount() == 0) {
            return true;
        }
        foreach($invest as $row) {
            $debit = $this->credit_debit($userID, $row['amount'], 'balance', 'debit', for: "Investment");
            if(!$debit) {
                $this->message("Unable to activate this user pending investment of ".$this->money_format($row['amount']), "error");
                continue ;
            }
            $id = $row['ID'];
            $update = $this->update("investment", ["status"=>"active"],  "ID = '$id'");
            if($update) {
                $actInfo = ["userID" => $row['userID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "Investment Activated", "description" => "An investment of ".$this->money_format($row['amount'], currency)." was activated.", "action_for"=>"investment", "action_for_ID"=>$row['ID']];
                $this->new_activity($actInfo);
            }
        }
    }

    function users_data() {
        // amount_invest number_invet profit_percent lost_percent profit_amount lost_amount trade_balance trade_bonus balance 
        $info = [];
        $user = $this->getall("users",  select: "COUNT(*)  as users_no, SUM(balance) as balance");
        $account = $this->getall("account",  select: "COUNT(*)  as no_account_added");
        $no_orders = $this->getall("orders", "ID != ?", [""],  select: "COUNT(*)  as no_orders, SUM(amount) as total_amount_sold");
        $payment = $this->getall("payment", "status = ? or status = ?", ["success", "successful"],  select: "COUNT(*)  as no_of_success_payment, SUM(amount) as total_success_payment");
        $info = array_merge($info, $user, $account, $no_orders, $payment);
        return $info;
    }

    function download_profile() {
        $users = $this->getall("users", "profile_image != ? and acct_type = ?", ["", "bot"], fetch: "moredetails");
        var_dump($users->rowCount());
        if($users->rowCount() > 0) {
            foreach($users as $user) {
                $url = $user['profile_image'];
                $this->upload_image_file($url, $user);
            }
        }
    }

    function upload_image_file($url, $user){
        if(!filter_var($url, FILTER_VALIDATE_URL)){
           return false;
        }
        $image = file_get_contents($url);
        $imageName = strtolower($user['gender'])."-".substr($url, strrpos($url, '/') + 1);
        if(file_put_contents("../assets/images/profile/".$imageName, $image)){
            $id = $user['ID'];
            $this->update("users", ["profile_image"=>$imageName], "ID = '$id'", "$id updated");
            return true;
        }
        return false;
    }


    function make_profile_send_message() {
        $messages = $this->getall("message as m join users as u on m.senderID = u.ID", "u.profile_image = ? and u.acct_type = ? order by m.time_sent DESC", ["", "bot"], "m.ID as ID, m.senderID as senderID", "moredetails");
        if($messages->rowCount() > 0) {
            foreach($messages as $row) {
                $user = $this->getall("users", "profile_image != ? and acct_type = ? ORDER BY RAND()", ["",  "bot"], "ID");
                if(is_array($user)) {
                    $mID = $row['ID'];
                    $this->update("message", ["senderID"=>$user['ID']], "ID = '$mID'", "Message Updated");
                }
            }
        }
    }
    
    function increase_balance($amount, $userID, $what = "balance", $oprator = "add") {
        $user = $this->getall("users", "ID = ?", [$userID], $what);
        if(!is_array($user)) { return false;}
        if($oprator == "add") {
            $amount = (float)$amount + (float)$user[$what];
        }
        if($oprator == "minus") {
            $amount = (float)$user[$what] - (float)$amount;
        }
        $update = $this->update("users", [$what=>$amount], "ID = '$userID'");
        if(!$update) {  return false;}
        return true;
    }
    function user_list($start, $limit = 1, $id = null, $type = "short")
    {
        $users = $this->fetchusers($start, $limit, $id);
        if ($users == "" || $users == null) {
            return null;
        }
        $info = "";
        foreach ($users as $user) {
            match ($type) {
                "short" => $info .= $this->short_user_table($user),
                "details" => $info .= $this->short_user_details($user),
            };
        }
        return $info;
    }

    function update_kyc($data) {
        $update = $this->validate_form($data, "users", "update");
        if($update) { 
            $this->send_email($update['ID'], "KYC ".$update['kyc_status'], "Your kYC verification was ".$update['kyc_status'], ["url"=>ROOT."index?p=profile"]);
            
            return $this->message("KYC status updated", "success", "json"); }
        
    }
    function  short_user_details($user)
    {
        if(!is_array($user) && $user != "") {
            $user = $this->getall("users", "ID = ?", [$user]);
        }
        if(!is_array($user)) {
            return "<b class='text-danger'>User Not Found</b>";
        }
        $profile_link =  $this->get_profile_icon_link($user['ID']);
        return "
        <div class='chat-list chat' id='content".$user['ID']."' data-user-id='" . $user['ID'] . "'>
           <div class='hstack align-items-start mb-7 pb-1 align-items-center justify-content-between'>
               <div class='d-flex align-items-center gap-3'>
                   <img src='$profile_link' alt='user4' width='72' height='72' class='rounded-circle' />
                   <div>
                       <h6 class='fw-semibold fs-4 mb-0'>" . $this->get_full_name($user) . " </h6>
                       <p class='mb-0'>Joined since:" . $user['date'] . " </p>
                   </div>
               </div>
           </div>
           <div class='row'>
               <div class='col-4 mb-7'>
                   <p class='mb-1 fs-2'>Phone number</p>
                   <h6 class='fw-semibold mb-0'>" . $user['phone_number'] . "</h6>
               </div>
               <div class='col-8 mb-7'>
                   <p class='mb-1 fs-2'>Email address</p>
                   <h6 class='fw-semibold mb-0'>" . $user['email'] . "</h6>
               </div>
               <div class='col-12 mb-9'>
                   <p class='mb-1 fs-2'>Address</p>
                   <h6 class='fw-semibold mb-0'>312, Imperical Arc, New western corner</h6>
               </div>
               <div class='col-4 mb-7'>
                   <p class='mb-1 fs-2'>City</p>
                   <h6 class='fw-semibold mb-0'>New York</h6>
               </div>
               <div class='col-8 mb-7'>
                   <p class='mb-1 fs-2'>Country</p>
                   <h6 class='fw-semibold mb-0'>United Stats</h6>
               </div>
           </div>
           <div class='border-bottom pb-7 mb-4'>
               <p class='mb-2 fs-2'>Notes</p>
               <p class='mb-3 text-dark'>
                   Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque bibendum
                   hendrerit lobortis. Nullam ut lacus eros. Sed at luctus urna, eu fermentum diam.
                   In et tristique mauris.
               </p>
               <p class='mb-0 text-dark'>Ut id ornare metus, sed auctor enim. Pellentesque nisi magna, laoreet a augue eget, tempor volutpat diam.</p>
           </div>
           <div class='d-flex align-items-center gap-2'>
               <button class='btn btn-primary fs-2'>Edit</button>
               <button class='btn btn-danger fs-2'>Delete</button>
           </div>
       </div>
           ";
    }
}
