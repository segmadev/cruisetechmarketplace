<?php

class rentals extends database
{
    protected $base_url;
    protected $API_code;
    protected $endpoints;
    protected $exchangeRateAPI;
    protected $non_end_points;
    protected $non_auth;
    protected $anosim_end_points;
    public $brokers;
    public $sms_end_points;
    public $sms_activate_Two_services;
    // protected $API_header;
    public function __construct()
    {
        // Call the parent constructor to initialize user properties
        parent::__construct();
        // Initialize rentals specific properties
        $this->sms_activate_Two_services = [];
        $this->base_url = $this->get_settings('rentals_base_url');
        $this->API_code = $this->get_settings('rentals_API');
        $this->endpoints = ["getServices" => "handler_api.php?api_key=" . $this->API_code . "&action=getPricesVerification"];
        $this->exchangeRateAPI = $this->get_settings("exchange_rate_API");
        $this->non_end_points = [
            "getBalance" => "https://nonvoipusnumber.com/manager/api/balance",
            "poducts" => "https://nonvoipusnumber.com/manager/api/products",
            "order" => "https://nonvoipusnumber.com/manager/api/order",
            "reuse" => "https://nonvoipusnumber.com/manager/api/reuse",
            "activate" => "https://nonvoipusnumber.com/manager/api/activate",
            "reject" => "https://nonvoipusnumber.com/manager/api/reject",
            "renew" => "https://nonvoipusnumber.com/manager/api/renew",
        ];

        $this->anosim_end_points = [
            "base_url" => "https://anosim.net/api/v1",
            "balance" => "/Balance",
            "countries" => "/Countries",
            "products" => "/Products",
            "order" => "/Orders",
            "getsms" => "/Sms",
            "orderbooking" => "/OrderBooking",
            "orderbookings" => "/OrderBookings",
            "/" => "/"

        ];
        $this->sms_end_points = [
            "base_url" => "https://sms-activation-service.pro/stubs/handler_api",
            "sms_activate_two_base_url" => "https://api.sms-activate.org/stubs/handler_api.php",
            "sms_bower" => "https://smsbower.online/stubs/handler_api.php",
        ];
        $this->non_auth = [
            "email" => $this->get_settings("nonvoipusnumber_email"),
            "password" => $this->get_settings("nonvoipusnumber_password"),
        ];

        $this->brokers = [
            1 => "daisysms",
            2 => "nonvoipusnumber",
            3 => "anosim",
            4 => "sms_activation",
            5 => "sms_activate_two",
            6 => "sms_bower"
        ];
        // $this->API_header = ['Authorization: Bearer '. $this->API_code];
    }

    function getBrokerName($id)
    {
        // $brokers = [
        //     "1"=>"dai",
        // ];
    }

    function get_services($number_type, $network, $countryCode = "", $get = "all")
    {
        $rental_services = [];
        $countries = null;
        if ($number_type == "short_term" && $network == 1) {
            $broker = "daisysms";
            if ($get == "all" || $get == "services")  $rental_services = $this->getServices(fromCookie: true);
        }
        if ($number_type == "short_term" && $network == 2) {
            $broker = "nonvoipusnumber";
            if ($get == "all" || $get == "services") $rental_services = $this->getServices($broker, "short_term", fromCookie: true);
            // var_dump($rental_services);
        }
        if ($number_type == "short_term" && $network == 3) {
            $broker = "anosim";
            if ($get == "all" || $get == "services")  $rental_services = (array)$this->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
            if ($get == "all" || $get == "countries") $countries = (array)$this->anosmsCountries();
        }
        if ($number_type == "short_term" && $network == 4 && $countryCode != "") {
            $broker = "sms_activation";
            if ($get == "all" || $get == "services")   $rental_services = $this->getServices($broker, "short_term", countryID: $countryCode);
            if ($get == "all" || $get == "countries") $countries = $this->smsActivationCountries();
        }

        if ($number_type == "short_term" && $network == 5 && $countryCode != "") {
            $broker = "sms_activate_two";
            if ($get == "all" || $get == "services") $rental_services = $this->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
            if ($get == "all" || $get == "countries")  $countries = $this->getCountry("smsActicateTwoCountries");
        }
        if ($number_type == "short_term" && $network == 6 && $countryCode != "") {
            $broker = "sms_bower";
            if ($get == "all" || $get == "services")  $rental_services = $this->getServices($broker, "short_term", countryID: $countryCode, fromCookie: true);
            // var_dump($rental_services);
            if ($get == "all" || $get == "countries")  $countries = $this->getCountry(broker: "smsBowerCountries");
            // if(isset($countries[0])) $countries = $countries[0];
            // var_dump($countries);
            // exit();
        }
        if ($number_type == "long_term") {
            $broker = "nonvoipusnumber";
            if ($get == "all" || $get == "services")  $rental_services = ($network != 1) ? $this->nonGetservices("long_term", network: $network) : $this->getServices($broker, "long_term", fromCookie: true);
        }
        if ($number_type == "3days") {
            $broker = "nonvoipusnumber";
            if ($get == "all" || $get == "services") $rental_services = $this->getServices($broker, "3days", fromCookie: true);
        }
        $info = ["broker" => $broker];
        if ($get == "all" || $get == "services") $info['services'] = $rental_services;
        if ($get == "all" || $get == "countries") $info['countries'] = $countries;
        return $info;
    }

    function cleanUpCounty($singleCountry, $network = 1)
    {
        // var_dump($singleCountry);
        $countryName = $singleCountry['name'] ?? $singleCountry['country'] ?? $singleCountry['eng'];
        if ($countryName == "USA") {
            $countryName = "USA (Real)";
        }

        if ($countryName == "Southafrica") {
            $countryName = "South Africa";
        }

        // if ($network == "3" && ($countryName != "Germany" && $countryName != "Netherlands")) {
        //     return false;
        // }

        if (($network == "4" || $network == "6") && $countryName == "Germany") {
            return false;
        }

        $code = $this->getKeyValue($countryName, key: "name");
        $countryID = $singleCountry['id'] ?? $singleCountry['ID'];
        $flagUrl = $code ? 'https://flagcdn.com/w320/' . strtolower($code) . '.png' : 'https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=hsjdhsd.com&size=24';
        return ["id" => $countryID, "code" => $code, "name" => $countryName, "flagUrl" => $flagUrl];
    }

