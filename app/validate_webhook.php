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
        $newData = "\n" . json_encode($data); 
        // Open the temporary file for writing
        $tempFile = fopen($filename, "w") or die("Unable to open temporary file!");
        // Write the new data to the temporary file
        fwrite($tempFile, $newData);
        // Check if the original file exists
        fclose($tempFile);
        return true;
    }
    function apiMessage($message, int $code = 400, $data = null) {
        header(':', true, $code);
        return json_encode(["code" => $code, "message" => $message, "data" => $data], true);
    }
}
$vaildate = new validate_payment();
echo $vaildate->getAndStoreResult();