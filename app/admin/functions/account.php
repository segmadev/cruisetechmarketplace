<?php
require_once "../functions/account.php";
class accounts extends Account
{
    function manage_account($account_from, $action = "insert")
    {
       if( $action == "insert") $_POST['ID'] = uniqid();
        $info = $this->validate_form($account_from, "account", $action);
        if (is_array($info) && $action == "insert") {
            $count = $this->add_login_info($info['ID']);
            return $this->message("Account Created Successfully and $count Login Details added", "success");
        }
        if (is_array($info) && $action == "update"){
            $count = $this->add_login_info($info['ID']);
            return $this->message("Account updated Successfully and $count Login Details added", "success");
        }
    }

    function add_login_info($accountID) {
        $count = 0;
        if(!isset($_POST['login_details'])) return $count;
        foreach ($_POST['login_details'] as $key => $value) {
            if($value == "" || $value == " ") continue;
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

        return $count;
    }

    function update_login_info($ID, $value, $accountID, $username, $preview_link) {
        $ID = htmlspecialchars($ID);
        $check = $this->getall("logininfo", "ID = ? and accountID = ? and login_details", [$ID, $accountID, $value]);
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
    }

    function delete_login_details($id) {
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
        return json_encode($return);
    }
}
