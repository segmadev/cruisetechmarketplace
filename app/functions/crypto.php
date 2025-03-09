<?php
class CryptomusService extends database {
    private $merchantUuid;
    private $apiKey;
    private $baseUrl = 'https://api.cryptomus.com/v1/';

    public function __construct($merchantUuid, $apiKey) {
        parent::__construct();
        $this->merchantUuid = $merchantUuid;
        $this->apiKey = $apiKey;
    }

    public function createPaymentWallet($userId, $currency = 'USDT') {
        // Build a unique order ID. This order_id must be unique within your system.
        $orderId = 'WALLET_' . $userId . '_' . time();

        // Prepare the data payload.
        $data = [
            'order_id'   => $orderId,
            'currency'   => strtoupper($currency),
            // For USDT, use 'tron' as network per documentation; for BTC, use 'bitcoin'
            'network'    => (strtoupper($currency) === 'USDT') ? 'bsc' : 'bitcoin',
            // Optional: include a callback URL to receive webhook notifications.
            'url_callback' => 'https://cruisetechlogs.com/app/crypto_validate?order_id='.$orderId
        ];

        // Compute the sign.
        // Note: The method for sign generation (MD5 of base64-encoded JSON + apiKey) must match Cryptomus requirements.
        $sign = md5(base64_encode(json_encode($data)) . $this->apiKey);

        // Prepare headers:
        // - 'merchant' header with your merchant UUID.
        // - 'sign' header with the computed signature.
        // - 'Content-Type' header for JSON.
        $headers = [
            'Content-Type: application/json',
            'merchant: ' . $this->merchantUuid,
            'sign: ' . $sign
        ];

        // Use the globally available api_call() function.
        $response = $this->api_call(
            $this->baseUrl . 'wallet',
            json_encode($data),
            $headers,
            false,
            'POST'
        );
        // var_dump($response);

        
        // Check response.
        if (!$response || !isset($response->result)) {
            error_log("Cryptomus API Error: " . print_r($response, true));
            return false;
        }
        
        // Return the response as an array for compatibility.
        $return =  json_decode(json_encode($response), true);
        return $return['result'];
    }

  
    /**
 * Verifies the authenticity of a Cryptomus webhook and logs debug information.
 *
 * This method:
 * 1. Reads the raw JSON payload.
 * 2. Logs the raw payload into payload.txt.
 * 3. Extracts the "sign" field from the payload and removes it.
 * 4. Re-encodes the remaining data (using JSON_UNESCAPED_UNICODE),
 *    base64-encodes it, concatenates with your API key, and computes the MD5 hash.
 * 5. Compares the computed hash with the received sign.
 * 6. If $debugMode is true, prints errors and debug messages on screen.
 *
 * @return bool True if the webhook is verified; false otherwise.
 */
public function verifyWebhook($debugMode = false) {
    // Set debug mode. Change to false to disable printing errors to the screen.
    // Get the raw POST payload.
    $rawPayload = file_get_contents("php://input");

    // Log the raw payload.
    file_put_contents("payload.txt", "Raw Payload:\n" . $rawPayload . "\n", FILE_APPEND);
    if ($debugMode) {
        echo "Raw Payload:\n" . htmlspecialchars($rawPayload) . "\n";
    }

    $data = json_decode($rawPayload, true);
    if (!$data) {
        $errorMsg = "Invalid JSON payload for webhook.";
        error_log($errorMsg);
        file_put_contents("payload.txt", "Error: " . $errorMsg . "\n", FILE_APPEND);
        if ($debugMode) {
            echo "Error: " . $errorMsg . "\n";
        }
        return false;
    }
    
    if (!isset($data['sign'])) {
        $errorMsg = "Missing 'sign' in webhook payload.";
        error_log($errorMsg);
        file_put_contents("payload.txt", "Error: " . $errorMsg . "\n", FILE_APPEND);
        if ($debugMode) {
            echo "Error: " . $errorMsg . "\n";
        }
        return false;
    }
    
    // Extract and remove the 'sign' field.
    $receivedSign = $data['sign'];
    unset($data['sign']);
    
    // Re-encode the remaining data.
    $encodedData = json_encode($data, JSON_UNESCAPED_UNICODE);
    
    // Compute the expected signature.
    $calculatedSign = md5(base64_encode($encodedData) . $this->apiKey);
    
    // Log calculated and received signatures.
    $debugInfo = "Calculated Sign: {$calculatedSign}\nReceived Sign: {$receivedSign}\n";
    file_put_contents("payload.txt", $debugInfo, FILE_APPEND);
    if ($debugMode) {
        echo "Calculated Sign: " . $calculatedSign . "\n";
        echo "Received Sign: " . $receivedSign . "\n";
    }
    
    // Compare signatures.
    if (!hash_equals($calculatedSign, $receivedSign)) {
        $errorMsg = "Webhook signature verification failed. Calculated sign does not match received sign.";
        error_log($errorMsg);
        file_put_contents("payload.txt", "Error: " . $errorMsg . "\n", FILE_APPEND);
        if ($debugMode) {
            echo "Error: " . $errorMsg . "\n";
        }
        return false;
    }
    
    $successMsg = "Webhook verified successfully.";
    file_put_contents("payload.txt", $successMsg . "\n", FILE_APPEND);
    if ($debugMode) {
        echo $successMsg . "\n";
    }
    
    return true;
}



    /**
     * Convert a cryptocurrency amount to Nigerian Naira (NGN).
     * Replace the conversion rates with live values as needed.
     *
     * @param float  $amount   The cryptocurrency amount.
     * @param string $currency The cryptocurrency code ("BTC" or "USDT").
     * @return float           Equivalent amount in NGN.
     */
     function convertCryptoToNaira($amount, $currency) {
        //  "BTC"  => 20000000, // Example: 1 BTC = 20,000,000 NGN
        $conversionRates = [
            "USDT" => $this->get_settings("exchange_rate")
        ];
        return isset($conversionRates[$currency]) ? $amount * $conversionRates[$currency] : 0;
    }
}
?>
