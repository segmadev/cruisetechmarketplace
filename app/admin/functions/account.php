<?php
require_once "../functions/account.php";
class accounts extends Account
{
    protected $role;
    public function __construct() {
        // Call the parent constructor
        parent::__construct();
        $this->role = new roles;
    }

    function manage_account($account_from, $action = "insert")
    {
       if( $action == "insert") {
            $_POST['ID'] = uniqid();
            if(!$this->role->validate_action(["account"=>"new"], true)) return ;
       }else {
        if(!$this->role->validate_action(["account"=>"edit"], true)) return ;
       }
        $info = $this->validate_form($account_from, "account", $action);
        if (is_array($info) && $action == "insert") {
            $count = $this->add_login_info($info['ID']);
            $this->message("Account Created Successfully and ".$count['count']." Login Details added", "success");
            if(isset($count['message'])  && $count['message']!= "") echo $count['message'];
            $action = "new";
        }
        if (is_array($info) && $action == "update"){
            $count = $this->add_login_info($info['ID']);
            $this->message("Account updated Successfully and ".$count['count']." Login Details added", "success");
           if(isset($count['message'])  && $count['message']!= "") echo $count['message'];
            $action = "edit";
        }
        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), "action_name" => "$action account", "description" => "$action Account on ".htmlspecialchars($_POST['ID']), "action_for"=>"account", "action_for_ID"=>htmlspecialchars($_POST['ID']) ];
        $this->new_activity($actInfo);
    }

    function isBase64($value) {
        // Check if value is a valid base64 string
        if (base64_encode(base64_decode($value, true)) === $value) {
            return true;
        }
        return false;
    }
    
    function decodeBase64IfNeeded($value) {
        // Check if value is Base64, if so, decode it
        if ($this->isBase64($value)) {
            $value = base64_decode($value);
            // Check if the decoded value is already UTF-8
            if (mb_detect_encoding($value, 'UTF-8', true) === false) {
                // Convert the string if not UTF-8
                $value = iconv('ISO-8859-1', 'UTF-8', $value);
            }
        }
        return $value; // Return original value if not Base64
    }


    function add_login_info($accountID, $uploadType = "normal") {
        if (!$this->role->validate_action(["account" => "addLogins"], true)) return;
    
        $count = 0;
        $failedLogins = []; // Store failed logins with reasons
        $notAdded = ""; // Store message for normal mode
        if (!isset($_POST['login_details']) && $uploadType == "normal") return ["count" => 0, "message" => "No logins provided"];
        if (!isset($_POST['login_details']) && $uploadType == "batch")  return json_encode(["count" => 0, "failed_logins" => []]);
    
        foreach ($_POST['login_details'] as $key => $value) {
            if (trim($value) === "") {
                $reason = "Login details are empty";
                if ($uploadType == "batch") {
                    $failedLogins[] = ["index" => $key, "reason" => $reason];
                } else {
                    $notAdded .= "Why?: $reason <p>$value</p><hr>";
                }
                continue;
            }
    
            $value = $this->decodeBase64IfNeeded($value); // Decode Base64 if necessary
            $username = $_POST['username'][$key] ?? "";
            $preview_link = $_POST['preview_link'][$key] ?? "";
    
            // Check for duplicate login details
            $check = $this->getall("logininfo", "accountID = ? and login_details = ?", [$accountID, $value], fetch: "");
            if ($check > 0) {
                $reason = "Login details already exist";
                if ($uploadType == "batch") {
                    $failedLogins[] = ["index" => $key, "reason" => $reason];
                } else {
                    $notAdded .= "Why?: $reason <p>$value</p><hr>";
                }
                continue;
            }
    
            // Check for duplicate username
            if ($username !== "") {
                $check = $this->getall("logininfo", "accountID = ? and username = ?", [$accountID, $username], fetch: "");
                if ($check > 0) {
                    $reason = "Username already exists";
                    if ($uploadType == "batch") {
                        $failedLogins[] = ["index" => $key, "reason" => $reason];
                    } else {
                        $notAdded .= "Why?: $reason <p>$value</p><hr>";
                    }
                    continue;
                }
            }
    
            // Insert new login
            $this->quick_insert("logininfo", [
                "accountID" => $accountID,
                "login_details" => $value,
                "username" => $username,
                "preview_link" => $preview_link,
            ]);
            $count++;
        }
    
        // Log action if any login was added
        if ($count > 0) {
            $actInfo = [
                "userID" => adminID,
                "date_time" => date("Y-m-d H:i:s"),
                "action_name" => "login account",
                "description" => "Added $count login(s) to account " . $accountID,
                "action_for" => "account",
                "action_for_ID" => $accountID
            ];
            // $this->new_activity($actInfo);
        }
    
        // **Return different responses based on the upload type**
        if ($uploadType == "batch") {
            return json_encode(["count" => $count, "failed_logins" => $failedLogins]);
        } else {
            if ($notAdded !== "") {
                $notAdded = "<h5>These logins were not added:</h5> " . $notAdded;
            }
            return ["count" => $count, "message" => $notAdded];
        }
    }
    

    function update_login_info($ID, $value, $accountID, $username, $preview_link) {
        if(!$this->role->validate_action(["account"=>"edit_login"], true)) return ;
        $ID = htmlspecialchars($ID);
        $check = $this->getall("logininfo", "ID = ? and accountID = ? and login_details = ?", [$ID, $accountID, $value]);
        if(is_array($check) && $check['login_details'] != $value) {
           return $this->message("Account with this details already exits for this account", "error");
        }

         $this->update(
            "logininfo",
            [
                "login_details" => $value,
                "username" => $username,
                "preview_link" => $preview_link
            ],
            "ID = '$ID'",
            "Login Details Updated."
        );

        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), "action_name" => "update login", "description" => "Edit login details", "action_for"=>"logininfo", "action_for_ID"=>$ID];
        $this->new_activity($actInfo);
    }

            /**
         * Deletes login records based on provided filters.
         */
        function delete_logins($filters)
        {
            return $this->delete("logininfo", "accountID = ? {$filters['where']}", $filters['data']);
        }


    function delete_login_details($id) {
        if(!$this->role->validate_action(["account"=>"deleteLogins"])) return ;
        if(!$this->validate_admin()) return ;
        $login = $this->getall("logininfo", "ID = ?", [$id]);
        if(!is_array($login)) return ;
        if($login['sold_to'] != "") return $this->message("This account can not be deleted it is sold out.", "error", "json");
        $delete = $this->delete("logininfo", "ID = ?", [$id]);
        if(!$delete) $this->message("Having issue deleting account", "error", "json");
        $return = [
            "message" => ["Success", "Login Details Deleted", "success"],
            "function" => ["removediv", "data" => ["#displaylogin-$id", "null"]]
        ];
        $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), "action_name" => "update login", "description" => "Delete login", "action_for"=>"logininfo", "action_for_ID"=>$id];
        $this->new_activity($actInfo);
        return json_encode($return);
    }
    function delete_logins_in_bulk() {
        if(!$this->role->validate_action(["account"=>"deleteLogins"])) return "<p class='text-danger'>You can not perfrom this action.</p>";
        $filters = $this->build_login_filters();
        // If delete_logins is set, delete matching login records
            if(!isset($_GET['is_sold']) || $_GET['is_sold'] != "no") return "<p class='text-danger'>You can not delete sold account. You can only delete when <span class='text-dark'><b>not sold</b></span> is selected</p>";
            if($this->delete_logins($filters)) return "<p class='text-success'>Account Deleted </p>";
            return "<p class='text-danger'>Something went wrong</p>";
    }
}