    function filter_services($services, $number_type, $broker, $userID = null)
    {
        $countryCode = htmlspecialchars($_GET['countryID'] ?? $_GET['countryCode'] ?? 98);
        // return var_dump($services);
        // $newServices = [];
        $services_list = [];
        $network = (int)htmlspecialchars($_GET['network'] ?? 1);
        $brokerKey = array_search($broker, $this->brokers);
        if ($number_type == "short_term" && ($network == 3 || $network == 4 || $network == 5 || $network == 6 || $brokerKey)) {
            return $this->flitter_anosim($services, $network, $broker, $userID);
        }
        if ($number_type == "short_term" && $network == 1) {
            foreach ($services as $key => $service) {
                $service = (array)$service;
                $service = isset($service['187']) ? (array) $service['187'] : $service;
                $service['id'] = base64_encode("$key||$number_type||$countryCode||$network||$brokerKey");
                $service['cost'] = (!is_array($service['cost'] ?? $service['price'])) ?  $this->valuedPrice($number_type, $broker, $service['cost'] ?? $service['price']) : $service['cost'];
                array_push($services_list, ["id" => $service['id'], "name" => $service['name'], "cost" => $service['cost']]);
            }
            return $services_list;
        }

        if ($number_type == "long_term" || $number_type == '3days' || $network == 2) {

            foreach ($services as $key => $service) {
                $key = $service->product_id;
                $service = (array)$service;
                $service['id'] = base64_encode("$key||$number_type||$countryCode||$network||$brokerKey");
                $service['cost'] = (!is_array($service['cost'] ?? $service['price'])) ?  $this->valuedPrice($number_type, $broker, $service['cost'] ?? $service['price']) : $service['cost'];
                array_push($services_list, ["id" => $service['id'], "name" => $service['name'], "cost" => round($service['cost'], 2)]);
            }
            return $services_list;
        }
        return [];
    }

    function filter_short($services, $network) {}

    function flitter_anosim($rental_services, $network, $broker, $userID = null)
    {
        $number_type = htmlspecialchars($_GET['type'] ?? "short_term");

        if (count($rental_services) == 0 || $rental_services == "") {
            return [];
            // echo $c->empty_page("No Services Available at the moment.");
        } else {
            // return [];
            $used = [];
            $services_list = [];
            $brokerKey = array_search($broker, $this->brokers);
            $likeds = $userID != null ? $this->getLikes($userID) : [];
            foreach ($rental_services as $key => $service) {
                $service = (array)$service;
                $service = isset($service['187']) ? (array) $service['187'] : $service;
                if (!isset($used[$key])) {
                    $key = ($network == 6) ? $key : $service["id"] ?? $key;
                    if (!isset($service['id']) && !isset($service['ID'])) $service['id'] = $key;
                    if ($network == 6) {
                        if ($key != "wa" && $key != "tg") $service['cost'] = $this->getLowestPrice((array)$service);
                        if ($key == "wa" || $key == "tg") {
                            $service['cost'] = (array)$service;
                            unset($service['cost']['id']);
                        }
                        if ($service['cost'] == 0) continue;
                        $service['maxPrice'] = $service['cost'];
                        $service['name'] = $this->getKeyValue($key, 'countrie/services.json');
                        if ($service['name'] == null || $service['name'] == "") continue;
                    }
                    // var_dump($service);  return;
                    $countryCode = htmlspecialchars($_GET['countryID'] ?? $_GET['countryCode'] ?? 98);
                    if ($network == 5 && !isset($service['name'])) {
                        $service['name'] = $this->smsActivateTwoGetServices($countryCode, $key);
                        if ($service['cost'] > 20 && $service['cost'] < 50)  $service['cost'] = $service['cost'] + 20;
                        if ($service['cost'] > 50)  $service['cost'] = $service['cost'] + 50;
                    }
                    $service['id'] = base64_encode("$key||$number_type||$countryCode||$network||$brokerKey");
                    // die(var_dump($service));
                    // $service['name'] = explode("/", $service['name']);
                    $service['cost'] = (!is_array($service['cost'] ?? $service['price'])) ?  $this->valuedPrice($number_type, $broker, $service['cost'] ?? $service['price']) : $service['cost'];
                    // if (isset($service['price'])) unset($service['cost']);
                    if (in_array($key, $likeds)) {
                        array_unshift($services_list, ["id" => $service['id'], "name" => $service['name'], "cost" => round($service['cost'] ?? $service['price'], 2)]);
                    } else {
                        array_push($services_list, ["id" => $service['id'], "name" => $service['name'], "cost" => round($service['cost'] ?? $service['price'], 2)]);
                    }
                }
            }
            return $services_list;
        }
    }
    function getLikes($userID)
    {
        $likes = $this->getall("liked_services", "userID =?", [$userID], fetch: "all");
        if ($likes == "") return [];
        $likeds = [];
        foreach ($likes as $like) {
            $likeds[] = $like['serviceID'];
        }
        return $likeds;
    }
    function likeService($userID, $serviceID)
    {
        $likes = $this->getall("liked_services", "userID =? AND serviceID =?", [$userID, $serviceID]);
        if (is_array($likes)) return $this->delete("liked_services", "ID = ?", [$likes['ID']]);
        return $this->quick_insert("liked_services", ["userID" => $userID, "serviceID" => $serviceID]);
    }
    function newNumber($userID, $fromAPI = false)
    {
        // return '{"code":200,"status":"success","message":"Number Booked Successfully","data":{"ID":"order-674c8ef677e5f","userID":"64e90df826719","amount":842.03,"type":"short_term","serviceName":"Amazon / AWS","accountID":"102932897","loginIDs":"12694844764","expiration":420,"expire_date":"2024-12-01 17: 36: 43","date":"2024-12-01 17: 29: 43"}}';
        $data = $this->validate_form(["id" => [], "broker" => ["is_required" => false], "type" => ["is_required" => false], "countryCode" => ["is_required" => false], "maxPrice" => ["is_required" => false]], showError: false);
        if (!is_array($data)) return $this->message("Invalid form data. Reload page and try again", "error", "json");
        if (isset($data['maxPrice'])) $data['maxPrice'] = (float)base64_decode($data['maxPrice']);
        $data['broker'] = base64_decode($data['broker']);
        $serviceCode = $data['id'];
        $broker = $data['broker'] ?? "daisysms";
        $noType = $data['type'] ?? "short_term";
        if ($broker == "sms_bower") {
            $service['id'] = $data['id'];
            $service['name'] = $this->getKeyValue($service['id'], 'countrie/services.json');
            $service['cost'] = $data['maxPrice'];
        } else {
            $service = $this->getServices($broker, $noType, $serviceCode, countryID: $data['countryCode']);
        }
        if (!is_array($service) || count($service) == 0) {
            return $this->message("Service(s) not available.", "error", 'json');
        }
        if ($data['broker'] == "sms_activate_two") {
            if ($service['cost'] > 20 && $service['cost'] < 50)  $service['cost'] = $service['cost'] + 20;
            // if($service['cost'] > 50 && $service['cost'] < 90)  $service['cost'] = $service['cost'] + 50; 
            if ($service['cost'] > 50)  $service['cost'] = $service['cost'] + 50;
        }
        $cost = $service['cost'] ?? $service['price'] ?? $data['maxPrice'];
        $valuedPrice = $this->valuedPrice($noType, $broker, $cost);
        if (isset($data['maxPrice']) && $cost > $data['maxPrice']) {
            if ($fromAPI == false && !isset($_POST['maxPrice'])) {
                $this->getServices($broker, $noType, countryID: $data['countryCode']);
                return $this->message("The price has changed. <br> The current price for " . ($service['name'] ?? "this service") . " is now: " . $this->money_format($valuedPrice) . ".<br> If you can purchase it at this price, <br> please reload this page and try again.", "error", 'json');
            }
        }
        if (isset($service['available']) && (int)$service['available'] <= 0) {
            return $this->message("No Number available you can try another network.", "error", 'json');
        }
        $user = $this->getall("users", "ID = ?", [$userID]);
        if (!is_array($user)) return $this->message("Unable to get user information.", "error", "json");
        if ($user['balance'] < $valuedPrice) return $this->message("Insufficient balance", "error", "json");
        $orderID = uniqid("order-");
        $order = [
            "ID" => $orderID,
            "userID" => $userID,
            "accountID" => "",
            "serviceCode" => $serviceCode,
            "serviceName" => ($service['name'] ?? ($this->getKeyValue($serviceCode, 'countrie/services.json') ?? "")),
            "loginIDs" => "",
            "amount" => $valuedPrice,
            "no_of_orders" => 1,
            "order_type" => "rentals",
            "type" => $noType,
            "order_where" => ($fromAPI) ? "api" : "live"
        ];
        if (!$this->quick_insert("orders", $order)) return $this->message("Unable to make order", "error", "json");
        if (!$this->credit_debit($userID, $valuedPrice, "balance", "debit", "orders", $order['ID'])) return $this->message("Error charging your account", "error", "json");
        $rentNumber = $this->rentNumber($serviceCode, $cost, $broker, $data['countryCode'] ?? null);
        if (!is_array($rentNumber) || !isset($rentNumber['ID']) || !isset($rentNumber['ACCESS_NUMBER']) || $rentNumber['ACCESS_NUMBER'] == "") {
            $this->credit_debit($userID, $valuedPrice, "balance", "credit", "orders-refrund", $order['ID']);
            $this->delete("orders", "ID = ?", [$orderID]);
            return $rentNumber;
        }
        if ($broker == "daisysms" || $broker == "sms_activation" || $broker == "sms_bower") {
            $rentNumber['expiration'] = ((int)$this->get_settings('rental_number_expire_time') * 60);
        }

        if ($broker == "sms_activate_two") {
            $rentNumber['expiration'] = (20 * 60);
        }

        if (isset($rentNumber['expiration']) && $rentNumber['expiration'] != "") {
            $rentNumber['expire_date'] = $this->getFutureDateTime($rentNumber['expiration']);
        }

        if ($rentNumber['expire_date'] != "" && $rentNumber['expiration'] == "") {
            $rentNumber['expiration'] = $this->getSecondsUntilExpiration($rentNumber['expire_date']);
        }

        $update = ["accountID" => $rentNumber['ID'], "loginIDs" => $rentNumber['ACCESS_NUMBER'], "broker_name" => $broker, "expiration" => ($rentNumber['expiration'] ?? ""), "expire_date" => ($rentNumber['expire_date'] ?? ""), "date" => date('Y-m-d H:i:s')];
        $update_order = $this->update("orders", $update, "ID ='$orderID'");
        if ($fromAPI) {
            $newOrder = [];
            unset($update["broker_name"]);
            $newOrder['ID'] = $order['ID'];
            // $newOrder['userID'] = $order['userID'];
            $newOrder['amount'] = $order['amount'];
            $newOrder['type'] = $order['type'];
            $newOrder['serviceName'] = $order['serviceName'];
            $newOrder['number'] = $update['loginIDs'];
            unset($update['loginIDs']);
            $newOrder = array_merge($newOrder, $update);
            return $newOrder;
        }
        if (!$update_order) return $this->message("Unable to update order.", "error", "json");
        return $this->loadpage("index?p=rentals&action=view&accountID=" . $rentNumber['ID'], true, "Number booked successfully. Redirecting...");
        // return $this->message("Number booked successfully.", "success", "json");
    }

