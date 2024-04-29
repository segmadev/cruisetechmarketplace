<?php 
require PATH.'gdrive/vendor/autoload.php';
use Google\Client;
use Google\Service\Drive;
function googleUpload($file, $parent)
{
    try {
        $client = new Client();
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.PATH.'gdrive/credentials.json');
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $driveService = new Drive($client);
        // $file = getcwd().'\back.png';
        // $file =  "../images/test.MP4";
        $fileName = basename($file);
        $mimeType = mime_content_type($file);
        // $fileName =  htmlspecialchars($_FILES["$file_name"]["name"]);
        // var_dump($fileName);
        // $mimeType = mime_content_type($_FILES["$file_name"]["tmp_name"]);
        if (empty($mimeType)) {
            $mimeType = "application/octet-stream";
        }
        $fileMetadata = new Drive\DriveFile(array(
            'name' => $fileName,
            'parents' => [$parent],
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
        return $file->id;
    } catch (Exception $e) {
        echo $e;
        return false ;
    }
}


function deleteFile($fileID) {
    try {
    $client = new Client();
    putenv('GOOGLE_APPLICATION_CREDENTIALS='.PATH.'gdrive/credentials.json');
    $client->useApplicationDefaultCredentials();
    $client->addScope(Drive::DRIVE);
    $driveService = new Drive($client);
    $driveService->files->delete($fileID);
    return true;
    } catch (Exception $e){ echo $e; return false;}
}
?>