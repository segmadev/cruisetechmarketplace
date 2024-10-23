<?php
session_start(); // Start the session
header("Content-Type: application/json; charset=UTF-8");
// Include your existing database connection.
require_once "consts/main.php";
require_once "admin/include/database.php";
class backup extends database {
    private $dropboxToken;
    private $backupLogFile = 'last_backup.log';
        // backupdatabse
        public function __construct() 
        { 
           parent::__construct();
           $this->dropboxToken = $this->get_settings("dropbox_API"); 
           
        } 
        public function exportLargeDatabase($chunkSize = 1000) {
            // Check last backup time
            if (!$this->isBackupAllowed()) {
                echo json_encode(["status" => "error", "message" => "Backup is not allowed yet. Please wait."]);
                return false;
            }
    
            // Initialize progress tracking
            $progressFile = 'progress.txt';
            $progressData = [
                'total_tables' => 0,
                'completed_tables' => 0,
                'last_table' => null,
            ];
    
            // Check for existing progress file
            if (file_exists($progressFile)) {
                $progressData = json_decode(file_get_contents($progressFile), true);
            }
    
            $backupFile = 'backup_file_' . date('Y-m-d_H-i-s') . '.sql';  // Dynamic filename with timestamp
            $fileHandle = fopen($backupFile, 'w');
            if (!$fileHandle) {
                throw new Exception("Failed to open backup file for writing.");
            }
    
            fwrite($fileHandle, "-- Database Export\n");
            fwrite($fileHandle, "-- Exported on " . date('Y-m-d H:i:s') . "\n\n");
    
            // Retrieve all tables in the database.
            $tables = $this->db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            $progressData['total_tables'] = count($tables);
    
            // Start from the last completed table
            $startIndex = array_search($progressData['last_table'], $tables) + 1;
    
            for ($i = $startIndex; $i < $progressData['total_tables']; $i++) {
                $table = $tables[$i];
                $progressData['last_table'] = $table; // Update last completed table
    
                // Export the CREATE TABLE structure.
                $createTableStmt = $this->db->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC)['Create Table'] . ";\n\n";
                fwrite($fileHandle, $createTableStmt);
    
                // Count total rows in the table to manage large data.
                $rowCount = $this->db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    
                if ($rowCount > 0) {
                    for ($offset = 0; $offset < $rowCount; $offset += $chunkSize) {
                        // Fetch data in chunks to prevent memory exhaustion.
                        $rows = $this->db->query("SELECT * FROM `$table` LIMIT $offset, $chunkSize")->fetchAll(PDO::FETCH_ASSOC);
    
                        // Write INSERT statements for each chunk.
                        foreach ($rows as $row) {
                            // Handle null values and escape others
                            $values = array_map(function ($value) {
                                return $value === null ? 'NULL' : $this->db->quote($value);
                            }, $row);
                            $insertStmt = "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                            fwrite($fileHandle, $insertStmt);
                        }
                    }
                }
    
                $progressData['completed_tables']++;
                // Save progress after each table
                file_put_contents($progressFile, json_encode($progressData));
            }
    
            fclose($fileHandle);  // Close the backup file.
    
            // Check and delete the previous Dropbox backup
            $this->deleteLastDropboxBackup();
    
            // Now upload the backup file to Dropbox
            if ($this->uploadToDropbox($backupFile, $this->dropboxToken)) {
                echo json_encode(["status" => "success", "message" => "Database export and upload to Dropbox completed successfully."]);
                $this->updateLastBackupTime(); // Update the last backup time
            } else {
                echo json_encode(["status" => "error", "message" => "Database export completed, but upload to Dropbox failed."]);
            }
    
