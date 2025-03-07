<?php
$base_path = '';
if (side == "admin") $base_path = '../';
require_once $base_path.'vendor/autoload.php';

use Flutterwave\Flutterwave;
use Flutterwave\Helper\Config;
use Flutterwave\Service\VirtualAccount;

require_once $base_path.'functions/crypto.php';
class deposit extends user
{
    protected $crypto;

    public function __construct()
    {
        parent::__construct();
        $this->crypto = new CryptomusService($this->get_settings("cryptomus_ID"), $this->get_settings("cryptomus_api"));
    }
    public function get_payments($userID, $start, $limit =10)
    {
        return $this->getall("payment", "userID = ? order by date DESC LIMIT $start, $limit", [$userID], fetch: "moredetails");
    }
    public function get_account_details($userID)
    {
        $user = $this->getall("users", "ID =?", [$userID]);
        if (!is_array($user)) {
            return null;
        }

        $account = $this->getall("user_accounts", "userID = ?", [$userID]);
        if (!is_array($account)) {
            return null;
        }

        return $account;
    }

    public function create_wallet_address(array $user)
    {
        $check = $this->getall("user_wallets", 'userID = ?', [$user['ID']]);
        if (is_array($check)) {
            return $check;
        }

        $wallet = $this->crypto->createPaymentWallet($user['ID']);
        if (!is_array($wallet) || !isset($wallet['uuid'])) {
            return $this->message("Unable to create wallet", "error");
        }

        if ($this->getall("user_wallets", "uuid = ?", [$wallet['uuid']], fetch: "") > 0) {
            return;
        }

        $wallet = array_merge(["userID" => $user['ID']], $wallet);
        $this->quick_insert("user_wallets", $wallet);
        return $wallet;
    }

    public function create_account_details(array $user)
    {
        if ($this->get_settings("bvn") == "") {
            return false;
        }

        if ($this->getall("user_accounts", "userID = ?", [$user['ID']], fetch: "") > 0) {
            return false;
        }

        $myConfig = Config::setUp(
            $this->get_settings("flutterwave_secret_key"),
            $this->get_settings("flutterwave_public_key"),
            $this->get_settings("flutterwave_encyption_key"),
            'staging'
        );
        Flutterwave::bootstrap($myConfig);
        $service = new VirtualAccount(config: $myConfig);
        $tx_ref = time() . uniqid();
        $payload = [
            "email" => $user['email'],
            "tx_ref" => $tx_ref,
            "bvn" => $this->get_settings("bvn"),
            "narration" => $user['first_name'] . ' ' . $user['last_name'],
            "is_permanent" => true,
        ];
        $response = $service->create($payload);
        if (!isset($response->status) || $response->status != "success" || !isset($response->data)) {
            return false;
        }

        $data = (array) $response->data;
        $account = [
            "userID" => $user['ID'],
            "tx_ref" => $tx_ref,
            "order_ref" => $data['order_ref'],
            "account_number" => (int) $data['account_number'],
            "bank_name" => $data['bank_name'],
            "note" => $data['note'],
            "expiry_date" => $data['expiry_date'],
        ];
        if ($this->quick_insert("user_accounts", $account)) {
            return $account;
        }
        return false;
    }
    public function ini_payment($userID)
    {
        if (!isset($_POST['amount']) || (float) $_POST['amount'] <= 0) {
            echo $this->message("Enter a vaild amount", "error");
            return false;
        }

        $amount = (float) htmlspecialchars($_POST['amount']);
        if (!$this->check_min_max_deposit($amount)) {
            return false;
        }

        $user = $this->getall("users", "ID = ?", [$userID]);
        if (!is_array($user)) {
            return false;
        }

        $ch = curl_init();
        $root = $this->get_settings("website_url");
        $data = [
            'tx_ref' => uniqid('tx-ref-'),
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => $root . '/app/index?p=deposit',
            'meta' => [
                'consumer_id' => $user['ID'],
            ],
            'customer' => [
                'email' => $user['email'],
                'phonenumber' => $user['phone_number'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
            ],
            'customizations' => [
                'title' => $user['first_name'] . " Fund Account",
                'logo' => $root . "/app/assets/images/logos/" . $this->get_settings("dark_logo"),
            ],
        ];
        $payment = ["userID" => $userID, "tx_ref" => $data['tx_ref'], "amount" => $amount, "title" => $data['customizations']['title']];
        if (!$this->quick_insert("payment", $payment)) {
            return false;
        }

        $data_string = json_encode($data);
        curl_setopt($ch, CURLOPT_URL, 'https://api.flutterwave.com/v3/payments');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->get_settings("flutterwave_secret_key"),
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_status != 200) {
            $this->message("We have error making your payment: $response", "error");
            return false;
        }

        $response = json_decode($response, true);
        curl_close($ch);
        if ($response['status'] == "success" && isset($response['data']['link'])) {
            $this->update("payment", ["status" => "pending", "pay_url" => $response['data']['link']], "tx_ref = '" . $data['tx_ref'] . "'");
            $return = [
                "message" => ["Sucess", "Redirecting...", "success"],
                "function" => ["loadpage", "data" => [$response['data']['link'], "success"]],
            ];
            return json_encode($return);
        } else {
            return $this->message("Error: " . $response['message'], "error");
        }
    }