    function getSecondsUntilExpiration($expirationDate)
    {
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

    function getFutureDateTime($seconds, $isMin = false)
    {
        // Convert seconds to minutes
        if (!$isMin) $minutes = $seconds / 60;
        // Get the current date and time
        $currentDateTime = new DateTime();

        // Add the minutes to the current date and time
        $currentDateTime->modify("+$minutes minutes");

        // Format the result as a string
        return $currentDateTime->format('Y-m-d H:i:s');
    }

    function handlePendingNumbers()
    {
        $orders = $this->getall("orders", "accountID != ? AND order_type = ?  AND status = ? order by date ASC LIMIT 100", ["", "rentals", 1], fetch: "all");
        if ($orders == "") return ["message" => "No pending."];
        foreach ($orders as $order) {
            $this->getNumberCode($order['ID']);
        }
        return ["message" => "success"];
    }


    function getNumberCode($orderID)
    {
        $order =  (is_array($orderID)) ? $orderID : $this->getall("orders", "ID =?", [$orderID]);
        if (!is_array($order)) return;
        $orderID = $order['ID'];
        if ($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
        $isExpired = $this->numberExpired($order['expire_date']);
        if (!$isExpired && $order['broker_name'] == "daisysms") $this->requestCodeNumber($order['accountID']);
        if (!$isExpired && $order['broker_name'] == "sms_activate_two") $this->smsActivateTwoRequestCodeNumber($order['accountID']);
        if (!$isExpired && $order['broker_name'] == "sms_bower") $this->smsBowerRequestCodeNumber($order['accountID']);
        if (!$isExpired && $order['broker_name'] == "anosim") $this->anosimRequestCodeNumber($order['accountID']);
        if (!$isExpired && $order['broker_name'] == "sms_activation") $this->smsActivationrequestCodeNumber($order['accountID']);

        if ($isExpired == true && (int)$order['status'] == 1) {
            $this->closeRental($order['userID'], $order['ID'], 0);
        }

        if ($isExpired && $this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
            $this->refundOrder($orderID);
        }
        return $this->getall("number_codes", "orderID = ? ORDER BY date desc", [$order['accountID']], fetch: "all");
    }

    function closeRental($userID, $orderID, $status = 2, $order = null)
    {
        if ($order == null) $order = $this->getall("orders", "ID = ? and userID = ?", [$orderID, $userID]);
        if (!is_array($order)) return;
        if ($order['order_type'] != 'rentals' || $order['accountID'] == "") return;
        $marked = false;
        if ($order['broker_name'] == "nonvoipusnumber") {
            $marked = $this->nonRejectNumber($order['serviceCode'], $order['type'], $order['loginIDs'], $order['accountID']);
        }
        if ($order['broker_name'] == "daisysms") {
            $marked = true;
            if ($this->makeNumberAsDone($order['accountID']) == false) $marked = false;
        }
        if ($order['broker_name'] == "sms_activate_two") {
            $marked = true;
            if ($this->smsActivateTwoMakeNumberAsDone($order['accountID']) == false) $marked = false;
        }
        if ($order['broker_name'] == "sms_bower") {
            $marked = true;
            if ($this->smsBowerMakeNumberAsDone($order['accountID']) == false) $marked = false;
        }
        if ($order['broker_name'] == "sms_activation") {
            $marked = true;
            if ($this->smsActivationCancel($order['accountID']) == false) $marked = false;
        }
        if ($order['broker_name'] == "anosim") {
            $marked = $this->anosimCancel($order['accountID']);
        }

        $broker_name = $order['broker_name'];
        if ($marked == true || $status == 0) {
            $this->update("orders", ["status" => $status], "ID = '$orderID' and broker_name = '$broker_name'");
        }
        if ($marked && $this->getall("number_codes", "orderID = ?", [$order['accountID']], fetch: "") <= 0) {
            $this->refundOrder($orderID);
        }
        return $marked;
    }

    function numberExpired($date, $timeZone = null): bool
    {
        if ($date == null) {
            return true;
        }

        // Use the default timezone if none is provided
        $timeZone = $timeZone ? new DateTimeZone($timeZone) : new DateTimeZone(date_default_timezone_get());

        // Create a DateTime object with the given date and the specified (or default) timezone
        $givenDateTime = new DateTime($date, $timeZone);

        // Create a DateTime object for the current time in the specified (or default) timezone
        $currentDateTime = new DateTime('now', $timeZone);

        // Compare the given date/time with the current date/time
        return $currentDateTime > $givenDateTime; // Return true if the given date/time has passed, false otherwise
    }



    protected function rentNumber($serviceCode, $cost, $broker = "daisysms", $countryCode = "")
    {

        // first check the balance
        if ($broker == "daisysms") {
            $url = $this->base_url . "handler_api.php?api_key=" . $this->API_code . "&action=getNumber&service=$serviceCode&max_price=$cost";
            $result = $this->api_call($url, isRaw: true);
            if ($result == null) {
                return $this->message("Int Error: unable to get number.", "error", "json");
            }
            return $this->handleRentailException($result);
        }

        if ($broker == "sms_activation") {
            $result = $this->smsActivationGetNumber($serviceCode, $countryCode);
            if ($result == null) {
                return $this->message("Int Error: unable to get number.", "error", "json");
            }
            return $result;
        }

        if ($broker == "nonvoipusnumber") {
            // second broker
            $response = $this->nonRentNumber($serviceCode);
            // $data =  $this->nonHandleRentailException($response);
            if (!is_array($response)) $response = (array)$response;
            if ($response['status'] == "error") {
                if ($response['message'] == "Insufficient balance.") return $this->message("We can not take orders.", "error", "json");
                return $this->message("Number not available or Something went wrong.", "error", "json");
            }

            if ($response['status'] == "success") {
                $message =  (array)$response['message'];
                $message = (array)$message[0];
                // var_dump("message 0:", ($message));
                return ["ID" => $message['order_id'], "ACCESS_NUMBER" => $message['number'], "expiration" => ($message['expiration'] ?? ""), "expire_date" => ($message['expires'] ?? "")];
            }
        }

        if ($broker == "anosim") {
            return $this->anosimRentNumber($serviceCode);
        }

        if ($broker == "sms_activate_two") {
            $request =  $this->smsActivateTwoGetNumber($serviceCode, $countryCode, $cost);
            if (!is_array($request)) {
                return $this->message("Number not available or Something went wrong. <br> Try again later", "error", "json");
            }
            return $request;
        }
        if ($broker == "sms_bower") {
            $request =  $this->smsBowerGetNumber($serviceCode, $countryCode, $cost);
            // var_dump($request);
            if (!is_array($request)) {
                $more = "Try again later.";
                if ($serviceCode == "wa" || $serviceCode == "tg") $more = "You can try another price.";
                return $this->message("Number not available or Something went wrong. <br> $more", "error", "json");
            }
            return $request;
        }
    }

    function handleBalance($broker = "daisysms")
    {
        if ($this->get_settings("notification_email") == "" || (float)$this->get_settings("notify_low_balance_amount") <= 0) return [];
        if ($broker == "daisysms") {
            $api_url = $this->base_url . "handler_api.php?api_key=" . $this->API_code . "&action=getBalance";
            // var_dump($api_url);
            $result = $this->api_call($api_url, isRaw: true);
            $result = $this->handleRentailException($result);
            // var_dump($result);
        }
        if ($broker == "sms_activate_two") {
            $api_url = $this->smsActivateTwoAPI("&action=getBalance", isRaw: true);
            $result = $this->handleRentailException($api_url);
        }
        if ($broker == "nonvoipusnumber") $result = $this->nonGetBalance();
        if ($broker == "anosim") $result = $this->anosimGetBalance();
        if (!is_array($result) || !isset($result['balance'])) return [];
        $notifyBalance =  $this->get_settings("notify_low_balance_amount_$broker") ? $this->get_settings("notify_low_balance_amount_$broker") : $this->get_settings("notify_low_balance_amount");
        if ((float)$result['balance'] > (float)$notifyBalance) return [];
        $message = "You have a low balance on $broker Current balance is <b>" . $this->money_format($result['balance'], "USD") . "</b>";
        $smessage = $this->get_email_template("default")['template'];
        $smessage = $this->replace_word(['${first_name}' => "Admin", '${message_here}' => $message, '${website_url}' => $this->get_settings("website_url")], $smessage);
        // var_dump($notifyBalance);
        // die();
        $send = $this->smtpmailer($this->get_settings("notification_email"), "Rental Low Balance on " . date("Y-m-d h:i:sa"), $smessage);
        // var_dump($send);
    
    }
    protected function requestCodeNumber($id)
    {
        $url = $this->base_url . "handler_api.php?api_key=" . $this->API_code . "&action=getStatus&id=$id";
        $result = $this->api_call($url, isRaw: true);
        $result = $this->handleRentailException($result);
        if (!is_array($result) || !isset($result['serviceCode'])) return;
        if ($result['serviceCode'] == "STATUS_CANCEL") return;
        if ($result['serviceCode'] == "STATUS_WAIT_CODE") return;
        $code = $result['serviceCode'];
        if ($this->newCode($id, $code)) return true;
        return;
    }



    protected function smsActivateTwoRequestCodeNumber($id)
    {
        $url = "&action=getStatus&id=$id";
        $result = $this->smsActivateTwoAPI($url, isRaw: true);
        return $this->handleCode($result, $id);
    }

    protected function handleCode($result, $id)
    {
        $result = $this->handleRentailException($result);
        if (!is_array($result) || !isset($result['serviceCode'])) return;
        if ($result['serviceCode'] == "STATUS_CANCEL") return;
        if ($result['serviceCode'] == "STATUS_WAIT_CODE") return;
        $code = $result['serviceCode'];
        if ($this->newCode($id, $code)) return true;
        return;
    }

    protected function newCode($id, $code, $sender = "", $number = "")
    {
        if ($this->getall("number_codes", "orderID = ? and NumberCode = ?", [$id, $code])) return;
        $insert = ["orderID" => $id, "phone_number" => $number, "NumberCode" => $code, "sender" => $sender];
        if ($this->quick_insert("number_codes", $insert)) return true;
        return false;
    }

    protected function makeNumberAsDone($id, $status = 0)
    {
        $url = $this->base_url . "handler_api.php?api_key=" . $this->API_code . "&action=setStatus&id=$id&status=8";
        $request = $this->api_call($url, isRaw: true);
        if ($request == "EARLY_CANCEL_DENIED" || $request == "CANNOT_BEFORE_2_MIN") {
            $this->message("You can not cancel this number at the moment", "error");
            return false;
        }
        return $this->message("Number status updated.", "success", "json");
    }

    protected function smsActivateTwoMakeNumberAsDone($id, $status = 0)
    {
        $url = "&action=setStatus&id=$id&status=8";
        $request = $this->smsActivateTwoAPI($url, isRaw: true);
        if ($request == "EARLY_CANCEL_DENIED" || $request == "CANNOT_BEFORE_2_MIN") {
            $this->message("You can not cancel this number at the moment", "error");
            return false;
        }
        return $this->message("Number status updated.", "success", "json");
    }

    protected function refundOrder($orderID)
    {
        $order = $this->getall("orders", "ID =?", [$orderID]);
        if (!is_array($order)) return;
        $check = $this->getall('transactions', "forID = ? and trans_for = ?", [$orderID, "orders-refrund"], fetch: "");
        if ($check > 0) return;
        $this->credit_debit($order['userID'], $order['amount'], "balance", "credit", "orders-refrund", $order['ID']);
        return true;
    }



    function handleRentailException($response)
    {
        $results = explode(":", $response);
        // generic errors
        if ($results[0] == "BAD_KEY") return $this->message("int: We can not take orders.", "error", "json");
        if ($results[0] == "") return $this->message("This service is currently down at the moment.", "error", "json");
        // get code errors handler
        if ($results[0] == "STATUS_OK") {
            return ["serviceCode" => $results[1]];
        }
        if ($results[0] == "STATUS_WAIT_CODE") return ["serviceCode" => "STATUS_WAIT_CODE"];
        if ($results[0] == "STATUS_CANCEL") return ["serviceCode" => "STATUS_CANCEL"];

        // get balance
        if ($results[0] == "ACCESS_BALANCE") return ["balance" => $results[1]];

        // booking numbers errors handlers
        if ($results[0] == "ACCESS_NUMBER") return ["ID" => $results[1], "ACCESS_NUMBER" => $results[2]];
        if ($results[0] == "MAX_PRICE_EXCEEDED" || $results[0] = "WRONG_MAX_PRICE") return $this->message("Please refresh this page and try again.", "error", "json");
        if ($results[0] == "NO_NUMBERS") return $this->message("No numbers available for this service at the moment.", "error", "json");
        if ($results[0] == "TOO_MANY_ACTIVE_RENTALS") return $this->message("We have too many orders at the moment please try again in few mins.", "error", "json");
        if ($results[0] == "NO_MONEY" || $results[0] == "NO_BALANCE") return $this->message("We can not take orders for this number type at the moment.", "error", "json");
        // mark number as done
        if ($results[0] == "ACCESS_ACTIVATION") return ["status" => true];
        if ($results[0] == "NO_ACTIVATION") return $this->message("Number not found.", "error", "json");
    }

    function daisysmsWebhook()
    {
        // Read the raw POST data from the incoming callback
        $rawData = file_get_contents('php://input');
        // Decode the JSON data into a PHP array
        $data = json_decode($rawData, true);
        $data = (array)$data;
        $this->update_catched_data("daisysmsWebhook", json_encode($data) ?? "Nothing");
        if (!is_array($data) || count($data) == 0) $data = $_POST;
        if (!is_array($data) || count($data) == 0) return;
        if (!is_array($data) || !isset($data['activationId'])) return;
        $accountID = $data['activationId'];
        $code = htmlspecialchars($data['text'] ?? $data['code']);
        $data = $this->getall("orders", "accountID = ? and broker_name = ?", [$accountID, "daisysms"]);
        if (!is_array($data)) return;
        $id = $data['accountID'];
        $orderID = $data['ID'];
        if ($this->newCode($id, $code, $data['sender'] ?? "", $data['loginIDs'])) {
            return json_encode(["success"]);
        }
        $this->update("orders", ["activate_expire_date" => ""], "ID ='$orderID'");
    }


    function valuedPrice($noType, $broker, $amount)
    {
        // echo "added_value_amount_".$broker."_".$noType;
        $currency = null;
        if ($broker == "sms_bower") $currency = "RUB";
        return round($this->convertDollarToNGN((float)$amount, $currency) + (float)$this->get_settings("added_value_amount_" . $broker . "_" . $noType), 2);
    }
    function daisysmsService($id = null)
    {
        $url = $this->base_url . $this->endpoints['getServices'];
        $services = $this->api_call($url);
        if ($services == null) return [];
        $services = (array)$services;
        if ($id !=  null) {
            $services = (array)$services[$id];
            $services = (array)$services['187'];
        }
        return $services;
    }

    function getServices($type = "daisysms", $noType = "short_term", $id = null, $fromCookie = false, $countryID = null)
    {
        $methods = [
            "daisysms" => ["function" => "daisysmsService", "params" => [$id]],
            "nonvoipusnumber" => ["function" => "nonGetservices", "params" => [$noType, $id]],
            "anosim" => ["function" => "anosimGetServices", "params" => [$countryID ?? null, $id ?? ""]],
            "sms_activation" => ["function" => "smsActivationGetService", "params" => [$countryID, $id]],
            "sms_activate_two" => ["function" => "smsActivteTwoGetService", "params" => [$countryID, $id]],
            "sms_bower" => ["function" => "smsBowerServices", "params" => [$countryID, $id]],
        ];

        if ($fromCookie && !isset($_GET['currentprice'])) {
            $data = $this->get_settings($type . "service" . $noType . ($id ?? '') . ($countryID ?? ''), where: "catched_data", type: "all");
            // var_dump($data);
            if (is_array($data)) {
                $date = $data['date'];
                $data = $data['meta_value'];
                $diff = $this->datediffe(date('Y-m-d H:i:s'), $date, "m");
                if ($diff < 10 && $data != null && $data != "[]" && $data != "") return (array)json_decode(base64_decode($data));
            }
        }
        // die(var_dump($type));
        $function = $methods[$type]['function'];
        $params = $methods[$type]['params'];
        if (method_exists($this, $function)) {
            $result = call_user_func_array([$this, $function], $params);
            $this->update_catched_data($type . "service" . $noType . ($id ?? '') . ($countryID ?? ''), $result);
            return $result;
        }
        return [];
    }

    protected function getExchangeRate($currency = null)
    {
        $exchangeRate = $this->getall("settings", "meta_name = ?", ["exchange_rate$currency"]);
        if (!is_array($exchangeRate) || !isset($exchangeRate['meta_value'])) {
            echo $this->message("This service is not available at the moment", "error");
            die('Exchange rate not found.');
            // exit();
        }
        // check last update
        $date = date('Y-m-d H:i:s');
        $lastUpdated = $exchangeRate['date'];
        $rate = $exchangeRate['meta_value'];
        if ((int)$this->datediffe($date, $lastUpdated, "m") >= (int)$this->get_settings("exchange_rate_update_interval") && $this->get_settings("fix_exchange_rate") != "yes") $rate = $this->setNewRate($currency ?? "USD");
        if ($currency == "USD" || $currency == "") $rate = $rate + 30;
        return $rate;
    }

    public function setNewRate($currency = "USD")
    {
        $url = "https://v6.exchangerate-api.com/v6/" . $this->exchangeRateAPI . "/latest/$currency";
        $data = (array)$this->api_call($url);
        if (!isset($data['conversion_rates']->NGN)) {
            echo $this->message("This service is not available at the moment", "error");
            die('Exchange rate for USD to NGN not found.');
            // exit();
        }
        $rate = $data['conversion_rates']->NGN;
        if ($currency == 'USD') $currency = "";
        $this->create_settings(["exchange_rate$currency" => ""]);
        $this->update("settings", ["meta_value" => $rate, "date" => date("Y-m-d H:i:s")], "meta_name = 'exchange_rate$currency'");
        return $rate;
    }
    public function convertDollarToNGN($dollarAmount, $currency = null)
    {
        $exchangeRate = $this->getExchangeRate($currency);
        return $dollarAmount * $exchangeRate;
    }

    // nonvoipusnumber rental
    function nonGetBalance()
    {
        $request = $this->nonAPiCall($this->non_end_points['getBalance']);
        $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        return $request;
    }
    function nonGetservices($type, $id = null, $network = 1)
    {
        $data = ["type" => $type, "id" => $id, "network" => $network];
        $request = $this->nonAPiCall($this->non_end_points['poducts'], $data);
        // var_dump($request);
        if ($request->status  != "success") return [];
        $request =  (array)$request;
        $request =  $request['message'];
        if (!is_array(value: $request) || count($request) == 0) return [];
        if ($id != null) return (array)$request[0];
        return (array)$request;
    }

    function nonRentNumber($serviceId)
    {
        $request = $this->nonAPiCall($this->non_end_points['order'], ["product_id" => $serviceId]);
        return $request;
    }

    function nonRejectNumber($serviceCode, $type, $number, $orderID)
    {
        $service = $this->nonGetservices($type, $serviceCode);
        $data = ["service" => $service['name'], "number" => $number, "order_id" => $orderID];
        $request = $this->nonAPiCall($this->non_end_points['reject'], $data);
        if ($request->status  == "success") {
            return true;
        }
        return false;
        // exit();
    }

    function nonResuse($serviceName, $number)
    {
        $data = ["service" => $serviceName, "number" => $number];
        return $this->nonAPiCall($this->non_end_points['reuse'], $data);
    }

    function nonActivateNumber($userID, $orderID)
    {
        $order = $this->getall("orders", "userID = ? and ID = ? and type != ? and broker_name = ?", array($userID, $orderID, "short_term", "nonvoipusnumber"));
        if (!is_array($order)) return false;
        $isExpired = $this->numberExpired($order['activate_expire_date'], "UTC");
        if (!$isExpired) return;
        $data = ["service" => $order['serviceName'], "number" => $order['loginIDs'], "order_id" => $orderID];
        $request = $this->nonAPiCall($this->non_end_points['activate'], $data);
        $request = (array)$request;
        if ($request['status'] != "success") return $this->message("We are having issue activating your number please try again.", "error");
        $message = (array)$request['message'];
        if (isset($message[0])) $message = (array)$message[0];
        if (isset($message['end_on'])) {
            $this->update("orders", ["activate_expire_date" => htmlspecialchars($message['end_on'])], "ID = '$orderID'", "Number Activated.");
            return;
        }
    }

    private function nonAPiCall($url, array $data = [])
    {
        $data = json_encode(array_merge($this->non_auth, $data));
        $response = $this->api_call($url, $data, ["Content-Type: application/json"]);
        return $response;
    }

    function nonHandleRentailException($response)
    {
        if (!is_array($response)) $response = (array)$response;
        if ($response['status'] == "error") {
            if ($response['message'] == "Insufficient balance.") return $this->message("We can not take orders.", "error", "json");
            return $this->message("Number not available or Something went wrong.", "error", "json");
        }
        if ($response['status'] == "success") {
            return $response['message'];
        }
    }

    function nonHandleCallBack()
    {
        // Read the raw POST data from the incoming callback
        $rawData = file_get_contents('php://input');
        // Decode the JSON data into a PHP array
        $data = json_decode($rawData, true);
        $data = (array)$data;
        $file = fopen("non_report.log", "a");
        if ($file) {
            fwrite($file, $rawData . PHP_EOL);
            if (count($_POST) > 0) fwrite($file, json_encode($_POST) . PHP_EOL);
            fclose($file);
        }
        if (isset($data['message'])) (array)$data['message'];
        if (isset($data['event']) && $data['event'] == "incoming_message") {
            $message = $data['message'][0] ?? $data['message'];
            if (!is_array($message)) return;
            $this->update_catched_data("nonHandleCallBack", json_encode($message));
            $code = $message['sms'];
            $number = $message['number'];
            if (isset($message['order_id']) && $message['order_id'] != "") {
                $id = $message['order_id'];
                $data = $this->getall("orders", "accountID = ? and loginIDs = ? order by date desc", [$id, $number]);
            } else {
                $data = $this->getall("orders", "loginIDs = ? order by date desc", [$number]);
            }
            if (!is_array($data)) return;
            $id = $data['accountID'];
            $orderID = $data['ID'];
            if ($this->newCode($id, $code, $message['sender'] ?? "", $message['number'])) {
                $this->nonResuse($data['serviceName'], $number);
                return json_encode(["success"]);
            }
            $this->update("orders", ["activate_expire_date" => ""], "ID ='$orderID'");
        }
    }

    // anosim api calls
    protected function anosimGetServices($countryID = 98, $id = "")
    {
        $services = $this->anosimCallApi("products", "&countryId=$countryID", $id);
        if ($id != "") {
            $services = (array)$services;
            $services['name'] = $services['service'];
            unset($services['service']);
            return $services;
        }
        return (array)$this->cleanAnosimData($services);
    }

    function anosimGetBalance()
    {
        $request = $this->anosimCallApi("balance",  method: "GET");
        $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        if (isset($request['accountBalanceInUSD'])) $request['balance'] = $request['accountBalanceInUSD'];
        return $request;
    }

    function anosimRentNumber($id)
    {
        $request = $this->anosimCallApi("order", "&productId=$id&amount=1&providerId=0", method: "POST");
        // if(!is_string($request) || trim($request) === '') return  $this->message("Number not available or Something went wrong. Try another network/service 1", "error", "json");
        $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        if (!is_array($request) || !isset($request['orderBookings'])) return $this->message("Number not available or Something went wrong. Try another network/service 2", "error", "json");
        $request = $request['orderBookings'][0];
        if (!is_array($request)) $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        $info = [];
        $info['expiration'] = (int)$request['durationInMinutes'] * 60;
        $info['ID'] = $request['id'];
        $info['ACCESS_NUMBER'] = $request['number'];
        $info['country'] = $request['country'];
        return $info;
    }
    protected function anosimRequestCodeNumber($id = "")
    {
        $request = $this->anosimCallApi("getsms", id: $id);
        $request = (array)$request;
        foreach ($request as $message) {
            $message = (array)$message;
            if (!isset($message['messageText'])) continue;
            $this->newCode($id, htmlspecialchars($message['messageText']), htmlspecialchars($message['messageSender'] ?? ""), htmlspecialchars($message['simCardNumber'] ?? ""));
        }
    }

    protected function anosimCancel($id)
    {
        // https://anosim.net/api/v1/OrderBookings/59?apikey=XXX
        $request = $this->anosimCallApi("orderbookings", id: $id,  method: "PATCH");
        $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        if (isset($request['success']) && ($request['success'] == true || $request['success'] == "true")) {
            $this->update("orders", ["status" => 2], "accountID = '$id' and broker_name = 'anosim'");
            return true;
        }
        return false;
    }

    function anosmsCountries()
    {
        return $this->anosimCallApi("countries");
    }
    function anosimCallApi($endpoint = "/", $data = "", $id = "", $method = "GET")
    {
        $id = (!empty($id)) ? "/" . $id : $id;
        $url = $this->anosim_end_points["base_url"]
            . $this->anosim_end_points["$endpoint"]
            . "$id?apikey=" . $this->get_settings("anosim_API")
            . $data;
        return $this->api_call($url, method: $method);
    }



    function cleanAnosimData($jsonData): array
    {
        // Decode the JSON into a PHP array
        // $data = json_decode($jsonData, true);
        // $data = $jsonData;
        // Use array_filter and array_map to process the data
        $result = array_map(function ($item) {
            // Change 'service' key to 'name'
            $item = (array)$item;
            $item['name'] = $item['service'];
            unset($item['service']);
            return $item;
        }, array_filter((array)$jsonData, function ($item) {
            $item = (array)$item;
            if (!isset($item['rentalType'])) return $item;
            // Filter only where rentalType is 'Activation'
            return $item['rentalType'] === 'Activation';
        }));

        return $result; // Return the processed array
    }


    // sms-activation-service api
    function getElementById($id, $data)
    {
        $filtered = array_filter($data, function ($item) use ($id) {
            return $item['id'] == $id;
        });

        // Reset array keys and return the first matching element, or null if none found
        return !empty($filtered) ? array_values($filtered)[0] : null;
    }
    function smsActivationGetService($countryCode, $service = null)
    {
        $services = $this->smsActivationAPI("&action=getServicesAndCost&country=$countryCode&operator=any&service=$service&lang=en");
        if (!is_array($services)) return [];
        if ($service != null) return (array)$services[0];
        return $services;
    }
    // sms-activate.io getService
    function smsActivteTwoGetService($countryCode, $service = null)
    {
        $services = $this->smsActivateTwoAPI("&action=getPrices&country=$countryCode&operator=any&service=$service&lang=en");
        if (!isset($services->$countryCode)) return [];
        $services = (array)$services->$countryCode;
        if ($service != null && isset($services[$service])) return (array)$services[$service];
        return $services;
        // if(!isset($services->services)) return [];
        // if($service != null) return (array)$services[0];
        // var_dump($services->services);
        // return $services->services;
    }

    function smsActivateTwoGetServices($countryCode, $serviceCode = null)
    {
        $services = $this->getCookieValue("sms_activate_two_services_" . $countryCode);
        if (($services == null || !is_array($services)) &&  isset($this->sms_activate_Two_services[$countryCode])) {
            $services = $this->sms_activate_Two_services[$countryCode];
        }
        if ($services == null || !is_array($services)) {
            $services = $this->smsActivateTwoAPI("&action=getServicesList&country=$countryCode");
            if (!isset($services->services)) return "unknown";
            $services = (array)$services->services;
            $this->sms_activate_Two_services[$countryCode] = $services;
            $this->setCookieValue("sms_activate_two_services_" . $countryCode, $services);
        }
        if ($serviceCode != null) {
            $service = array_search($serviceCode, array_column($services, 'code'));
            $service = (array)$services[$service];
            return $service['name'];
        }
        // var_dump($services);
        return $services;
    }

    protected function smsActivationrequestCodeNumber($id)
    {
        $result = $this->smsActivationAPI("&action=getStatus&id=$id&lang=en", isRaw: true);
        $result = $this->handleRentailException($result);
        if (!is_array($result) || !isset($result['serviceCode'])) return;
        if ($result['serviceCode'] == "STATUS_CANCEL") return;
        if ($result['serviceCode'] == "STATUS_WAIT_CODE") return;
        $code = $result['serviceCode'];
        if ($this->newCode($id, $code)) return true;
        return;
    }

    protected function smsActivationCancel($id, $status = 8)
    {
        $services = $this->smsActivationAPI("&action=setStatus&id=$id&status=$status&lang=en", isRaw: true);
        // var_dump($services);
        if ($services == "EARLY_CANCEL_DENIED" || $services == "CANNOT_BEFORE_2_MIN") {
            $this->message("You can not cancel this number at the moment", "error");
            return false;
        }
        return $this->message("Number status updated.", "success", "json");
    }

    // rent number
    function smsActivationGetNumber($serviceID, $countryCode = null)
    {
        $request = $this->smsActivationAPI("&action=getNumber&service=$serviceID&country=$countryCode&lang=en", isRaw: true);
        return $this->handleRentailException($request);
    }

    function getCountry($broker)
    {
        $countries = json_decode(base64_decode($this->get_settings($broker, where: 'catched_data')));
        if ($countries != "" && $countries != null && $countries != '[]') {
            return $countries;
        }
        if (method_exists($this, $broker)) {
            $countries = $this->$broker();
        }
        if (is_array($countries)) $this->update_catched_data($broker,  $countries);
        return $countries;
    }

    function update_catched_data($key, $data)
    {
        $data = is_array($data) ? base64_encode(json_encode($data)) : base64_encode($data);
        $record = $this->getall("catched_data", 'meta_name = ?', [$key]);
        if (is_array($record)) {
            $data = ["meta_value" => $data, "date" => date('Y-m-d H:i:s')];
            if ($data['meta_value'] != $record['meta_value']) $this->update("catched_data", $data, "meta_name = '$key'");
            return true;
        }
        $this->quick_insert("catched_data", ["meta_name" => $key, "meta_value" => $data, "meta_for" => "all", "date" => date('Y-m-d H:i:s')]);
    }


    function smsActivationCountries()
    {
        $countries = $this->getCookieValue("smsActivationCountries");
        if ($countries != "" && $countries != null) return unserialize(base64_decode($countries));
        $countries = $this->smsActivationAPI("&action=getCountryAndOperators&lang=en");
        $this->setCookieValue("smsActivationCountries", base64_encode(serialize($countries)));
        return (array)$countries;
    }

    function smsActicateTwoCountries()
    {
        $countries = $this->getCookieValue("smsActivateTwoCountries");
        if ($countries != "" && $countries != null) return unserialize(base64_decode($countries));
        $countries = $this->smsActivateTwoAPI("&action=getCountries&lang=en");
        $this->setCookieValue("smsActivateTwoCountries", base64_encode(serialize($countries)));
        return (array)$countries;
    }

    function smsActivateTwoGetNumber($service, $countryCode, $amount)
    {
        $request = $this->smsActivateTwoAPI("&action=getNumberV2&service=$service&country=$countryCode&maxPrice=$amount");
        // var_dump($request);
        return $this->handleNumberResponse($request);
    }

    function handleNumberResponse($request)
    {
        if (!is_array($request)) $request = (array)$request;
        if (isset($request[0])) $request = $request[0];
        if (!isset($request['phoneNumber'])) return false;
        $info = [];
        $info['ID'] = $request['activationId'];
        $info['ACCESS_NUMBER'] = $request['phoneNumber'];
        $info['country'] = $request['countryCode'];
        return $info;
    }

    function smsActivationAPI($params, $method = "GET", $isRaw = false)
    {
        $url =  $this->sms_end_points['base_url'] . "?api_key=" . $this->get_settings("sms_activation_API") . $params;
        return $this->api_call($url, method: $method, isRaw: $isRaw);
    }
    function smsActivateTwoAPI($params, $method = "GET", $isRaw = false)
    {
        $url =  $this->sms_end_points['sms_activate_two_base_url'] . "?api_key=" . $this->get_settings("sms_activate_two_API") . $params;
        $request =  $this->api_call($url, method: $method, isRaw: $isRaw);
        // var_dump("message".$request);
        return $request;
    }

    //  sms bower api
    function smsBowerServices($countryID, $service = null)
    {
        $request = $this->smsBowerCallApi("&action=getPricesV2&country=$countryID&service=$service");
        // var_dump($request);
        $request = (array)$request;
        if (!isset($request[$countryID])) return [];
        return (array)$request[$countryID];
    }
    function smsBowerGetNumber($service, $countryCode, $amount)
    {
        $request = $this->smsBowerCallApi("&action=getNumberV2&service=$service&country=$countryCode&maxPrice=$amount");
        // var_dump($request);
        return $this->handleNumberResponse($request);
    }

    protected function smsBowerRequestCodeNumber($id)
    {
        $url = "&action=getStatus&id=$id";
        $result = $this->smsBowerCallApi($url, isRaw: true);
        return $this->handleCode($result, $id);
    }

    protected function smsBowerMakeNumberAsDone($id, $status = 0)
    {
        $url = "&action=setStatus&id=$id&status=8";
        $request = $this->smsBowerCallApi($url, isRaw: true);
        if ($request == "EARLY_CANCEL_DENIED" || $request == "CANNOT_BEFORE_2_MIN") {
            $this->message("You can not cancel this number at the moment", "error");
            return false;
        }
        return $this->message("Number status updated.", "success", "json");
    }

    function smsBowerCountries()
    {
        $jsonFile = file_get_contents("countrie/smsbowercountries.json");
        $countries = json_decode($jsonFile, true);
        return $countries;
    }
    function getLowestPrice(array $data, $minCount = 10, $maxPrice = 200)
    {
        // Filter the array based on the conditions ( count >= $minCount and price <= $maxPrice)
        $filteredData = array_filter($data, function ($count, $price) use ($minCount, $maxPrice) {
            return $count >= $minCount && $price <= $maxPrice;
        }, ARRAY_FILTER_USE_BOTH);
        // Get the minimum price from the filtered data
        $lowestPrice = $filteredData ? min(array_keys($filteredData)) : null;
        return $lowestPrice !== null ? $lowestPrice : 0;
    }

    function getKeyStats($data)
    {
        // Filter keys where the value is greater than 15 and the key is less than or equal to 190
        $filteredKeys = array_filter(array_keys($data), function ($key) use ($data) {
            return $data[$key] > 15 && $key <= 190;
        });

        // Check if any keys meet the criteria
        if (!empty($filteredKeys)) {
            // Convert the keys to float for numeric operations
            $filteredKeys = array_map('floatval', $filteredKeys);

            // Calculate the lowest and highest key
            $lowestKey = min($filteredKeys);
            $highestKey = max($filteredKeys);
            $averageKey = array_sum($filteredKeys) / count($filteredKeys);

            // Find the key closest to the average
            $closestKey = null;
            $minDiff = PHP_INT_MAX;
            foreach ($filteredKeys as $key) {
                $diff = abs($key - $averageKey);
                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closestKey = $key;
                }
            }

            // Return the results, including the closest to average
            return [
                'lowest' => $lowestKey,
                'closest_to_average' => $closestKey,
                'highest' => $highestKey
            ];
        } else {
            // Return null if no keys meet the criteria
            return null;
        }
    }


    private function smsBowerCallApi($params = "", $id = "", $method = "GET", $isRaw = false)
    {
        $url =  $this->sms_end_points['sms_bower'] . "?api_key=" . $this->get_settings("sms_bower_API") . $params;
        $request =  $this->api_call($url, method: $method, isRaw: $isRaw);
        return $request;
    }


    function getKeyValue($countryName, $path = 'countrie/countries.json', $key = null)
    {
        // Read the JSON file
        if (!file_exists($path)) $path = "../$path";
        $jsonFile = file_get_contents($path);
        // Decode the JSON data into an associative array
        $countries = json_decode($jsonFile, true);
        // Loop through the array to find the country by name
        if ($key == null && isset($countries[$countryName])) return $countries[$countryName];
        if ($key == null && !isset($countries[$countryName])) return "";
        foreach ($countries as $country) {
            if (strtolower($country[$key]) == strtolower($countryName)) {
                return $country['code']; // Return the country code if found
            }
        }
        return null; // Return null if the country name is not found
    }
}