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

    function get_user_data($token)
    {
        $api_data = $this->getall("api_data", "api_key = ? and status = ?", [$token, 1]);
        return $api_data;
    }
    function getBalance()
    {
        die($this->apiMessage("Balance fetched", 200, ["balance" => round($this->userData['balance'], 3), "total_credit" => $this->userData['total_credit'], "currency" => "NGN"]));
    }
}