            // Clear progress and backup files after successful completion
            unlink($progressFile);
            unlink($backupFile);
            return true;
        }
    
        private function isBackupAllowed() {
            if (file_exists($this->backupLogFile)) {
                $lastBackupTime = file_get_contents($this->backupLogFile);
                if(trim($lastBackupTime) == "") return true;
                $interval = (int)$this->get_settings("backup_interval") ?? 6;
                if($interval == 0) $interval = 6;
                return (time() - $lastBackupTime) >= (3600 *  $interval); // 12 hours in seconds
            }
            return true; // No previous backup, allow it
        }
    
        private function updateLastBackupTime() {
            file_put_contents($this->backupLogFile, time());
        }
    
        private function deleteLastDropboxBackup() {
            $previousBackupFile = $this->getLastDropboxBackup();
            if ($previousBackupFile) {
                $this->deleteFileFromDropbox($previousBackupFile);
            }
        }
    
        private function getLastDropboxBackup() {
            // Check Dropbox for the last uploaded backup file
            $url = 'https://api.dropboxapi.com/2/files/list_folder';
            $headers = [
                'Authorization: Bearer ' . $this->dropboxToken,
                'Content-Type: application/json',
            ];
            $data = json_encode(["path" => "", "recursive" => false]);
    
            $response = $this->sendCurlRequest($url, $this->dropboxToken, $headers, $data);
            if ($response) {
                $responseData = json_decode($response);
                if (isset($responseData->entries) && count($responseData->entries) > 0) {
                    foreach ($responseData->entries as $entry) {
                        // Return the most recent backup file
                        if (strpos($entry->name, 'backup_file_') === 0) {
                            return $entry->name;
                        }
                    }
                }
            }
            return null;
        }
    
        private function deleteFileFromDropbox($fileName) {
            $url = 'https://api.dropboxapi.com/2/files/delete_v2';
            $headers = [
                'Authorization: Bearer ' . $this->dropboxToken,
                'Content-Type: application/json',
            ];
            $data = json_encode(["path" => '/' . $fileName]);
    
            $this->sendCurlRequest($url, $this->dropboxToken, $headers, $data);
        }
    
        private function uploadToDropbox($file, $token) {
            $chunkSize = 4 * 1024 * 1024; // 4MB chunk size
            $fileSize = filesize($file);
            $fileHandle = fopen($file, 'rb');
    
            // Start the upload session
            $sessionId = $this->startUploadSession($token);
            if (!$sessionId) {
                return false;
            }
    
            // Upload file in chunks
            $offset = 0;
            while ($offset < $fileSize) {
                $chunk = fread($fileHandle, $chunkSize);
                if (!$this->uploadChunk($token, $sessionId, $chunk, $offset)) {
                    fclose($fileHandle);
                    return false;
                }
                $offset += strlen($chunk);
            }
    
            fclose($fileHandle);
    
            // Commit the upload session
            return $this->finishUploadSession($token, $sessionId, $fileSize, basename($file));
        }
    
        private function sendCurlRequest($url, $token, $headers, $postFields = null) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if ($postFields !== null) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            // Set timeouts
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Maximum time in seconds for the whole request
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // Maximum time in seconds to wait for a connection
    
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
    
            if ($httpCode != 200) {
                echo "cURL error (code $httpCode): $response\n";  // Debug output
                return false;
            }
    
            return $response;
        }
    
        private function startUploadSession($token) {
            $url = 'https://content.dropboxapi.com/2/files/upload_session/start';
            $headers = [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/octet-stream',
            ];
    
            $response = $this->sendCurlRequest($url, $token, $headers, '');
            
            if ($response) {
                $responseData = json_decode($response);
                if (isset($responseData->session_id)) {
                    return $responseData->session_id; // Return session_id
                } else {
                    echo "Error: 'session_id' not found in response: $response\n";
                }
            }
            return false;
        }
    
        private function uploadChunk($token, $sessionId, $chunk, $offset) {
            $url = 'https://content.dropboxapi.com/2/files/upload_session/append_v2';
            $headers = [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/octet-stream',
                'Dropbox-API-Arg: ' . json_encode([
                    "cursor" => [
                        "session_id" => $sessionId,
                        "offset" => $offset,
                    ],
                    "close" => false,
                ]),
            ];
    
            return $this->sendCurlRequest($url, $token, $headers, $chunk);
        }
    
        private function finishUploadSession($token, $sessionId, $fileSize, $fileName) {
            $url = 'https://content.dropboxapi.com/2/files/upload_session/finish';
            $headers = [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/octet-stream',
                'Dropbox-API-Arg: ' . json_encode([
                    "cursor" => [
                        "session_id" => $sessionId,
                        "offset" => $fileSize,
                    ],
                    "commit" => [
                        "path" => '/' . $fileName,
                        "mode" => "add",
                        "autorename" => true,
                        "mute" => false,
                    ],
                ]),
            ];
    
            return $this->sendCurlRequest($url, $token, $headers);
        }
    
        // backup end
}

$backup = new backup;
// Dropbox API token
$dropboxToken = '';
// Set the backup file name with timestamp
$backup->exportLargeDatabase();