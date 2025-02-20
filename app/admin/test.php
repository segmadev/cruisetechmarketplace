<?php

require_once  "functions/gmail.php";

try {
    $gmail = new GmailClient();
    $emails = $gmail->fetchOpayEmails(5); // Fetch last 5 emails

    foreach ($emails as $index => $details) {
        echo "Transaction #" . ($index + 1) . ":\n";
        print_r($details);
        echo "----------------------------------\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
