<?php
define("PATH", "");
require '../vendor/autoload.php';
require_once '../consts/main.php';
require_once 'include/database.php';
require_once '../functions/users.php';
use Google\Client;
use Google\Service\Gmail;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable("../");
$dotenv->load();
error_reporting(E_ALL & ~E_DEPRECATED);
class GmailClient extends user{
    private $client;
    private $service;
    private $tokenPath;
    private $credentialsPath;
    private $user;
    public function __construct() {
        parent::__construct();
        // $this-> = new user;
        $opayPayment = [
            "ID"=>[],
            "sessionID"=>[],
            "transactionID"=>[],
            "remark"=>[],
            "sender_name"=>[],
            "sender_account"=>[],
            "sender_bank"=>[],
            "amount"=>[],
            "transaction_date"=>[],
            "status"=>[],
        ];

        
        $awatingPayment = [
            "ID"=>[],
            "userID"=>[],
            "sessionID"=>[],
            "remark"=>[]
        ];
        $this->create_table("awatingPayment", $awatingPayment);
        $this->create_table("opaypayment", $opayPayment);
        $this->tokenPath = $_ENV['token_path'] ?? "token.json";
        $this->credentialsPath = $_ENV['credentials_path'] ?? "credentials.json";
        $this->client = new Client();
        $this->client->setApplicationName('Gmail API PHP');
        $this->client->setScopes(Gmail::GMAIL_READONLY);
        $this->client->setAuthConfig($this->credentialsPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        $this->client->setRedirectUri($this->getRedirectUri());
        $this->authenticate();
        $this->service = new Gmail($this->client);
    }

    private function getRedirectUri() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        return str_replace(".php", "", $url);
    }

    private function authenticate() {
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }
    
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->checkAndRefreshToken(); // 🔄 Auto-refresh token if expired
            } elseif (isset($_GET['code'])) {
                // First-time authentication
                $authCode = $_GET['code'];
                $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
                $this->client->setAccessToken($accessToken);
    
                if (!file_exists(dirname($this->tokenPath))) {
                    mkdir(dirname($this->tokenPath), 0700, true);
                }
                file_put_contents($this->tokenPath, json_encode($accessToken));
    
                // Redirect to clear the code from URL
                header('Location: ' . $this->getRedirectUri());
                exit();
            } else {
                // Start OAuth flow if no token exists
                $authUrl = $this->client->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit();
            }
        }
    }

  

/**
 * ✅ Update last fetched timestamp (stored in a file)
 */
private function updateLastFetchedTimestamp($timestamp) {
    file_put_contents('last_fetched_timestamp.txt', $timestamp);
}

/**
 * ✅ Get the last fetched timestamp
 *
 * @return int Unix timestamp of last fetched message
 */
private function getLastFetchedTimestamp() {
    return file_exists('last_fetched_timestamp.txt') 
        ? (int)file_get_contents('last_fetched_timestamp.txt') 
        : 0;
}


/**
 * ✅ Fetch OPay emails from the last fetched timestamp and continuously listen for new ones
 *
 * @param int $maxResults Number of emails to fetch per API call
 * @return array List of new and existing emails since the last fetch
 */
public function fetchAndListenForOpayEmails($maxResults = 10) {
    $emails = [];

    // 🕒 Get the last fetched timestamp from file
    $lastFetched = $this->getLastFetchedTimestamp();

    // Default to 30 days ago if no timestamp exists
    if ($lastFetched === 0) {
        $lastFetched = strtotime('-30 days');
    }

    // Gmail search query: fetch messages after the last fetched timestamp
    $query = 'from:no-reply@opay-nigeria.com after:' . $lastFetched;

    // 🔄 Fetch messages using Gmail API
    $messages = $this->service->users_messages->listUsersMessages('me', [
        'q' => $query,
        'maxResults' => $maxResults
    ]);

    if ($messages->getMessages()) {
        foreach ($messages->getMessages() as $msg) {
            $messageId = $msg->getId();
            $message = $this->service->users_messages->get('me', $messageId);
            $emailBody = $this->getEmailBody($message);

            // Extract details and include Message ID
            $details = $this->extractDetailsFromHtml($emailBody, $messageId);
            $details['timestamp'] = $message->getInternalDate() / 1000; // Gmail returns microseconds

            $emails[] = $details;

            // 📝 Update last fetched timestamp after each message
            $this->updateLastFetchedTimestamp($details['timestamp']);
        }
    } else {
        echo "✅ No new OPay emails found.<br>";
    }

    return $emails;
}


    /**
 * ✅ Check if the token is expired and refresh it if needed
    */
    public function checkAndRefreshToken() {
        if ($this->client->isAccessTokenExpired()) {
            echo "🔄 Access token expired. Refreshing...\n";

            if ($this->client->getRefreshToken()) {
                // Refresh the access token using the refresh token
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());

                if (!isset($newToken['error'])) {
                    // Save the new access token to token.json
                    file_put_contents($this->tokenPath, json_encode($this->client->getAccessToken()));
                    echo "✅ Access token refreshed successfully.\n";
                } else {
                    echo "❌ Error refreshing token: " . $newToken['error_description'] . "\n";
                }
            } else {
                echo "⚠️ No refresh token available. Please re-authenticate.\n";
                $this->authenticate(); // Re-run the auth flow if no refresh token exists
            }
        } else {
            echo "✅ Access token is still valid.\n";
        }
    }


    public function fetchOpayEmails($maxResults = 10) {
        $emails = [];
        $query = 'from:no-reply@opay-nigeria.com';
    
        $messages = $this->service->users_messages->listUsersMessages('me', [
            'q' => $query,
            'maxResults' => $maxResults
        ]);
    
        if ($messages->getMessages()) {
            foreach ($messages->getMessages() as $msg) {
                $messageId = $msg->getId(); // ✅ Extract Gmail Message ID
                $message = $this->service->users_messages->get('me', $messageId);
                $emailBody = $this->getEmailBody($message);
    
                // ✅ Pass the Message ID to extractDetailsFromHtml
                $details = $this->extractDetailsFromHtml($emailBody, $messageId);
    
                $emails[] = $details;
            }
        } else {
            echo "No OPay emails found.<br>";
        }
    
        return $emails;
    }
    

    private function getEmailBody($message) {
        $parts = $message->getPayload()->getParts();
        foreach ($parts as $part) {
            if ($part->getMimeType() === 'text/html') {
                return base64_decode(strtr($part->getBody()->getData(), '-_', '+/'));
            }
        }
        return '';
    }
