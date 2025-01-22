<?php
if (!class_exists('wallets')) {
    require_once "functions/wallets.php";
}
if (side == "admin") {
    require_once '../vendor/autoload.php';
} else {
    require_once 'vendor/autoload.php';
}

use Flutterwave\Helper\Config;
use Flutterwave\Flutterwave;
use Flutterwave\Service\VirtualAccount;

class deposit extends user
{

    function get_payments($userID, $start)
    {
        return $this->getall("payment", "userID = ? order by date DESC LIMIT $start, 10", [$userID], fetch: "moredetails");
    }
    function get_account_details($userID)
    {
        $user = $this->getall("users", "ID =?", [$userID]);
        if (!is_array($user)) return null;
        $account = $this->getall("user_accounts", "userID = ?", [$userID]);
        if (!is_array($account)) return null;
        return $account;
    }

    function create_account_details(array $user)
    {
        if ($this->get_settings("bvn") == "") return false;
        if ($this->getall("user_accounts", "userID = ?", [$user['ID']], fetch: "") > 0) return false;
        $myConfig = Config::setUp(
            $this->get_settings("flutterwave_secret_key"),
            $this->get_settings("flutterwave_public_key"),
            $this->get_settings("flutterwave_encyption_key"),
            'staging'
        );
        Flutterwave::bootstrap($myConfig);
        $service = new VirtualAccount(config: $myConfig);
        $tx_ref = time() . uniqid();
        $payload = [
            "email" => $user['email'],
            "tx_ref" => $tx_ref,
            "bvn" => $this->get_settings("bvn"),
            "narration" => $user['first_name'] . ' ' . $user['last_name'],
            "is_permanent" => true
        ];
        $response = $service->create($payload);
        if (!isset($response->status) || $response->status != "success" || !isset($response->data)) return false;
        $data = (array)$response->data;
        $account = [
            "userID" => $user['ID'],
            "tx_ref" => $tx_ref,
            "order_ref" => $data['order_ref'],
            "account_number" => (int)$data['account_number'],
            "bank_name" => $data['bank_name'],
            "note" => $data['note'],
            "expiry_date" => $data['expiry_date'],
        ];
        if ($this->quick_insert("user_accounts", $account)) {
            return $account;
        }
        return false;
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
        // $pay = $this->verifyPayment($transID);
        // die(var_dump($pay));
        // check if txref is valid is own by userID
        $trans = $this->getall("payment", "userID = ? and tx_ref = ?", [$userID, $txref]);
        if (!is_array($trans)) return false;
        if ($trans['status'] == "successful" || $trans['status'] == "success") {
            // $this->message("Transaction amount already added to balance", "success");
            return false;
        }

        if ($trans['status'] != "" && $trans['status'] != "initiate" && $trans['status'] != "pending" && $trans['status'] != "successful" && $trans['status'] != "success") {
            $this->message("Faild Transaction.", "error");
            return false;
        }
        // verifyPayment 
        $pay = $this->verifyPayment($transID);
        if($pay['payment_type']  == "bank_transfer") {
            return false;
        }
        if($this->getall("transactions", "userID = ? and forID = ?", [$user['ID'], $pay["flw_ref"]], fetch: "") > 0) {
            // echo $this->apiMessage("Value assigned in the past", 401);
            return false;
        }

        // die(var_dump($pay));
        if (!$pay) {
            $this->message("Error verifying payment", "error");
            return false;
        }
        // https://checkout.flutterwave.com/v3/hosted/pay/flwlnk-01jhz6czmmhhmw9kjybbghd18b
        // confrim if the amount match the amount
        if ($pay['status'] != "successful" && $pay['status'] != "success") {
            if(isset($_GET['continue'])) return $this->loadpage($pay['pay_url']);
            $this->message("Payment Faild please try again", "error");
            $this->update("payment", ["transaction_id" => $transID, "status" => $pay['status']], "ID = '" . $trans['ID'] . "'");
            return false;
        }

        // check of txt_ref match 
        // check of userID match $pay['customer']['id']
        $pay = $pay['data'];
        if ($pay['tx_ref'] != $txref || $pay['meta']["consumer_id"] != $userID) {
            $this->message("Payment Faild please try again. <br> Seems the payment do not belong to you. <br> if you think this is an error send an email to: " . $this->get_settings("support_email"), "error");
            return false;
        }
        $amount = $pay['amount'];
        if(isset($pay['flw_ref']) && !empty($pay['flw_ref'])) {
            if ($this->credit_debit($userID, $amount, for: "payment", forID: $pay['flw_ref'])) {
                $this->update("payment", ["transaction_id" => $transID, "amount" => $amount, "status" => $pay['status']], "ID = '" . $trans['ID'] . "'");
                $this->message("Payment successfull", "success");
                $this->quick_insert("assiged_value", ["transID"=>$pay['id'], "userID"=>$userID]);
                return true;
                // credit amount and update the payment status and amount
            }
        }

     
        $this->message("Issue crediting your account. Please try again or contact us" . $this->get_settings("support_email"), "error");
        return true;
    }


    function handle_double() {
        $deposit = $this->getall("transactions", "forID = ", fetch: "ID");
        if (!$deposit) return false;
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
            $data = $this->getall("deposit", "userID = ? and status = ? LIMIT 50", [$userID, $status], fetch: "moredetails");
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