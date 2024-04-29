<?php
class accounts extends Account
{
    function manage_account($account_from, $action = "insert")
    {
       if( $action == "insert") $_POST['ID'] = uniqid();
        $info = $this->validate_form($account_from, "account", $action);
        if (is_array($info) && $action == "insert") return $this->message("Account Created Successfully", "success");
        if (is_array($info) && $action == "update") return $this->message("Account updated Successfully", "success");
    }
}
