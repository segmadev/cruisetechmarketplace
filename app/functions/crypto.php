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
            'network'    => (strtoupper($currency) === 'USDT') ? 'tron' : 'bitcoin',
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

        
        // Check response.
        if (!$response || !isset($response->result)) {
            error_log("Cryptomus API Error: " . print_r($response, true));
            return false;
        }
        
        // Return the response as an array for compatibility.
        $return =  json_decode(json_encode($response), true);
        return $return['result'];
    }

   /* Verify the authenticity of an incoming webhook from Cryptomus.
    *
    * Assumes Cryptomus sends a header "sign" computed as:
    *     md5(base64_encode($rawPayload) . $this->apiKey)
    *
    * @return bool True if verified; false otherwise.
    */
   public function verifyWebhook() {
       $headers = getallheaders();
       if (!isset($headers['sign'])) {
           error_log("Webhook verification failed: Missing 'sign' header.");
           return false;
       }
       $receivedSign = $headers['sign'];
       $rawPayload = file_get_contents("php://input");
       $calculatedSign = md5(base64_encode($rawPayload) . $this->apiKey);
       if (hash_equals($calculatedSign, $receivedSign)) {
           return true;
       } else {
           error_log("Webhook verification failed: Calculated sign ($calculatedSign) does not match received sign ($receivedSign).");
           return false;
       }
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
