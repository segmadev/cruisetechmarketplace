<?php
class autorize extends database
{
  

    public function signin()
    {
        $d = new database;
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        if (!empty($email) && !empty($password)) {
            $value = $d->getall("admins", "email = ?", [$email]);
            if (is_array($value)) {
                if (password_verify($password, $value['password'])) {
                    if (htmlspecialchars($value['status']) == 0) {
                        $reason = "";
                        if ($value['reason'] != "") {
                            $reason = htmlspecialchars($value['reason']);
                        }
                        $d->message("We're sorry, your account has been blocked. <br> <b>Reason: </b> " . $reason, "error");
                    } else {
                        $urlgoto = "index";
                        if (isset($_SESSION['urlgoto'])) {
                            $urlgoto = htmlspecialchars($_SESSION['urlgoto']);
                            unset($_SESSION['urlgoto']);
                        }
                        $urlgoto = "index";
                        
                        // reson here
                       session_unset();
                        session_start();
                        // $d->updateadmintoken($value['ID'], "users");
                        $_SESSION['adminSession'] = htmlspecialchars($value['ID']);
                        
                        
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




}
