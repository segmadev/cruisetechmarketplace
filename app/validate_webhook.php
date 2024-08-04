<?php 
require_once "admin/include/database.php";
class validate_payment extends database {

    function getAndStoreResult() {
        // Retrieve the raw POST data
        $jsonData = file_get_contents('php://input');
        // Decode the JSON data into a PHP associative array
        $data = json_decode($jsonData, true);
        $data = (array)$data['data'];
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
        $tempFilename = "temp_datainfo.db";
        $newData = "\n" . json_encode($data); 
        // Open the temporary file for writing
        $tempFile = fopen($tempFilename, "w") or die("Unable to open temporary file!");
        // Write the new data to the temporary file
        fwrite($tempFile, $newData);
        // Check if the original file exists
        if (!file_exists($filename)) { 
            $originalFile = fopen($filename, "w");
            fclose($originalFile);
        }
        // Open the original file for reading
        $originalFile = fopen($filename, "r") or die("Unable to open original file!");
        // Append the content of the original file to the temporary file
        while (!feof($originalFile)) {
            $line = fgets($originalFile);
            if ($line !== false) {
                fwrite($tempFile, $line);
            }
        }   
        // Close the original file
        fclose($originalFile);
        // Close the temporary file
        fclose($tempFile);
        // Replace the original file with the temporary file
        rename($tempFilename, $filename) or die("Unable to rename temporary file!");        
    }
    function apiMessage($message, int $code = 400, $data = null) {
        header(':', true, $code);
        return json_encode(["code" => $code, "message" => $message, "data" => $data], true);
    }
}
    
?>