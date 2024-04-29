<?php
class autorize extends database
{
    public function signup($data)
    {
        $_POST['ID'] = uniqid();
        $info =  $this->validate_form($data);
        if (!is_array($info)) {
            return null;
        }
        $check = $this->getall("users", "email = ?", [$info['email']]);
        if ($check > 0) {
            echo $this->message("User with email alrady exit.", "error");
            return null;
        }

        $info['password'] = password_hash($info['password'], PASSWORD_DEFAULT);
        unset($info['confrim_password']);
        $info['ip_address'] = $this->get_visitor_details()['ip_address'];
        // check referral code if active.
        // var_dump($info['referral_code']);
        // exit();
        if(!empty($info['referral_code']) && $this->getall("referrals", "referral_code = ? and status = ?", [$info['referral_code'], "active"], fetch:"") == 0){
            $this->message("You referral code is no more active or doesn't exist", "error");
            return false;
        }
        $insert = $this->quick_insert("users", $info);
        if ($insert) {
            if(!empty($info['referral_code'])) {
                $this->apply_referral_code(htmlspecialchars($info['ID']), $info['referral_code']);
            }
            session_start();
            // session_unset();
            $expiry = strtotime('+6 months'); // Calculate the expiry time for 3 months from now
            session_set_cookie_params($expiry); // Set the session cookie expiry time
            // session_start();

            // $d->updateadmintoken($value['ID'], "users");
            $_SESSION['userSession'] = htmlspecialchars($info['ID']);
            if(!$this->set_cookies("userSession", htmlspecialchars($info['ID']), time() + 60 * 60 * 24 * 30)){
                echo $this->message("Your account was created successfuly. But we are having issues logging you in. <a href='login'>Click here</a> to login.", "error");
                return ;
            }
            $actInfo = ["userID" => $info['ID'],  "date_time" => date("Y-m-d H:i:s"), "action_name" => "Registration", "description" => "Account Registration."];
            $this->new_activity($actInfo);
            $return = [
                "message" => ["Success", "Account Created", "success"],
                "function" => ["loadpage", "data" => ["index", "null"]],
            ];
            return json_encode($return);
        }
    }

    private function apply_referral_code($userID, $code) {
        // check if active
        // insert data into DB as pending
      
        // check if active
        if($this->getall("referrals", "referral_code = ? and status = ?", [$code, "active"], fetch: "") == 0) {
            $this->message("Referral code not active anymore", 'error');
            return false;
        }
        $info = ["userID"=>$userID, "referral_code"=>$code];
        if($this->quick_insert("referral_allocation", $info)){
            return true;
        }
       
    }

    private function check_referral_status(){

    }

    function set_cookies($name, $value, $time = null) {
        if($time == null) { $time = time() + 60 * 2;} // current time + 1 hour
        $secureOnly = true; // Set the cookie to be transmitted only over HTTPS
        if(setcookie($name, $value, $time, "/", "", $secureOnly, true)){ return true; }else{ return false; };
    
    }
    public function signin()
    {
        $d = new database;
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        if (!empty($email) && !empty($password)) {
            $value = $d->getall("users", "email = ?", [$email]);
            if (is_array($value)) {
                if (password_verify($password, $value['password'])) {
                    if (htmlspecialchars($value['status']) == 0) {
                        $reason = "";
                        if ($value['reason'] != "") {
                            $reason = htmlspecialchars($value['reason']);
                        }
                        $d->message("We're sorry, your account has been blocked. <br> <b>Reason: </b> " . $reason, "error");
                    } else {
                        // session_start();
                        // $_SESSION['userSession'] = htmlspecialchars($value['ID']);
                        if (isset($_POST['urlgoto'])  && !empty($_POST['urlgoto'])) {
                            $urlgoto = str_replace("/localhost", "", $_POST['urlgoto']);
                        }
                        $urlgoto = "index";
                        // reson here
                        session_start();
                        session_unset();
                        // $d->updateadmintoken($value['ID'], "users");
                        if(!$this->set_cookies("userSession", htmlspecialchars($value['ID']), time() + 60 * 60 * 24 * 30)){
                            echo $this->message("Sorry we are having issues logging you in. Please try again", "error");
                            return ;
                        }
                        $_SESSION['userSession'] = htmlspecialchars($value['ID']);
                        $actInfo = ["userID" => $value['ID'],  "date_time" => date("Y-m-d H:i:s"),"action_name" => "Login", "description" => "Account login access."];
                        $this->new_activity($actInfo);
                        // $d->message("Account logged in Sucessfully <a href='index.php'>Click here to proceed.</a>", "error");
                        $return = [
                            "message" => ["Success", "Account Logged in", "success"],
                            "function" => ["loadpage", "data" => ["$urlgoto", "null"]],
                        ];
                        return json_encode($return);
                    }
                } else {
                    $d->message("Password Incorrect", "error");
                }
            } else {
                $d->message("Email doesn't exist.", "error");
            }
        } else {
            $d->message("Make sure you enter your email and password", "error");
        }
    }



    public function sendotp()
    {
        $d = new database;
        $email = htmlspecialchars($_POST['email']);
        if (isset($_POST['email'])) {
            $checkemail = $d->fastgetwhere("users", "email = ?", $email, "");
            if ($checkemail) {
                $data = $d->fastgetwhere("users", "email = ?", $email, "details");
                $id = $data['ID'];
                $reset =  mt_rand(000000, 99999);
                $hashreset = password_hash($reset, PASSWORD_DEFAULT);
                $where = "ID ='$id'";
                $update = $d->update("users", "", $where, ["reset" => $hashreset]);
                if ($update) {
                    // ($to, $from_name, $subject, $body, $message = '')
                    $sendmail = $d->smtpmailer($to = $data['email'], "no-reply@bestimelive.com", "Password Reset ($reset)", "<h4>Your Password Reset</h4>
                    We have sent you this email in response to your request to reset your password on Bestimelive.
                    <p>Use OTP code below to rest your password <h1>$reset</h> <hr>
        
                    </p><small>Do not reply because this email is not monitored by anyone.</small>", $data['first_name'], "");
                    if ($sendmail) {
                        // $_SESSION['otp'] = "sent";
                        $d->message("OTP sent to $email", "success");
                    }
                } else {
                    $d->message("Error", "error");
                }
            } else {
                $d->message("Email not found by check and try again", "error");
            }
        } else {
            $d->message("Please enter email address", "error");
        }
    }

    function resetpassword()
    {
        $d = new database;
        $email = htmlspecialchars($_POST['email']);
        $value = $d->checkmessage(["OTP", "password", "confirm_password"]);
        if (is_array($value)) {
            $data = $d->getall("users", "email = ?", [$email]);
            if (password_verify($value['OTP'], $data['reset'])) {
                $newpassword = password_hash($value['password'], PASSWORD_DEFAULT);
                $where = "email ='$email'";
                $update = $d->update("users", ["password" => $newpassword, "reset" => ""], $where, message: "Password Reset successfully. You can now <a class='btn btn-default' href='signin.php'>Login here</a> with your new password");
            } else {
                $d->message("Incorrect OTP", "error");
            }
        }
    }
}
