<?php 
header("Content-Type: application/json; charset=UTF-8");
require_once "consts/main.php";
require_once "admin/include/database.php";
class validate_payment extends database {

    function getAndStoreResult() {
        // Retrieve the raw POST data
        $jsonData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($jsonData, true);
        $data = (array)$data;
        $data2 = $_POST;
        // Check if decoding was successful
        try {
            $this->storeResult($data);
            $this->storeResult($data2);
           return $this->apiMessage("Successfully", 200);
        } catch (\Throwable $th) {
            return $this->apiMessage($th);
        }
    }

   
    function storeResult($data) {
        $filename = "results.db";
        $newData = "\n new data" . json_encode($data); 
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
echo $vaildate->getAndStoreResult();
// 7630df8267191722771419