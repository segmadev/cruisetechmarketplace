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
if($vaildate->check_transaction()){
    var_dump('YES');
}else{
    var_dump("No");
}
// 7630df8267191722771419