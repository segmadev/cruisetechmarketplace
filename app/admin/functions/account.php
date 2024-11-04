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
            $this->message("Account Created Successfully and $count Login Details added", "success");
            $action = "new";
        }
        if (is_array($info) && $action == "update"){
            $count = $this->add_login_info($info['ID']);
            $this->message("Account updated Successfully and $count Login Details added", "success");
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

    function add_login_info($accountID) {
        if(!$this->role->validate_action(["account"=>"addLogins"], true)) return ;
        $count = 0;
        if(!isset($_POST['login_details'])) return $count;
        foreach ($_POST['login_details'] as $key => $value) {
            if($value == "" || $value == " ") continue;
            $value = $this->decodeBase64IfNeeded($value); // Decode Base64 if necessary
            $username = $_POST['username'][$key] ?? "";
            $preview_link = $_POST['preview_link'][$key] ?? "";
            $check = $this->getall("logininfo", "accountID = ? and login_details = ?", [$accountID, $value], fetch: "");
            if($check > 0) continue;
            if($username != ""){
                $check = $this->getall("logininfo", "accountID = ? and username = ?", [$accountID, $username], fetch: "");
                if($check > 0) continue;
            }
            $this->quick_insert("logininfo", [
                "accountID" => $accountID,
                "login_details" => $value,
                "username"=>$username,
                "preview_link"=>$preview_link,
            ]);
            $count++;
        }
        if($count > 0) {
            $actInfo = ["userID" => adminID, "date_time" => date("Y-m-d H:i:s"), "action_name" => "login account", "description" => "Add $count login(s) Account in ".$accountID, "action_for"=>"account", "action_for_ID"=>$accountID];
            $this->new_activity($actInfo);
        }
        
        return $count;
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
}
