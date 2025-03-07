<?php
class autorize extends database
{


    public function signin()
    {
        $d = new database;
        if(!isset($_POST['just_token_allow']) || !password_verify( htmlspecialchars($_POST['just_token_allow']), $d->get_settings("jtoken"))){
            $this->message("Access token not passed or incorrect", "error");
            return;
        }
        if(!$this->verify_captcha()) return ;
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        if (empty($email) || empty($password)) {
            return $d->message("Make sure you enter your email and password", "error");
        }
        $value = $d->getall("admins", "email = ?", [$email]);
        if (!is_array($value)) {
            return $d->message("User not found or password incorrect.", "error");
        }
        if (!password_verify($password, $value['password'])) {
            return $d->message("User not found or password incorrect.", "error");
        }
        if (htmlspecialchars($value['status']) == 0) {
            $reason = "";
            if ($value['reason'] != "") {
                $reason = htmlspecialchars($value['reason']);
            }
            return $d->message("We're sorry, your account has been blocked. <br> <b>Reason: </b> " . $reason, "error");
        }
        $urlgoto = "index";
        if (isset($_SESSION['urlgoto'])) {
            $urlgoto = htmlspecialchars($_SESSION['urlgoto']);
            unset($_SESSION['urlgoto']);
        }
        // reson here
        if (!$this->set_token($value)) {
            $this->message("Unable to set token", "error");
            return;
        }
        $actInfo = ["userID" => $value['ID'], "date_time" => date("Y-m-d H:i:s"), "action_name" => "Login", "description" => "Admin Login"];
        $this->new_activity($actInfo);
        // $_SESSION['adminSession'] = ;
        // $d->message("Account logged in Sucessfully <a href='index.php'>Click here to proceed.</a>", "error");
        $return = [
            "message" => ["Success", "Account Logged in", "success"],
            "function" => ["loadpage", "data" => ["$urlgoto", "null"]],
        ];
        return json_encode($return);


    }


    private function set_token(array $user)
    {
        $userID = $user['ID'];
        $token = $this->randcar(rand(20, 40));
        if (!$this->create_table("admins", ["token" => []], isCreate: false))
            return false;
        $where = "ID ='$userID'";
        if (!$this->update("admins", ["token" => $token], $where))
            return false;
        $_SESSION['adminSession'] = $token;

        // set log token
        $token = $this->randcar(rand(20, 40));
        $otpCode = ($user['is_2fa'] == 1) ? rand(100000, 999999) : "";
        $userDetails = $this->get_visitor_details();
        $hashPass = ($otpCode != "") ? password_hash($otpCode, PASSWORD_DEFAULT) : $otpCode;
        $log = [
            "userID"=>$userID,
            "token"=>$token,
            "ip"=>$userDetails['ip_address'],
            "device"=>$userDetails['device'],
            "date_time"=>time(),
            "expiry_date"=>time() + (60 * 60 * 24),
            "otp"=>$hashPass,
        ];
        $this->delete("user_logs", "userID = ? and ip = ?", [$log['userID'], $log['ip']]);
        $this->quick_insert("user_logs", $log);
        if($otpCode != "") {
            $smessage = $this->get_email_template("otp")['template'];
            $smessage = $this->replace_word(['${first_name}' => $user['first_name'], 
            '${otp}' => $otpCode,
            '${ip_address}' => $log['ip'],
            '${device}' => $log['device'],
            '${location}' => $userDetails['state']." ".$userDetails['country'],
        ], $smessage);
            $this->smtpmailer($user['email'], "OTP Verification for Login Attempt on Your Cruise tech log Account", $smessage);
            
        }
        $_SESSION['logTk'] = $token;
        return true;
    }

function verify_otp($adminToken){
    if(!isset($_POST['codeverify']) || empty($_POST['codeverify'])) return $this->message("please enter code and try again", "error");
    $code = htmlspecialchars(trim($_POST['codeverify']));
    $user_log = $this->getall("user_logs", "token = ? and status = ?", [$adminToken, 1], 'token, otp');
    if(!is_array($user_log) || $user_log['token'] == "") return $this->message("User session expired or not found. Go back to login and try again.", "error");
    $token = $this->randcar(rand(20, 40));
    if(!password_verify($code, $user_log['otp'])) return $this->message("Invaild code", "error");
    $this->update("user_logs", ["token"=>$token,"otp"=>""], "token = '$adminToken'");
    $_SESSION['logTk'] = $token;
    $return = [
        "message" => ["Success", "Code verified", "success"],
        "function" => ["loadpage", "data" => ["index", "null"]],
    ];
    return json_encode($return);
    }
} 