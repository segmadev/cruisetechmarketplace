<?php 

    class rentals extends user {
        protected $base_url;
        protected $API_code;
        protected $endpoints;
        protected $exchangeRateAPI;
        // protected $API_header;
        public function __construct() {
            // Call the parent constructor to initialize user properties
            parent::__construct();
            // Initialize rentals specific properties
            $this->base_url = $this->get_settings('rentals_base_url');
            $this->API_code = $this->get_settings('rentals_API');
            $this->endpoints = ["getServices"=>"handler_api.php?api_key=".$this->API_code."&action=getPricesVerification"];
            $this->exchangeRateAPI = $this->get_settings("exchange_rate_API");
            // $this->API_header = ['Authorization: Bearer '. $this->API_code];
        }

        function newNumber($userID, $serviceCode) {
            $services = $this->getServices();
            if(!isset($services[$serviceCode])) {
                return $this->message("Services no avilable.", "error", 'json');
            }
            $service = (array)$services[$serviceCode];
            $cost = $service['cost'];
            $valuedPrice = $this->valuedPrice($serviceCode, $cost);
            $user = $this->getall("users", "userID = ?", [$userID]);
            if(!is_array($user)) return $this->message("Unable to get user information.", "error", "json");
            if($user['balance'] < $valuedPrice) return $this->message("Insufficient balance", "error", "json");
            $order = [
                "ID"=>uniqid("order-"),
                "userID"=>$userID,
                "accountID"=>$serviceCode,
                "loginIDs"=>"",
                "amount"=>$valuedPrice,
                "no_of_orders"=>1,
                "order_type"=>"rentals"
            ];
            if(!$this->quick_insert("orders", $order)) return $this->message("Unable to make order", "error", "json");
            if(!$this->credit_debit($userID, $valuedPrice, "balance", "debit", "orders", $order['ID'])) $this->message("Error charging your account", "error", "json");
            
        }


        protected function rentNumber($serviceCode, $cost) {
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."action=getNumber&service=$serviceCode&max_price=$cost";
            $result = $this->api_call($url);
            if($result == null) return false;
            // return (array)$numbers;
        }

        function valuedPrice($serviceCode, $amount) {
            return $this->convertDollarToNGN((float)$amount) + (float)$this->get_settings("added_value_amount");
        }
        function getServices() {
            $url = $this->base_url.$this->endpoints['getServices'];
            $services = $this->api_call($url);
            if($services == null) return [];
            return (array)$services;
        }

        protected function getExchangeRate() {
            $exchangeRate = $this->getall("settings", "meta_name = ?",["exchange_rate"]);
            if (!is_array($exchangeRate) || !isset($exchangeRate['meta_value'])) {
                echo $this->message("This service is not avilable at the moment", "error");
                die('Exchange rate not found.');
                // exit();
            }
            // check last update
            $date = date('Y-m-d H:i:s');
            $lastUpdated = $exchangeRate['date'];
            $rate = $exchangeRate['meta_value'];
            if((int)$this->datediffe($date, $lastUpdated) >= 1) $rate = $this->setNewRate();
            return $rate;
        }

        protected function setNewRate(){
            $url = "https://v6.exchangerate-api.com/v6/".$this->exchangeRateAPI."/latest/USD";
            $data = (array)$this->api_call($url);
            if (!isset($data['conversion_rates']->NGN)) {
                echo $this->message("This service is not avilable at the moment", "error");
                die('Exchange rate for USD to NGN not found.');
                // exit();
            }
            $rate = $data['conversion_rates']->NGN;
            $this->update("settings", ["meta_value"=>$rate, "date"=>date("Y-m-d H:i:s")], "meta_name = 'exchange_rate'");
            return $rate;
        }
        public function convertDollarToNGN($dollarAmount) {
            $exchangeRate = $this->getExchangeRate();
            return $dollarAmount * $exchangeRate;
        }
    }