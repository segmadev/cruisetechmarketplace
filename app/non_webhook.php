<?php 
    header("Content-Type: application/json; charset=UTF-8");
    require_once "consts/main.php";
    require_once "admin/include/database.php";
    require_once "functions/rentals.php";
    $r = new rentals;
    $rawData = file_get_contents('php://input');
    // Specify the file path and name
    $filename = 'note.txt';

    // The content you want to write into the file
    $content =  $rawData;

    // Open the file for writing. 'a' mode opens the file for writing and places the file pointer at the end of the file.
    // If the file does not exist, it attempts to create it.
    $file = fopen($filename, 'a');

    // Check if the file was opened successfully
    if ($file) {
        // Write the content to the file
        fwrite($file, $content);
        
        // Close the file to free up resources
        fclose($file);
        
        echo "Content successfully written to the file.";
    } else {
        echo "Unable to open the file.";
    }
    echo $r->nonHandleCallBack();

    