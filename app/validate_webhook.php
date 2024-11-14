<?php 
header("Content-Type: application/json; charset=UTF-8");
require_once "include/database.php";
class validate_payment extends database {

    function check_transaction() {
        $secretHash = $this->get_settings('secret_hash');
        // $signature = $_SERVER['HTTP_VERIF_HASH'] ?? null;
        // // Verify the signature
        // if (!$signature || $signature !== $secretHash) {
        //  $this->storeResult(["Header"=>$_SERVER]);
        //  return $this->apiMessage("Unauthorized", 401);
        // }
        // Retrieve the raw POST data
        $jsonData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($jsonData, true);
        $data = (array)$data;
        $this->storeResult($data);
        if(isset($data['data']) && $data['data'] != null) {
            $data = (array)$data['data'];
            if($data['payment_type'] != "bank_transfer" || $data['status'] != "successful") return false;
            if($this->getall("assiged_value", "transID = ? and is_assiged = ?", [$data['id'], 1], fetch: "") > 0) {
                $this->apiMessage("Value assigned in the past", 401);
                return false;
            }
            $customer = (array)$data['customer'];
            $user = $this->getall("users", "email = ?", [$customer['email']]);
            if(!is_array($user)) return false;
            if(!$this->credit_debit($user['ID'], $data['amount'], for: "transfer_payment", forID: $data["flw_ref"])) return false;
            $this->quick_insert("assiged_value", ["transID"=>$data['id'], "userID"=>$user['ID']]);
            $this->apiMessage("Successfull");
            return true;
        }
        return false;
        // Check if decoding was successful
        
    }

    function outstandingtrans() {
        $out = $this->getall("outstandingtransactions", "outStatus = ?", [0], fetch:"all");
        if($out == "") { return $this->apiMessage("All done"); }
        foreach($out as $o) {
                $check = $this->getall("transactions", "forID = ? and trans_for = ? and action_type = ?", [$o['forID'], $o['trans_for'], $o['action_type']]);
                if(is_array($check)) {
                    $this->update("outstandingtransactions", ["outStatus"=>2], "ID = '".$o['ID']."'");
                    $this->message("Was done for ".$o['ID'], "error");
                    continue;
                }
            if($this->credit_debit($o['userID'], $o['amount'], "balance", $o['action_type'], $o['trans_for'], $o['forID'])) {
                $this->update("outstandingtransactions", ["outStatus"=>1], "ID = '".$o['ID']."'");
                $this->message("Done for ".$o['ID'], "success");
            }
        }
    }

    function outstandingorders() {
        $orders =  $this->getall("outstandingorders", "outStatus = ?", [0], fetch: "all");
        foreach ($orders as $order) {
            var_dump($order);
            exit();
            if(is_array($this->getall("orders", "ID = ?", [$order['ID']]))) {
                $this->update("outstandingorders", ["outStatus"=>2], "ID = '".$order['ID']."'");
                $this->message("order already created", "error");
                continue;
            }

            if($order['order_type'] == "account") {
                $logins = explode(',', $order['loginIDs']);
                if(is_array($logins) && count($logins) > 0) {
                    foreach ($logins as $login) {
                        $checkLogin = $this->getall("logininfo", "ID = ?", [$login]);
                        if(is_array($checkLogin)){
                            if($checkLogin['sold_to'] != null && $checkLogin['sold_to'] != "" && $checkLogin['sold_to'] != $order['userID']) {
                                $this->message($login." Login already sold to ".$checkLogin['sold_to'], "error");
                                $this->update("outstandingorders", ["outStatus"=>3], "ID = '".$order['ID']."'");
                                continue;
                            }
                            $this->update("logininfo", ["sold_to"=>$order['userID']], "ID = '".$checkLogin['ID']."'");
                        }
                    }
                } 
            }
            unset($order['outStatus']);
            $data = [
                "ID"=>$order['ID'],
                "userID"=>$order['userID'],
                "accountID"=>$order['accountID'],
                "serviceCode"=>$order['serviceCode'],
                "serviceName"=>$order['serviceName'],
                "otpCode"=>$order['otpCode'],
                "loginIDs"=>$order['loginIDs'],
                "amount"=>$order['amount'],
                "cost_amount"=>$order['cost_amount'],
                "no_of_orders"=>$order['no_of_orders'],
                "order_type"=>$order['order_type'],
                "type"=>$order['type'],
                "broker_name"=>$order['broker_name'],
                "country"=>$order['country'],
                "expiration"=>$order['expiration'],
                "expire_date"=>$order['expire_date'],
                "activate_expire_date"=>$order['activate_expire_date'],
                "status"=>$order['status'],
                "date"=>$order['date']
            ];
            $this->quick_insert("orders", $data, "New order created");
            $this->update("outstandingorders", ["outStatus"=>1], "ID = '".$order['ID']."'");
        }
    }

   
    function storeResult($data) {
        $filename = "results.db";
        $newData = "\n " . json_encode($data); 
        // Open the file in append mode for writing
        $file = fopen($filename, "a") or die("Unable to open file!");
        // Write the new data to the file
        fwrite($file, $newData);
        // Close the file
        fclose($file);
        return true;
    }
    function apiMessage($message, int $code = 400, $data = null) {
        header(':', true, $code);
        return json_encode(["code" => $code, "message" => $message, "data" => $data], true);
    }
}
$vaildate = new validate_payment();
$vaildate->outstandingtrans();
// if($vaildate->check_transaction()){
//     var_dump('YES');
// }else{
//     var_dump("No");
// }
// 7630df8267191722771419