    public function verifyPayment($txref)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$txref/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->get_settings("flutterwave_secret_key"),
            ),
        ));
        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
            return false;
        }

        curl_close($curl);
        return json_decode($response, true);
    }

    public function validate_payment($txref, $transID, $userID)
    {
        // $pay = $this->verifyPayment($transID);
        // die(var_dump($pay));
        // check if txref is valid is own by userID
        $trans = $this->getall("payment", "userID = ? and tx_ref = ?", [$userID, $txref]);
        if (!is_array($trans)) {
            return false;
        }

        if ($trans['status'] == "successful" || $trans['status'] == "success") {
            // $this->message("Transaction amount already added to balance", "success");
            return false;
        }

        if ($trans['status'] != "" && $trans['status'] != "initiate" && $trans['status'] != "pending" && $trans['status'] != "successful" && $trans['status'] != "success") {
            $this->message("Faild Transaction.", "error");
            return false;
        }
        // verifyPayment
        $pay = $this->verifyPayment($transID);

        // die(var_dump($pay));
        if (!$pay) {
            $this->message("Error verifying payment", "error");
            return false;
        }
        // https://checkout.flutterwave.com/v3/hosted/pay/flwlnk-01jhz6czmmhhmw9kjybbghd18b
        // confrim if the amount match the amount
        if ($pay['status'] != "successful" && $pay['status'] != "success") {
            if (isset($_GET['continue'])) {
                return $this->loadpage($pay['pay_url']);
            }

            $this->message("Payment Faild please try again", "error");
            $this->update("payment", ["transaction_id" => $transID, "status" => $pay['status']], "ID = '" . $trans['ID'] . "'");
            return false;
        }

        // check of txt_ref match
        // check of userID match $pay['customer']['id']
        $pay = $pay['data'];

        if ($pay['payment_type'] == "bank_transfer") {
            // echo $this->message("This is a transfer", "error");
            return false;
        }
        if ($this->getall("transactions", "userID = ? and forID = ?", [$user['ID'], $pay["flw_ref"]], fetch: "") > 0) {
            // echo $this->apiMessage("Value assigned in the past", 401);
            return false;
        }

        if ($pay['tx_ref'] != $txref || $pay['meta']["consumer_id"] != $userID) {
            $this->message("Payment Faild please try again. <br> Seems the payment do not belong to you. <br> if you think this is an error send an email to: " . $this->get_settings("support_email"), "error");
            return false;
        }
        $amount = $pay['amount'];
        if (isset($pay['flw_ref']) && !empty($pay['flw_ref'])) {
            if ($this->credit_debit($userID, $amount, for :"payment", forID: $pay['flw_ref'])) {
                $this->update("payment", ["transaction_id" => $transID, "amount" => $amount, "status" => $pay['status']], "ID = '" . $trans['ID'] . "'");
                $this->message("Payment successfull", "success");
                $this->quick_insert("assiged_value", ["transID" => $pay['id'], "userID" => $userID]);
                return true;
                // credit amount and update the payment status and amount
            }
        }

        $this->message("Issue crediting your account. Please try again or contact us" . $this->get_settings("support_email"), "error");
        return true;
    }

    public function handle_double()
    {
        $trans = $this->getall("transactions", "ID != ? and forID is null and transID is null order by date desc", [""], fetch: "all");
        var_dump($trans->rowCount());
        $datas = [];
        foreach ($trans as $tran) {
            $datas[$tran['userID']] = $tran['current_balance'];
            $userID = $tran['userID'];
            $amount = $tran['current_balance'];

        }
        print($datas);
    }

    public function handleuserissue($userID, $amount)
    {
        $users = $this->getall("users", "userID LIKE '$userID%' and balance = $amount LIMIT 50", [""]);
        foreach ($users as $user) {
            $last_trans = $this->getall("transactions", "userID = ? order by date desc", [$user['ID']]);
            $current_balance = $last_trans['current_balance'];
            echo "<p> Balance: " . $user['balance'] . "</p>";
            echo "<p> Current Balance: $current_balance</p>";
            echo "<hr>";
            $duserID = $user['ID'];
            // $this->update("users", ["balance"=>$current_balance], "ID = '$duserID'");
        }
    }

    public function check_min_max_deposit($amount)
    {
        $max = (int) $this->get_settings("max_deposit");
        $min = $this->get_settings("min_deposit");
        if ($amount < $min) {
            $this->message("The minimum amount you can deposit is " . $this->money_format($min, currency), "error");
            return false;
        }

        if ($amount > $max && $max > 0) {
            $this->message("The maximum amount you can deposit is " . $this->money_format($max, currency), "error");
            return false;
        }
        return true;
    }

    public function new_deposit($data, $userID)
    {
        // $check = $this->getall("deposit", "userID = ? and status = ?", [$userID, "pending"], fetch: "");
        if ($this->get_deposit_max($userID)) {
            return $this->message("You have alot of pending deposit please wait for approval before you can make a new deposit", "error");
        }
        if (!is_array($data)) {
            return null;
        }
        $info = $this->validate_form($data);
        if (!is_array($info)) {
            return false;
        }
        $insert = $this->quick_insert("deposit", $info);
        if ($insert) {
            $actInfo = ["userID" => $userID, "date_time" => date("Y-m-d H:i:s"), "action_name" => "New Deposit", "description" => "A deposit of " . $this->money_format($info['amount'], currency) . " was made into your account."];
            $this->new_activity($actInfo);
            $return = [
                "message" => ["Sucess", "Deposit Submited for approval", "success"],
                "function" => ["loadpage", "data" => ["?p=deposit&action=list", "success"]],
            ];
            return json_encode($return);
        }
        return $this->message("Error submiting your request", "error");
    }

    public function get_total_pending($userID, $status, $data = null)
    {
        if ($data == null) {
            $data = $this->getall("deposit", "userID = ? and status = ? LIMIT 50", [$userID, $status], fetch: "moredetails");
        }
        if ($data->rowCount() == 0) {
            return $info = ['data' => [], 'number' => 0, 'total' => 0];
        }
        $info['total'] = 0;
        $info['data'] = $data;
        $info['number'] = $data->rowCount();
        foreach ($data as $row) {
            $info['total'] = $info['total'] + (float) $row['amount'];
        }
        // var_dump($total_amount);
        return $info;
    }
    public function get_deposit_max($userID)
    {
        $check = $this->getall("deposit", "userID = ? and status = ?", [$userID, "pending"], fetch: "");
        if ($check > $this->get_settings("deposit_max") || $check == $this->get_settings("deposit_max")) {
            return true;
        }
        return false;
    }

    /**
     * Process a Cryptomus webhook: validate the transaction and assign the converted amount to the user.
     *
     * Expects a JSON payload with:
     * - order_id: Unique order identifier (format: "WALLET_{userId}_{timestamp}")
     * - amount: Amount of cryptocurrency received.
     * - currency: Cryptocurrency code (e.g., "BTC" or "USDT").
     * - status: Payment status (e.g., "paid").
     *
     * @return void Outputs a response and updates the user's balance.
     */
    public function processWebhookTransaction()
    {
        $rawPayload = file_get_contents("php://input");
        $filePath   = 'payload.txt';
        // Prepend a newline to the payload so it starts on a new line
        $newContent = "\n" . $rawPayload;

        if($rawPayload != "") {
            // Append the new content to the file without replacing existing content
            if (file_put_contents($filePath, $newContent, FILE_APPEND) !== false) {
                echo "File updated successfully.";
            } else {
                echo "There was an error updating the file.";
            }
        }
       
        // Verify webhook authenticity.
        if (!$this->crypto->verifyWebhook()) {
            http_response_code(400);
            exit("Webhook verification failed.");

        }

        // Read and decode the payload.
        
        $data = json_decode($rawPayload, true);
        if (!$data) {
            http_response_code(400);
            exit("Invalid JSON payload.");
        }

        // Validate required fields.
        if (!isset($data['order_id'], $data['amount'], $data['currency'], $data['status'], $data['txid'], $data['merchant_amount'], $data['commission'])) {
            http_response_code(400);
            exit("Missing required fields in payload.");
        }

        if ($data['status'] !== "paid") {
            http_response_code(400);
            exit("Transaction not paid.");
        }

        // Prevent duplicate processing using the txid.
        $txid = $data['txid'];
        if ($this->getall("transactions", "forID = ?", [$txid], fetch: "") > 0) {
            // Transaction already processed.
            http_response_code(400);
            exit("Value already assigned.");
        }

        // Extract user id from order_id (assumes format "WALLET_{userId}_{timestamp}").
        $parts = explode('_', $data['order_id']);
        if (count($parts) < 3) {
            http_response_code(400);
            exit("Invalid order_id format.");
        }
        // Use GET parameter if provided, otherwise extract from order_id.
        $userId = htmlspecialchars($_GET['userID'] ?? $parts[1]);

        // Retrieve amounts from the payload.
        $merchantAmount = floatval($data['merchant_amount']); // The net amount credited by Cryptomus.
        $commission = floatval($data['commission']); // The commission taken by Cryptomus.

        // Define the commission sharing percentage:
        // This variable represents the percentage of the commission the owner is willing to share with the user.
        // If its value is above 100 or below 0, it will be treated as 0 (no sharing).
        $sharePercentageCommissionOnCrypto = $this->get_settings("share_percentage_commission_on_crypto"); // Adjust this value as needed.
        if ($sharePercentageCommissionOnCrypto > 100 || $sharePercentageCommissionOnCrypto < 0) {
            $sharePercentageCommissionOnCrypto = 0;
        }

        // Calculate the user's commission refund.
        // For example, if commission is 0.06 and owner shares 50%, then user gets 0.06 * 0.50 = 0.03 extra.
        $userCommissionRefund = $commission * ($sharePercentageCommissionOnCrypto / 100);

        // The effective crypto amount credited to the user.
        $userCryptoCredit = $merchantAmount + $userCommissionRefund;

        // Convert the effective crypto amount to NGN.
        $nairaAmount = $this->crypto->convertCryptoToNaira($userCryptoCredit, strtoupper($data['currency']));

        // Update the user's balance in the database.
        $this->credit_debit($userId, $nairaAmount, for :"crypto", forID: $txid);
    }

    /**
     * Generate wallet details HTML in a styled Bootstrap card.
     *
     * @param array $wallet Associative array with keys: 'address', 'network', 'currency'.
     * @return string The HTML string for displaying the wallet details.
     */
    public function display_wallet($wallet)
    {
        // Start output buffering to capture HTML content.
        ob_start();
        ?>
    <div class="card mx-auto bg-light">
      <div class="card-body">
        <!-- Wallet Address -->
        <div class="mb-4">
            <h5>Pay with crypto</h5>
            <small class="mb-0 text-muted">Please scan the QR code below or copy the wallet address to proceed with your transaction.</small>
          <h6 class="text-muted">Wallet Address</h6>
          <p class="fs-4 text-dark"><?php echo htmlspecialchars($wallet['address']) . $this->copy_text($wallet['address']) ?></p>
        </div>
        <!-- Network and Currency -->
        <div class="row mb-4">
          <div class="col-md-4">
            <h6 class="text-muted">Currency</h6>
            <p><?php echo htmlspecialchars($wallet['currency']); ?></p>
          </div>
          <div class="col-md-4">
            <h6 class="text-muted">Network</h6>
            <p><?php echo htmlspecialchars($wallet['network']); ?></p>
          </div>
          <div class="col-md-4">
            <h6 class="text-muted">Exchange Rate</h6>
            <!-- <p><small>Not fixed and can change anytime.</small></p> -->
            <p><?php echo $this->money_format($this->get_settings("exchange_rate")); ?></p>
          </div>
        </div>
        <!-- QR Code generated for the wallet address -->
        <div class="text-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php echo urlencode($wallet['address']); ?>"
            alt="Wallet QR Code" class="img-fluid" style="max-width: 200px;">
            <div>
            <h6 class="mb-3 badge bg-light-danger text-dark">Scan QR Code</h6>
            </div>
        </div>
      </div>
    </div>
    <?php
// Get the HTML from the buffer and clean it.
        $html = ob_get_clean();
        return $html;
    }
}