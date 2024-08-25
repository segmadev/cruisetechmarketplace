<?php 

    class rentals extends database {
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

        function getLikes($userID) {
            $likes = $this->getall("liked_services", "userID =?", [$userID], fetch: "all");
            if($likes == "") return [];
            $likeds = [];
            foreach($likes as $like) {
                $likeds[] = $like['serviceID'];
            }
            return $likeds;
        }
        function likeService($userID, $serviceID) {
            $likes = $this->getall("liked_services", "userID =? AND serviceID =?", [$userID, $serviceID]);
            if(is_array($likes)) return $this->delete("liked_services", "ID = ?", [$likes['ID']]);
            return $this->quick_insert("liked_services", ["userID"=>$userID, "serviceID"=>$serviceID]);
        }
        function newNumber($userID, $serviceCode) {
            $services = $this->getServices();
            if(!isset($services[$serviceCode])) {
                return $this->message("Services no avilable.", "error", 'json');
            }
            $service = (array)$services[$serviceCode];
            $service = (array)$service['187'];
            $cost = $service['cost'];
            $valuedPrice = $this->valuedPrice($serviceCode, $cost);
            $user = $this->getall("users", "ID = ?", [$userID]);
            if(!is_array($user)) return $this->message("Unable to get user information.", "error", "json");
            if($user['balance'] < $valuedPrice) return $this->message("Insufficient balance", "error", "json");
            $orderID = uniqid("order-");
            $order = [
                "ID"=>$orderID,
                "userID"=>$userID,
                "accountID"=>"",
                "serviceCode"=>$serviceCode,
                "loginIDs"=>"",
                "amount"=>$valuedPrice,
                "no_of_orders"=>1,
                "order_type"=>"rentals"
            ];
            if(!$this->quick_insert("orders", $order)) return $this->message("Unable to make order", "error", "json");
            if(!$this->credit_debit($userID, $valuedPrice, "balance", "debit", "orders", $order['ID'])) return $this->message("Error charging your account", "error", "json");
            $rentNumber = $this->rentNumber($serviceCode, $cost);
            // ["ID"=>"51173283", "ACCESS_NUMBER"=>"15633077436"];
            // $this->rentNumber($serviceCode, $cost);
            //  { ["ID"]=> string(8) "51173283" ["ACCESS_NUMBER"]=> string(11) "15633077436" }
            // var_dump($rentNumber);
            if(!is_array($rentNumber) || !isset($rentNumber['ID']) || !isset($rentNumber['ACCESS_NUMBER'])) {
                $this->credit_debit($userID, $valuedPrice, "balance", "credit", "orders-refrund", $order['ID']);
                $this->delete("orders", "ID = ?", [$orderID]);
                return $rentNumber;
            }
            $update = ["accountID"=>$rentNumber['ID'], "loginIDs"=>$rentNumber['ACCESS_NUMBER'], "date"=>date('Y-m-d H:i:s')];
            $update_order = $this->update("orders", $update, "ID ='$orderID'");
            if(!$update_order) return $this->message("Unable to update order.", "error", "json");
            return $this->loadpage("index?p=rentals&action=view&accountID=".$rentNumber['ID'], true, "Number booked successfully. Redirecting...");
            // $this->message("Number booked successfully.", "success", "json");
        }

        function handlePendingNumbers() {
            $orders = $this->getall("orders", "accountID != ? AND order_type = ?  AND status = ? order by date ASC LIMIT 100", ["","rentals", 1], fetch: "all");
            if($orders == "") return ["message"=>"No pending."];
            foreach($orders as $order) {
               $this->getNumberCode($order['ID']);
            }
            return ["message"=>"success"];
        }

        function handleBalance() {
            
        }
        function getNumberCode($orderID) {
            $order = $this->getall("orders", "ID =?", [$orderID]);
            if(!is_array($order)) return;
            if($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
            $isExpired = $this->numberExpired($order['date']);
            if(!$isExpired) $this->requestCodeNumber($order['accountID']); 
            if($isExpired && (int)$order['status'] == 1) $this->makeNumberAsDone($order['accountID']);
            if($isExpired && $this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
                $this->refundOrder($orderID);
            }
            return $this->getall("number_codes", "orderID = ? ORDER BY date desc", [$order['accountID']], fetch: "all");
        }

        function closeRental($orderID) {
            $order = $this->getall("orders", "ID =?", [$orderID]);
            if(!is_array($order)) return;
            if($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
            $this->makeNumberAsDone($order['accountID'], 2);
           
            if($this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
                $this->refundOrder($orderID);
            }
        }

        function numberExpired($date) : bool {
            $diff = $this->datediffe($date, date('Y-m-d H:i:s'), "m");
            $rental_expire_duration = $this->get_settings("rental_number_expire_time");
            if($diff <= $rental_expire_duration) return false;
            return true;
        }


        protected function rentNumber($serviceCode, $cost) {
            // first check the balance
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=getNumber&service=$serviceCode&max_price=$cost";
            $result = $this->api_call($url, isRaw: true);
            if($result == null) {
                return $this->message("Int Error: unable to get number.", "error", "json");
            }
            return $this->handleRentailException($result);
        }

        protected function requestCodeNumber($id) {
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=getStatus&id=$id";
            $result = $this->api_call($url, isRaw: true);
            $result = $this->handleRentailException($result);
            if(!is_array($result) || !isset($result['serviceCode'])) return ;
            if($result['serviceCode'] == "STATUS_CANCEL") return ;
            if($result['serviceCode'] == "STATUS_WAIT_CODE") return ;
            $code = $result['serviceCode'];
            if($this->getall("number_codes", "orderID = ? and NumberCode = ?", [$id, $code])) return ;
            $insert = ["orderID"=>$id, "NumberCode"=>$code];
            if($this->quick_insert("number_codes", $insert)) return true;
            return ;
        }

        protected function makeNumberAsDone($id, $status = 0) {
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=setStatus&id=$id&status=8";
            $this->api_call($url, isRaw: true);
            $this->update("orders", ["status"=>$status], "accountID = '$id'");
            return $this->message("Number status updated.", "success", "json");
        }

        protected function refundOrder($orderID) {
            $order = $this->getall("orders", "ID =?", [$orderID]);
            if(!is_array($order)) return;
            $check = $this->getall('transactions', "forID = ? and trans_for = ?", [$orderID, "orders-refrund"], fetch: "");
            if($check > 0) return;
            $this->credit_debit($order['userID'], $order['amount'], "balance", "credit", "orders-refrund", $order['ID']);
            return true;
        }

        function handleRentailException($response) {
            $results = explode(":", $response);
            // generic errors
            if($results[0] == "BAD_KEY") return $this->message("int: We can not take orders.", "error", "json");
            if($results[0] == "") return $this->message("This service is currently down at the moment.", "error", "json");
            // get code errors handler
            if($results[0] == "STATUS_OK") return ["serviceCode"=>$results[1]];
            if($results[0] == "STATUS_WAIT_CODE") return ["serviceCode"=>"STATUS_WAIT_CODE"];
            if($results[0] == "STATUS_CANCEL") return ["serviceCode"=>"STATUS_CANCEL"];
            // booking numbers errors handlers
            if($results[0] == "ACCESS_NUMBER") return ["ID"=>$results[1],"ACCESS_NUMBER"=>$results[2]];
            if($results[0] == "MAX_PRICE_EXCEEDED") return $this->message("Please refresh this page and try again.", "error", "json");
            if($results[0] == "NO_NUMBERS") return $this->message("No numbers avilable for this service at the moment.", "error", "json");
            if($results[0] == "TOO_MANY_ACTIVE_RENTALS") return $this->message("We have too many orders at the moment please try again in few mins.", "error", "json");
            if($results[0] == "NO_MONEY") return $this->message("We can not take orders.", "error", "json");
            // mark number as done
            if($results[0] == "ACCESS_ACTIVATION") return ["status"=>true];
            if($results[0] == "NO_ACTIVATION") return $this->message("Number not found.", "error", "json");
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
            if((int)$this->datediffe($date, $lastUpdated, "m") >= (int)$this->get_settings("exchange_rate_update_interval") && $this->get_settings("fix_exchange_rate") != "yes") $rate = $this->setNewRate();
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