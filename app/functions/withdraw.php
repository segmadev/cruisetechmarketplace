<?php
class withdraw extends user
{
    function new_withdraw($data)
    {
        if (!is_array($data)) {
            return false;
        }
        $info = $this->validate_form($data);
        if (!is_array($info)) {
            return null;
        }
        $user = $this->getall("users", "ID = ?", [$info['userID']]);
        if(!is_array($user)) { return false; }
        if($user['kyc_status'] != "approved") { return $this->message("You need to <a class='text-primary' href='index?p=profile'>verify your KYC</a> before you can make a withdrawal", "error");}
        // min and max withraw check
        $min =  $this->get_settings("min_withdraw");
        $max =  $this->get_settings("max_withdraw");
        $send_email = $this->get_settings("send_email_on_user_withdraw");
        if((float)$min > (float)$info['amount']) {
            $this->message("The minimum amount you can withdraw is ".$this->money_format($min), "error");
            return null;
        }
        // return  (float)$info['amount']."max";
        if((float)$max <  (float)$info['amount'] && (float)$max > 0) {
            $this->message("The maximum amount you can withdraw is ".$this->money_format($max), "error");
            return null;
        }

      

        // check if send email if send then send email


        $debit = $this->credit_debit($info['userID'], $info['amount'], "balance", "debit", for:"widthdraw");
        if (!$debit) {
            return false;
        }
        $insert = $this->quick_insert("withdraw", $info);
        // $insert = true;
        if ($insert) {
            $actInfo = ["userID" => $info['userID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "New Withdraw", "description" => "A withdrawal of ".$info['amount']." was made in your account."];
                $this->new_activity($actInfo);
            if($send_email == "yes") {
                $template = $this->get_email_template("default")['template'];
                $email_to = $this->get_settings("support_email");
                $website_url = $this->get_settings("website_url")."/admin/index";
                $title = "You have a new withdrawal request";
                $body = "You have a new withdrawal request from with the amount of ".$this->money_format($info['amount'], currency);
                $body .= "<br> <a href='$website_url' style='padding:  20px 20px; background-color: black; color: white'> Go to Admin</a>";
                $body = $this->replace_word(['${message_here}'=>$body, '${first_name}'=>"Admin."], $template); 
                $sendmessage = $this->smtpmailer($email_to, $title, $body);   
            }

            $return = [
                "message" => ["Sucess", "Success.", "success"],
                "function" => ["loadpage", "data" => ["?p=success&action=withdraw", "success"]],
            ];
            return json_encode($return);
        }
    }
}
