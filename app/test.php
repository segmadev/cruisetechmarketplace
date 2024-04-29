<?php
require 'gdrive/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;

function uploadBasic($file_name = "")
{
    try {
        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS=gdrive/credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        // $file = getcwd().'\back.png';
        $file =  "../images/test.MP4";
        $fileName = basename($file);
        $mimeType = mime_content_type($file);
        // $fileName =  htmlspecialchars($_FILES["$file_name"]["name"]);
        // var_dump($fileName);
        // $mimeType = mime_content_type($_FILES["$file_name"]["tmp_name"]);
        if (empty($mimeType)) {
            $mimeType = "application/octet-stream";
        }
        echo $mimeType;

        $fileMetadata = new Drive\DriveFile(array(
            'name' => $fileName,
            'parents' => ['1jlKSnaLGJURubYFM-AfKdFKxPEfBtbfe'],
        ));

        // $content = file_get_contents($_FILES["$file_name"]["tmp_name"]);
        $content = file_get_contents($file);
        // var_dump($file);
        $data = [
            'data' => "$content",
            'mimeType' => "$mimeType",
            'uploadType' => 'multipart',
            'fields' => 'id',
        ];
        // var_dump($data);
        $file = $driveService->files->create($fileMetadata, $data);
        printf("File ID: %s\n", $file->id);
        return $file->id;
    } catch (Exception $e) {
        echo "Error Message: " . $e;
    }
}

function deleteFile($fileID) {
    try {
    $client = new Client();
    putenv('GOOGLE_APPLICATION_CREDENTIALS=gdrive/credentials.json');
    $client->useApplicationDefaultCredentials();
    $client->addScope(Drive::DRIVE);
    $driveService = new Drive($client);
    $driveService->files->delete($fileID);
    return true;
    } catch (Exception $e){ echo $e; return false;}
}

if(isset($_GET['delete'])) {
    deleteFile($_GET['delete']);
}

// if (isset($_POST['upload_file'])) {
//     //    var_dump(file_get_contents($_FILES["upload"]["tmp_name"]));
//     // echo $_FILES["upload"]["tmp_name"];
//     // echo "<br> Name:"; ho $_FILES["upload"]["name"];
//     uploadBasic("upload");
//     // uploadBasic();
// }
?>
<!-- upload a video on the server fist to test if it will work -->
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="upload" id=""> <br>
    <input type="submit" value="Upload" name="upload_file">
</form>

