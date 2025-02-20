<?php

require '../vendor/autoload.php';

use Google\Client;
use Google\Service\Gmail;

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(rootFile);
$dotenv->load();

class GmailClient {
    private $client;
    private $service;
    private $tokenPath = $_ENV['token_path'] ?? "token.json";
    private $credentialsPath = $_ENV['credentials_path'] ?? "credentials.json";
    public function __construct() {
        $this->client = new Client();
        $this->client->setApplicationName('Gmail API PHP');
        $this->client->setScopes(Gmail::GMAIL_READONLY);
        $this->client->setAuthConfig($this->credentialsPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $this->authenticate();
        $this->service = new Gmail($this->client);
    }

    private function authenticate() {
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                $authUrl = $this->client->createAuthUrl();
                printf("Open this link in your browser:\n%s\n", $authUrl);
                print 'Enter the authorization code: ';
                $authCode = trim(fgets(STDIN));
                $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
                $this->client->setAccessToken($accessToken);
                if (!file_exists(dirname($this->tokenPath))) {
                    mkdir(dirname($this->tokenPath), 0700, true);
                }
                file_put_contents($this->tokenPath, json_encode($accessToken));
            }
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
                $message = $this->service->users_messages->get('me', $msg->getId());
                $emailBody = $this->getEmailBody($message);
                $details = $this->extractDetailsFromHtml($emailBody);
                $emails[] = $details;
            }
        } else {
            echo "No OPay emails found.\n";
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

    private function extractDetailsFromHtml($html) {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $details = [];

        $fields = [
            'amount' => "Amount:",
            'sender' => "Sender:",
            'bank_name' => "Bank Name:",
            'sender_account' => "Sender Account:",
            'transaction_number' => "Transaction number:",
            'session_id' => "Session id:",
            'remark' => "Remark:",
            'receipt_account' => "Receipt account:"
        ];

        foreach ($fields as $key => $label) {
            $node = $xpath->query("//span[contains(text(), '$label')]/following-sibling::span");
            $details[$key] = $node->length > 0 ? trim($node->item(0)->nodeValue) : null;
        }

        return $details;
    }
}
