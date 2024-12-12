<?php
require_once ROOT . "content/content.php";
require_once ROOT . "functions/users.php";
class ApiUser extends user
{
    public $userID;
    public $userData;
    // Constructor for ApiUser
    public function __construct()
    {
        // Call parent constructor to set the name
        parent::__construct();
        // Additional logic specific to ApiUser
        $token = $this->getBearerToken();
        if ($token == null || empty($token)) {
            die($this->apiMessage("No token passed", 401));
        }
        $user_api_data = $this->get_user_data($token);
        if (!is_array($user_api_data) || !isset($user_api_data['userID'])) {
            die($this->apiMessage("Invaild API Token", 401));
        }
        $this->userID = $user_api_data['userID'];
        $this->userData = $this->user_data($this->userID);
        if (count($this->userData) == 0) {
            die($this->apiMessage("Invaild API Token or account not active", 401));
        }
    }

    function get_order($isApi = "no", $id = null)
    {
        if ($id == null && !isset($_GET['id'])) {
            die($this->apiMessage("No ID passed", 401));
        }
        $id = $id ?? htmlspecialchars($_GET['id'] ?? $_GET['ID']);
        $order = $this->getall("orders", "ID = ? and userID = ?", [$id, $this->userID]);
        if (!is_array($order)) die($this->apiMessage("Order not found", 400));
        if ($isApi == "no") return $order;
        return $this->apiMessage("Order fetched", 200, $this->cleanOrder($order));
    }

    function get_user_data($token)
    {
        $api_data = $this->getall("api_data", "api_key = ? and status = ?", [$token, 1]);
        return $api_data;
    }
    function getBalance()
    {
        die($this->apiMessage("Balance fetched", 200, ["balance" => round($this->userData['balance'], 3), "total_credit" => $this->userData['total_credit'], "currency" => "NGN"]));
    }

    function cleanOrder($order)
    {
        if (!is_array($order)) return [];
        $fOrder = [];
        if ($order['order_type'] == "rentals") {
            $fOrder['ID'] = $order['ID'];
            // $fOrder['serviceCode'] = $order['serviceCode'];
            $fOrder['serviceName'] = $order['serviceName'];
            $fOrder['number'] = $order['loginIDs'];
            $fOrder['type'] = $order['type'];
            $fOrder['amount'] = $order['amount'];
            // $fOrder['country'] = $order['country'];
            $fOrder['expiration'] = $order['expiration'];
            $fOrder['expire_date'] = $order['expire_date'];
            $fOrder['status'] = $order['status'];
            $fOrder['date'] = $order['date'];
        }
        if ($order['order_type'] == "account") {
            $fOrder['ID'] = $order['ID'];
            $fOrder['accountID'] = $order['accountID'];
            $fOrder['no_of_orders'] = $order['no_of_orders'];
            $fOrder['loginIDs'] = $order['loginIDs'];
            $fOrder['amount'] = $order['amount'];
            // $fOrder['costAmount'] = $order['cost_amount'];
            $fOrder['date'] = $order['date'];
        }

        return $fOrder;
    }
}