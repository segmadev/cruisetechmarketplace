<?php 

    class rentals extends database {
        protected $base_url;
        protected $API_code;
        protected $endpoints;
        protected $exchangeRateAPI;
        protected $non_end_points;
        protected $non_auth;
        protected $anosim_end_points;
        public $brokers;
        public $sms_end_points;
        // protected $API_header;
        public function __construct() {
            // Call the parent constructor to initialize user properties
            parent::__construct();
            // Initialize rentals specific properties
            $this->base_url = $this->get_settings('rentals_base_url');
            $this->API_code = $this->get_settings('rentals_API');
            $this->endpoints = ["getServices"=>"handler_api.php?api_key=".$this->API_code."&action=getPricesVerification"];
            $this->exchangeRateAPI = $this->get_settings("exchange_rate_API");
            $this->non_end_points = [
                "getBalance"=>"https://nonvoipusnumber.com/manager/api/balance",
                "poducts"=>"https://nonvoipusnumber.com/manager/api/products",
                "order"=>"https://nonvoipusnumber.com/manager/api/order",
                "reuse"=>"https://nonvoipusnumber.com/manager/api/reuse",
                "activate"=>"https://nonvoipusnumber.com/manager/api/activate",
                "reject"=>"https://nonvoipusnumber.com/manager/api/reject",
                "renew"=>"https://nonvoipusnumber.com/manager/api/renew",
            ];

            $this->anosim_end_points = [
                "base_url"=>"https://anosim.net/api/v1",
                "balance"=>"/Balance",
                "countries"=>"/Countries",
                "products"=>"/Products",
                "order"=>"/Orders",
                "getsms"=>"/Sms",
                "orderbooking"=>"/OrderBooking",
                "orderbookings"=>"/OrderBookings",
                "/"=>"/"

            ];
            $this->sms_end_points = [
                "base_url"=>"https://sms-activation-service.pro/stubs/handler_api",
            ];
            $this->non_auth = [
                "email"=>$this->get_settings("nonvoipusnumber_email"),
                "password"=>$this->get_settings("nonvoipusnumber_password"),
            ];

            $this->brokers = [
                1=>"daisysms",
                2=>"nonvoipusnumber",
                3=>"anosim",
            ];
            // $this->API_header = ['Authorization: Bearer '. $this->API_code];
        }

        function getBrokerName($id) {
            // $brokers = [
            //     "1"=>"dai",
            // ];
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
        function newNumber($userID) {
            $data = $this->validate_form(["id"=>[], "broker"=>["is_required"=>false], "type"=>["is_required"=>false], "countryCode"=>["is_required"=>false]], showError: false);
            if(!is_array($data)) return $this->message("Invalid form data. Reload page and try again", "error", "json");
            $serviceCode = $data['id'];
            $broker = $data['broker'] ?? "daisysms";
            $noType = $data['type'] ?? "short_term";
            $service = $this->getServices($broker, $noType, $serviceCode, countryID: $data['countryCode']);
            if(!is_array($service) ||count($service) == 0) {
                return $this->message("Service(s) not available.", "error", 'json');
            }
            $cost = $service['cost'] ?? $service['price'];
            if(isset($service['available']) && (int)$service['available'] <= 0){
                return $this->message("No Number available you can try another network.", "error", 'json');
            }
            $valuedPrice = $this->valuedPrice($noType, $broker, $cost);
            $user = $this->getall("users", "ID = ?", [$userID]);
            if(!is_array($user)) return $this->message("Unable to get user information.", "error", "json");
            if($user['balance'] < $valuedPrice) return $this->message("Insufficient balance", "error", "json");
            $orderID = uniqid("order-");
            $order = [
                "ID"=>$orderID,
                "userID"=>$userID,
                "accountID"=>"",
                "serviceCode"=>$serviceCode,
                "serviceName"=>$service['name'] ?? "",
                "loginIDs"=>"",
                "amount"=>$valuedPrice,
                "no_of_orders"=>1,
                "order_type"=>"rentals",
                "type"=>$noType
            ];
            if(!$this->quick_insert("orders", $order)) return $this->message("Unable to make order", "error", "json");
            if(!$this->credit_debit($userID, $valuedPrice, "balance", "debit", "orders", $order['ID'])) return $this->message("Error charging your account", "error", "json");
            $rentNumber = $this->rentNumber($serviceCode, $cost, $broker, $data['countryCode'] ?? null);
            if(!is_array($rentNumber) || !isset($rentNumber['ID']) || !isset($rentNumber['ACCESS_NUMBER'])) {
                $this->credit_debit($userID, $valuedPrice, "balance", "credit", "orders-refrund", $order['ID']);
                $this->delete("orders", "ID = ?", [$orderID]);
                return $rentNumber;
            }
            if($broker == "daisysms" || $broker == "sms_activation") {
                $rentNumber['expiration'] = ((int)$this->get_settings('rental_number_expire_time') * 60);
            }

            if(isset($rentNumber['expiration']) && $rentNumber['expiration'] != "") {
                $rentNumber['expire_date'] = $this->getFutureDateTime($rentNumber['expiration']);
            }

            if( $rentNumber['expire_date'] != "" && $rentNumber['expiration'] == "") {
                $rentNumber['expiration'] = $this->getSecondsUntilExpiration($rentNumber['expire_date']);
            }

            $update = ["accountID"=>$rentNumber['ID'], "loginIDs"=>$rentNumber['ACCESS_NUMBER'], "broker_name"=>$broker, "expiration"=>($rentNumber['expiration'] ?? ""), "expire_date"=>($rentNumber['expire_date'] ?? ""), "date"=>date('Y-m-d H:i:s')];
            $update_order = $this->update("orders", $update, "ID ='$orderID'");
            if(!$update_order) return $this->message("Unable to update order.", "error", "json");
            return $this->loadpage("index?p=rentals&action=view&accountID=".$rentNumber['ID'], true, "Number booked successfully. Redirecting...");
            // $this->message("Number booked successfully.", "success", "json");
        }

        function getSecondsUntilExpiration($expirationDate) {
            // Get the current date and time
            $currentDateTime = new DateTime();
        
            // Create a DateTime object for the expiration date
            $expirationDateTime = new DateTime($expirationDate);
        
            // Calculate the difference between the expiration date and the current date
            $interval = $currentDateTime->diff($expirationDateTime);
        
            // Convert the difference to seconds
            $seconds = ($interval->days * 24 * 60 * 60) +
                       ($interval->h * 60 * 60) +
                       ($interval->i * 60) +
                       $interval->s;
        
            // Return the seconds (positive if in the future, negative if in the past)
            return ($expirationDateTime > $currentDateTime) ? $seconds : -$seconds;
        }

        function getFutureDateTime($seconds, $isMin = false) {
            // Convert seconds to minutes
            if(!$isMin) $minutes = $seconds / 60;
            // Get the current date and time
            $currentDateTime = new DateTime();
        
            // Add the minutes to the current date and time
            $currentDateTime->modify("+$minutes minutes");
        
            // Format the result as a string
            return $currentDateTime->format('Y-m-d H:i:s');
        }

        function handlePendingNumbers() {
            $orders = $this->getall("orders", "accountID != ? AND order_type = ?  AND status = ? order by date ASC LIMIT 100", ["","rentals", 1], fetch: "all");
            if($orders == "") return ["message"=>"No pending."];
            foreach($orders as $order) {
               $this->getNumberCode($order['ID']);
            }
            return ["message"=>"success"];
        }

        
        function getNumberCode($orderID) {
            $order = $this->getall("orders", "ID =?", [$orderID]);
            if(!is_array($order)) return;
            if($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
            $isExpired = $this->numberExpired($order['expire_date']);
            if(!$isExpired && $order['broker_name'] == "daisysms") $this->requestCodeNumber($order['accountID']); 
            if(!$isExpired && $order['broker_name'] == "anosim") $this->anosimRequestCodeNumber($order['accountID']); 
            if(!$isExpired && $order['broker_name'] == "sms_activation") $this->smsActivationrequestCodeNumber($order['accountID']); 
           
            if($isExpired == true && (int)$order['status'] == 1) {
                $this->closeRental($order['userID'], $order['ID'], 0);
            }
            
            if($isExpired && $this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
                $this->refundOrder($orderID);
            }
            return $this->getall("number_codes", "orderID = ? ORDER BY date desc", [$order['accountID']], fetch: "all");
        }

        function closeRental($userID, $orderID, $status = 2, $order = null) {
            if($order == null) $order = $this->getall("orders", "ID = ? and userID = ?", [$orderID, $userID]);
            if(!is_array($order)) return;
            if($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
            $marked = false;
            if($order['broker_name'] == "nonvoipusnumber") {
                $marked = $this->nonRejectNumber($order['serviceCode'], $order['type'], $order['loginIDs'], $order['accountID']);
            }
            if($order['broker_name'] == "daisysms") {
                $marked = true;
                if($this->makeNumberAsDone($order['accountID']) == false) $marked = false;
            }
            if($order['broker_name'] == "sms_activation") {
                $marked = true;
                if($this->smsActivationCancel($order['accountID']) == false) $marked = false;
            }
            if($order['broker_name'] == "anosim") {
               $marked = $this->anosimCancel($order['accountID']);
            }
           
            $broker_name = $order['broker_name'];
            if($marked == true || $status == 0) {
                $this->update("orders", ["status"=>$status], "ID = '$orderID' and broker_name = '$broker_name'");
            }
            if($marked && $this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
                $this->refundOrder($orderID);
            }
        }

        function numberExpired($date) : bool {
            if($date == null) return true;
            $givenDateTime = new DateTime($date);
            $currentDateTime = new DateTime();

            // Compare the given date/time with the current date/time
            if ($currentDateTime > $givenDateTime) {
                return true; // The given date/time has passed
            } else {
                return false; // The given date/time has not passed
            }
            // $diff = $this->datediffe($date, date('Y-m-d H:i:s'), "m");
            // $rental_expire_duration = $this->get_settings("rental_number_expire_time");
            // if($diff <= $rental_expire_duration) return false;
            // return true;
        }


        protected function rentNumber($serviceCode, $cost, $broker = "daisysms", $countryCode = "") {
            // first check the balance
            if($broker == "daisysms") {
                $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=getNumber&service=$serviceCode&max_price=$cost";
                $result = $this->api_call($url, isRaw: true);
                if($result == null) {
                    return $this->message("Int Error: unable to get number.", "error", "json");
                }
                return $this->handleRentailException($result);
            }

            if($broker == "sms_activation") {
                $result = $this->smsActivationGetNumber($serviceCode, $countryCode);
                if($result == null) {
                    return $this->message("Int Error: unable to get number.", "error", "json");
                }
                return $result;
            }

            if($broker == "nonvoipusnumber") {
                // second broker
                $response = $this->nonRentNumber($serviceCode);
                // $data =  $this->nonHandleRentailException($response);
                if(!is_array($response)) $response = (array)$response;
                if($response['status'] == "error") {
                if($response['message'] == "Insufficient balance.") return $this->message("We can not take orders.", "error", "json");
                    return $this->message("Number not available or Something went wrong.", "error", "json");
                }
                
                if($response['status'] == "success") {
                    $message =  (array)$response['message'];
                    $message = (array)$message[0];
                    // var_dump("message 0:", ($message));
                    return ["ID"=>$message['order_id'],"ACCESS_NUMBER"=>$message['number'], "expiration"=>($message['expiration'] ?? ""), "expire_date"=>($message['expires'] ?? "")];
                }
            }

            if($broker == "anosim") {
                return $this->anosimRentNumber($serviceCode);
            }
        }

        function handleBalance() {
            if($this->get_settings("notification_email") == "" || (float)$this->get_settings("notify_low_balance_amount") <= 0) return;
            $api_url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=getBalance";
            $result = $this->api_call($api_url, isRaw: true);
            $result = $this->handleRentailException($result);
            if(!is_array($result) || !isset($result['balance'])) return ;
            if((float)$result['balance'] > (float)$this->get_settings("notify_low_balance_amount")) return ;
            $message = "You have a low balance on ".str_replace('stubs/', '', $this->base_url).". Current balance is <b>".$this->money_format($result['balance'], "USD")."</b>";
            $smessage = $this->get_email_template("default")['template'];
            $smessage = $this->replace_word(['${first_name}' => "Admin", '${message_here}' => $message, '${website_url}' => $this->get_settings("website_url")], $smessage);
            $this->smtpmailer($this->get_settings("notification_email"), "Rental Low Balance on ".date("Y-m-d h:i:sa"), $smessage);
        }
        protected function requestCodeNumber($id) {
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=getStatus&id=$id";
            $result = $this->api_call($url, isRaw: true);
            $result = $this->handleRentailException($result);
            if(!is_array($result) || !isset($result['serviceCode'])) return ;
            if($result['serviceCode'] == "STATUS_CANCEL") return ;
            if($result['serviceCode'] == "STATUS_WAIT_CODE") return ;
            $code = $result['serviceCode'];
            if($this->newCode($id, $code)) return true;
            return ;
        }

        protected function newCode($id, $code, $sender="", $number = "") {
            if($this->getall("number_codes", "orderID = ? and NumberCode = ?", [$id, $code])) return ;
            $insert = ["orderID"=>$id, "phone_number"=>$number, "NumberCode"=>$code, "sender"=>$sender];
            if($this->quick_insert("number_codes", $insert)) return true;
            return false; 
        }

        protected function makeNumberAsDone($id, $status = 0) {
            $url = $this->base_url."handler_api.php?api_key=".$this->API_code."&action=setStatus&id=$id&status=8";
            $request = $this->api_call($url, isRaw: true);
            if($request == "EARLY_CANCEL_DENIED" || $request == "CANNOT_BEFORE_2_MIN") {
                $this->message("You can not cancel this number at the moment", "error");
                return false;
            }
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
            if($results[0] == "MAX_PRICE_EXCEEDED" || $results[0] = "WRONG_MAX_PRICE") return $this->message("Please refresh this page and try again.", "error", "json");
            if($results[0] == "NO_NUMBERS") return $this->message("No numbers available for this service at the moment.", "error", "json");
            if($results[0] == "TOO_MANY_ACTIVE_RENTALS") return $this->message("We have too many orders at the moment please try again in few mins.", "error", "json");
            if($results[0] == "NO_MONEY" || $results[0] == "NO_BALANCE") return $this->message("We can not take orders for this number type at the moment.", "error", "json");
            // mark number as done
            if($results[0] == "ACCESS_ACTIVATION") return ["status"=>true];
            if($results[0] == "NO_ACTIVATION") return $this->message("Number not found.", "error", "json");
            // get balance
            if($results[0] == "ACCESS_BALANCE") return ["balance"=>$results[1]];
        }

        function valuedPrice($noType, $broker, $amount) {
            // echo "added_value_amount_".$broker."_".$noType;
            return round($this->convertDollarToNGN((float)$amount) + (float)$this->get_settings("added_value_amount_".$broker."_".$noType), 2);
        }
        function getServices($type = "daisysms", $noType = "short_term", $id = null, $fromCookie = false, $countryID = null) {
            if($fromCookie) {
                $data = $this->getCookieValue($type."service".$noType.($id ?? ''));
                if($data != null) return $data;
            }
            if($type == "daisysms") {
                $url = $this->base_url.$this->endpoints['getServices'];
                $services = $this->api_call($url);
                if($services == null) return [];
                $services = (array)$services;
                if($id !=  null) {
                    $services = (array)$services[$id];
                    $services = (array)$services['187'];
                }
                $this->setCookieValue($type."service".$noType.($id ?? ''), $services);
                return $services;
            }

            if($type == "nonvoipusnumber") {
                $data = $this->nonGetservices($noType, $id);
                // var_dump($noType);
                if(!is_array($data)) return [];
                $data = (array)$data[0];
                $this->setCookieValue($type."service" . $noType . ($id ?? ''), $data);
                return $data;
            }

            if($type == "anosim") {
                $data = $this->anosimGetServices($countryID ?? null, id: $id ?? "");
                return $data;
            }
            if($type == "sms_activation") {
                $data = $this->smsActivationGetService($countryID, $id);
                return $data;
            }
           
        }

        protected function getExchangeRate() {
            $exchangeRate = $this->getall("settings", "meta_name = ?",["exchange_rate"]);
            if (!is_array($exchangeRate) || !isset($exchangeRate['meta_value'])) {
                echo $this->message("This service is not available at the moment", "error");
                die('Exchange rate not found.');
                // exit();
            }
            // check last update
            $date = date('Y-m-d H:i:s');
            $lastUpdated = $exchangeRate['date'];
            $rate = $exchangeRate['meta_value'];
            if((int)$this->datediffe($date, $lastUpdated, "m") >= (int)$this->get_settings("exchange_rate_update_interval") && $this->get_settings("fix_exchange_rate") != "yes") $rate = $this->setNewRate();
            return $rate + 30;
        }

        protected function setNewRate(){
            $url = "https://v6.exchangerate-api.com/v6/".$this->exchangeRateAPI."/latest/USD";
            $data = (array)$this->api_call($url);
            if (!isset($data['conversion_rates']->NGN)) {
                echo $this->message("This service is not available at the moment", "error");
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

        // nonvoipusnumber rental
        function nonGetservices($type, $id = null, $network = 1) {
            $data = ["type"=>$type, "id"=>$id, "network"=>$network];
            $request = $this->nonAPiCall($this->non_end_points['poducts'], $data);
            if($request->status  != "success") return [];
            $request =  (array)$request;
            return $request['message']; 
        }

        function nonRentNumber($serviceId) {
            $request = $this->nonAPiCall($this->non_end_points['order'], ["product_id"=>$serviceId]);
            return $request;
        }

        function nonRejectNumber($serviceCode, $type, $number, $orderID) {
            $service = $this->nonGetservices($type, $serviceCode);
            if(!is_array($service) || count($service) == 0) return false;
            $service = (array)$service[0];
            $data = ["service"=>$service['name'], "number"=>$number, "order_id"=>$orderID];
            $request = $this->nonAPiCall($this->non_end_points['reject'], $data);
            if($request->status  == "success")  {
                return true;
            } 
            return false;
            // exit();
        }

        function nonResuse($serviceName, $number) {
            $data = ["service"=>$serviceName, "number"=>$number];
            return $this->nonAPiCall($this->non_end_points['reuse'], $data);
        }

        function nonActivateNumber($userID, $orderID) {
            $order = $this->getall("orders", "userID = ? and orderID = ? and type != ? and broker_name = ?", array($userID, $orderID, "short_term", ""));
            if(!is_array($order)) return false;
            $service = $this->nonGetservices($order['type'], $order['serviceCode']);
            if(!is_array($service) || count($service) == 0) return false;
            $service = (array)$service[0];
            $data = ["service"=>$service['name'], "number"=>$order['number'], "order_id"=>$orderID];
            $request = $this->nonAPiCall($this->non_end_points['activate'], $data);
        }

        private function nonAPiCall($url, array $data = []) {
            $data = json_encode(array_merge($this->non_auth, $data));
            $response = $this->api_call($url, $data, ["Content-Type: application/json"]);
            return $response;
        }

        function nonHandleRentailException($response) {
            if(!is_array($response)) $response = (array)$response;
            if($response['status'] == "error") {
               if($response['message'] == "Insufficient balance.") return $this->message("We can not take orders.", "error", "json");
               return $this->message("Number not available or Something went wrong.", "error", "json");
            }
            if($response['status'] == "success") {
                return $response['message'];
            }
        }

        function nonHandleCallBack() {
            // Read the raw POST data from the incoming callback
            $rawData = file_get_contents('php://input');
            // Decode the JSON data into a PHP array
            $data = json_decode($rawData, true);
            $data = (array)$data;
            if(isset($data['message'])) (array)$data['message'];
            if(isset($data['event']) && $data['event'] == "incoming_message") {
                $message = $data['message'][0] ?? $data['message'];
                if(!is_array($message)) return ;
                $code = $message['sms'];
                $number = $message['number'];
                $data = $this->getall("orders", "loginIDs = ?", [$number]);
                if(!is_array($data)) return ;
                $id = $data['accountID'];
                if($this->newCode($id, $code, $message['sender'] ?? "", $message['number'])) {
                    $this->nonResuse($data['serviceName'], $number);
                    return json_encode(["success"]);
                }
            }
        }

        // anosim api calls
        protected function anosimGetServices($countryID = 98, $id = "") {
            $services = $this->anosimCallApi("products", "&countryId=$countryID", $id);
            if($id != "")  {
                $services = (array)$services;
                $services['name'] = $services['service'];
                unset($services['service']);
                return $services;
            }
            return $this->cleanAnosimData($services);
        }

         function anosimRentNumber($id) {
            $request = $this->anosimCallApi("order", "&productId=$id&amount=1&providerId=0", method: "POST");
            // if(!is_string($request) || trim($request) === '') return  $this->message("Number not available or Something went wrong. Try another network/service 1", "error", "json");
            $request = (array)$request;
            if(isset($request[0])) $request = $request[0];
            if(!is_array($request) || !isset($request['orderBookings'])) return $this->message("Number not available or Something went wrong. Try another network/service 2", "error", "json");
            $request = $request['orderBookings'][0];
            if(!is_array($request)) $request = (array)$request;
            if(isset($request[0])) $request = $request[0];
            $info = [];
            $info['expiration'] = (int)$request['durationInMinutes'] * 60;  
            $info['ID'] = $request['id'];
            $info['ACCESS_NUMBER'] = $request['number'];
            $info['country'] = $request['country'];
            return $info;

        }
        protected function anosimRequestCodeNumber($id = "") {
            $request = $this->anosimCallApi("getsms", id: $id);
            $request = (array)$request;
            foreach($request as $message) {
                $message = (array)$message;
                if(!isset($message['messageText'])) continue;
                $this->newCode($id, htmlspecialchars($message['messageText']), htmlspecialchars($message['messageSender'] ?? ""), htmlspecialchars($message['simCardNumber'] ?? ""));
            }  
        }

         protected function anosimCancel($id) {
            // https://anosim.net/api/v1/OrderBookings/59?apikey=XXX
            $request = $this->anosimCallApi("orderbookings", id: $id,  method: "PATCH");
            $request = (array)$request;
            if(isset($request[0])) $request = $request[0];
            if(isset($request['success']) && ($request['success'] == true || $request['success'] == "true")) {
                $this->update("orders", ["status"=>2], "accountID = '$id' and broker_name = 'anosim'");
                return true;
            }
            return false;
        }
        
        function anosmsCountries() {
            return $this->anosimCallApi("countries");
        }
        function anosimCallApi($endpoint = "/", $data = "", $id = "", $method = "GET") {
            $id = (!empty($id)) ? "/".$id : $id;
            $url = $this->anosim_end_points["base_url"]
                   .$this->anosim_end_points["$endpoint"]
                   ."$id?apikey=".$this->get_settings("anosim_API")
                   .$data;
            return $this->api_call($url, method: $method);
        }

        function cleanAnosimData($jsonData) : array {
            // Decode the JSON into a PHP array
            // $data = json_decode($jsonData, true);
            // $data = $jsonData;
            // Use array_filter and array_map to process the data
            $result = array_map(function($item) {
                // Change 'service' key to 'name'
                $item = (array)$item;
                $item['name'] = $item['service'];
                unset($item['service']);
                return $item;
            }, array_filter((array)$jsonData, function($item) {
                $item = (array)$item;
                if(!isset($item['rentalType'])) return $item;
                // Filter only where rentalType is 'Activation'
                return $item['rentalType'] === 'Activation';
            }));
        
            return $result; // Return the processed array
        }


        // sms-activation-service api
        function getElementById($id, $data) {
            $filtered = array_filter($data, function($item) use ($id) {
                return $item['id'] == $id;
            });
        
            // Reset array keys and return the first matching element, or null if none found
            return !empty($filtered) ? array_values($filtered)[0] : null;
        }
         function smsActivationGetService($countryCode, $service = null) {
             $services = $this->smsActivationAPI("&action=getServicesAndCost&country=$countryCode&operator=any&service=$service&lang=en");
            if(!is_array($services)) return [];
            if($service != null) return (array)$services[0];
            return $services;
        }

        protected function smsActivationrequestCodeNumber($id) {
            $result = $this->smsActivationAPI("&action=getStatus&id=$id&lang=en", isRaw: true);
            $result = $this->handleRentailException($result);
            if(!is_array($result) || !isset($result['serviceCode'])) return ;
            if($result['serviceCode'] == "STATUS_CANCEL") return ;
            if($result['serviceCode'] == "STATUS_WAIT_CODE") return ;
            $code = $result['serviceCode'];
            if($this->newCode($id, $code)) return true;
            return ;
        }

        protected function smsActivationCancel($id, $status = 8) {
            $services = $this->smsActivationAPI("&action=setStatus&id=$id&status=$status&lang=en", isRaw: true);
            if($services == "EARLY_CANCEL_DENIED" || $services == "CANNOT_BEFORE_2_MIN") {
                $this->message("You can not cancel this number at the moment", "error");
                return false;
            }
            return $this->message("Number status updated.", "success", "json");
        }

        // rent number
        function smsActivationGetNumber($serviceID, $countryCode = null) {
            $request = $this->smsActivationAPI("&action=getNumber&service=$serviceID&country=$countryCode&lang=en", isRaw: true);
            return $this->handleRentailException($request);

        }
        function smsActivationCountries() {
            $countries = $this->getCookieValue("smsActivationCountries");
            if($countries!= "" && $countries != null) return unserialize(base64_decode($countries));
            $countries = $this->smsActivationAPI("&action=getCountryAndOperators&lang=en");
            $this->setCookieValue("smsActivationCountries", base64_encode(serialize($countries)));
            return (array)$countries;
        }

         function smsActivationAPI($params, $method = "GET", $isRaw = false) {
            
            $url = $this->sms_end_points['base_url']."?api_key=".$this->get_settings("sms_activation_API").$params;
            return $this->api_call($url, method: $method, isRaw: $isRaw);
         }

         function getCountryCode($countryName) {
                        // Read the JSON file
                $jsonFile = file_get_contents('countrie/countries.json');

                // Decode the JSON data into an associative array
                $countries = json_decode($jsonFile, true);

                // Loop through the array to find the country by name
                foreach ($countries as $country) {
                    if (strtolower($country['name']) == strtolower($countryName)) {
                        return $country['code']; // Return the country code if found
                    }
                }
                return null; // Return null if the country name is not found
         }
    }