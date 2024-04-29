<?php
if (!class_exists('wallets')) {
    require_once "functions/wallets.php";
}
class deposit extends user
{

    function get_payments($userID, $start) {
        return $this->getall("payment", "userID = ? order by date DESC LIMIT $start, 10", [$userID], fetch: "moredetails");
    }
    function ini_payment($userID)
    {
        if (!isset($_POST['amount']) || (float)$_POST['amount'] <= 0) {
            echo $this->message("Enter a vaild amount", "error");
            return false;
        }

        $amount = (float)htmlspecialchars($_POST['amount']);
        if (!$this->check_min_max_deposit($amount)) return false;
        $user = $this->getall("users", "ID = ?", [$userID]);
        if (!is_array($user)) return false;
        $ch = curl_init();
        $root = $this->get_settings("website_url");
        $data = [
            'tx_ref' => uniqid('tx-ref-'),
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => $root . '/app/index?p=deposit',
            'meta' => [
                'consumer_id' => $user['ID'],
            ],
            'customer' => [
                'email' => $user['email'],
                'phonenumber' => $user['phone_number'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
            ],
            'customizations' => [
                'title' => $user['first_name'] . " Fund Account",
                'logo' => $root . "/app/assets/images/logos/" . $this->get_settings("dark_logo"),
            ]
        ];
        $payment = ["userID" => $userID, "tx_ref" => $data['tx_ref'], "amount" => $amount, "title" => $data['customizations']['title']];
        if (!$this->quick_insert("payment", $payment)) return false;
        $data_string = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->get_settings("flutterwave_secret_key"),
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status != 200) {
            $this->message("We have error making your payment: $response", "error");
            return false;
        }

        $response = json_decode($response, true);
        curl_close($ch);
        if ($response['status'] == "success" && isset($response['data']['link'])) {
            $this->update("payment", ["status" => "pending", "pay_url" => $response['data']['link']], "tx_ref = '" . $data['tx_ref'] . "'");
            $return = [
                "message" => ["Sucess", "Redirecting...", "success"],
                "function" => ["loadpage", "data" => [$response['data']['link'], "success"]],
            ];
            return json_encode($return);
        } else {
            return $this->message("Error: " . $response['message'], "error");
        }
    }

    function verifyPayment($txref)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$txref/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->get_settings("flutterwave_secret_key"),
            ),
        ));
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
            return false;
        }

        curl_close($curl);
        return  json_decode($response, true);
    }

    function validate_payment($txref, $transID, $userID)
    {
        // check if txref is valid is own by userID
        $trans = $this->getall("payment", "userID = ? and tx_ref = ?", [$userID, $txref]);
        if (!is_array($trans)) return false;
        if($trans['status'] == "successful") {
            // $this->message("Transaction amount already added to balance", "success");
            return false;
        }

        if($trans['status'] != "" && $trans['status'] != "initiate" && $trans['status'] != "pending" && $trans['status'] != "successful" && $trans['status'] != "success") {
            $this->message("Faild Transaction.", "error");
            return false;
        }
        // verifyPayment 
        $pay = $this->verifyPayment($transID);
        if(!$pay) {
            $this->message("Error verifying payment", "error");
            return false;
        }
        // confrim if the amount match the amount
        if($pay['status'] != "successful" && $pay['status'] != "success") {
            $this->message("Payment Faild please try again", "error");
            $this->update("payment", ["transaction_id"=>$transID, "status"=>$pay['status']], "ID = '" . $trans['ID'] . "'");
            return false;
        }

        // check of txt_ref match 
        // check of userID match $pay['customer']['id']
        $pay = $pay['data'];
        if($pay['tx_ref'] != $txref || $pay['meta']["consumer_id"] != $userID) {
            $this->message("Payment Faild please try again. <br> Seems the payment do not belong to you. <br> if you think this is an error send an email to: ".$this->get_settings("support_email"), "error");
            return false;
        }
        $amount = $pay['amount'];
        if($this->credit_debit($userID, $amount, "balance",  "credit", "payment", $transID)) {
           $this->update("payment", ["transaction_id"=>$transID, "amount"=>$amount, "status"=>$pay['status']], "ID = '" . $trans['ID'] . "'");
            $this->message("Payment successfull", "success");
            return true;
           // credit amount and update the payment status and amount
        }
        $this->message("Issue crediting your account. Please try again or contact us".$this->get_settings("support_email"), "error");
        return true;
    }

    function check_min_max_deposit($amount)
    {
        $max = (int)$this->get_settings("max_deposit");
        $min = $this->get_settings("min_deposit");
        if ($amount < $min) {
            $this->message("The minimum amount you can deposit is " . $this->money_format($min, currency), "error");
            return false;
        }

        if ($amount > $max && $max > 0) {
            $this->message("The maximum amount you can deposit is " . $this->money_format($max, currency), "error");
            return false;
        }
        return true;
    }

    function new_deposit($data, $userID)
    {
        // $check = $this->getall("deposit", "userID = ? and status = ?", [$userID, "pending"], fetch: "");
        if ($this->get_deposit_max($userID)) {
            return $this->message("You have alot of pending deposit please wait for approval before you can make a new deposit", "error");
        }
        if (!is_array($data)) {
            return null;
        }
        $info = $this->validate_form($data);
        if (!is_array($info)) {
            return false;
        }
        $insert = $this->quick_insert("deposit", $info);
        if ($insert) {
            $actInfo = ["userID" => $userID,  "date_time" => date("Y-m-d H:i:s"), "action_name" => "New Deposit", "description" => "A deposit of " . $this->money_format($info['amount'], currency) . " was made into your account."];
            $this->new_activity($actInfo);
            $return = [
                "message" => ["Sucess", "Deposit Submited for approval", "success"],
                "function" => ["loadpage", "data" => ["?p=deposit&action=list", "success"]],
            ];
            return json_encode($return);
        }
        return $this->message("Error submiting your request", "error");
    }

    function get_total_pending($userID, $status, $data = null)
    {
        if ($data == null) {
            $data = $this->getall("deposit", "userID = ? and status = ?", [$userID, $status], fetch: "moredetails");
        }
        if ($data->rowCount() == 0) {
            return $info = ['data' => [], 'number' => 0, 'total' => 0];
        }
        $info['total'] = 0;
        $info['data'] = $data;
        $info['number'] = $data->rowCount();
        foreach ($data as $row) {
            $info['total'] = $info['total'] + (float)$row['amount'];
        }
        // var_dump($total_amount);
        return $info;
    }
    function get_deposit_max($userID)
    {
        $check = $this->getall("deposit", "userID = ? and status = ?", [$userID, "pending"], fetch: "");
        if ($check > $this->get_settings("deposit_max") || $check == $this->get_settings("deposit_max")) {
            return true;
        }
        return false;
    }
}