/**
 * Extracts transaction details from the email HTML and includes the Message ID
 *
 * @param string $html The raw HTML content of the email
 * @param string $messageId The Gmail Message ID
 * @return array Extracted details with the Message ID
 */
private function extractDetailsFromHtml($html, $messageId) {
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);
    $details = [];

    // Mapping fields for both formats
    $fields = [
        'sessionID' => ["Session id:"],
        'transactionID' => ["Transaction number:"],
        'remark' => ["Remark:"],
        'sender_name' => ["Sender:"],
        'sender_account' => ["Sender Account:"],
        'sender_bank' => ["Bank Name:"],
        'amount' => ["Amount:", "₦"],
        'transaction_date' => ["Transaction Date:"]
    ];


    foreach ($fields as $key => $labels) {
        foreach ($labels as $label) {
            $query = "//span[contains(text(), '$label')]/following-sibling::em/span";
            // $query .= " | //td[contains(text(), '$label')]/following-sibling::td | //td[contains(text(), '$label')]/em/span";
            $nodes = $xpath->query($query);
            $details['ID'] = $messageId;
            $details[$key] = "";
            if ($nodes->length > 0) { 
                $value = trim($nodes->item(0)->nodeValue);

                // Clean amount fields
                if (in_array($key, ['amount', 'available_balance'])) {
                    $value = $this->cleanAmount($value);
                }

                $details[$key] = $value;
                break;
            }
        }
    }
    return $details;
}

    /**
     * Cleans amount by removing currency symbols and commas.
     * E.g., ₦14,000.00 → 14000.00
     */
    private function cleanAmount($amount) {
        // Remove ₦, commas, and whitespace
        $cleaned = preg_replace('/[₦,\s]/', '', $amount);
        return is_numeric($cleaned) ? number_format((float)$cleaned, 2, '.', '') : $cleaned;
    }


    public function verifyAmountByMessageId($messageId, $expectedAmount) {
        try {
            $message = $this->service->users_messages->get('me', $messageId);
            $emailBody = $this->getEmailBody($message);
            $details = $this->extractDetailsFromHtml($emailBody, $messageId);
    
            $actualAmount = $details['amount'] ?? null;
            $expectedAmount = $this->cleanAmount($expectedAmount);
    
            if ($actualAmount === $expectedAmount) {
                return [
                    'status' => 'success',
                    'message' => "Amount verified successfully.",
                    'details' => $details
                ];
            } else {
                return [
                    'status' => 'failed',
                    'message' => "Amount mismatch. Expected: $expectedAmount, Found: $actualAmount",
                    'details' => $details
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => "Error verifying amount: " . $e->getMessage()
            ];
        }
    }

   
    function getSaveOpay() {
        $this->checkAndRefreshToken();
        $emails = $this->fetchAndListenForOpayEmails(5);
        if(!$emails) return ;
        foreach($emails as $email) {
            if($email['sessionID'] == "" && $email['transactionID'] == "") continue;
            if($this->getall("opaypayment", "sessionID = ? or transactionID = ?", [$email['sessionID'], $email['transactionID']], fetch: "") > 0) continue;
            if(!$this->quick_insert("opaypayment", $email)) continue;
            // check for wainting payment that matches transaction or session ID
            $awaiting = $this->getall("awatingPayment", "sessionID = ? or sessionID = ?", [$email['sessionID'], $email['transactionID']]);
            if(is_array($awaiting)) continue;
            if(!is_array($this->getall("transactions", "forID = ? or forID = ?", [$awaiting['userID'], $email["sessionID"]]))) {
                $this->credit_debit($awaiting['userID'], $email['amount'],  for: "opaypayment", forID: $awaiting['sessionID']);
            }
            $this->message("New payment added", "success");
            $this->dispose_payment($email['ID'], $awaiting['ID']);    
        }
    }
}
