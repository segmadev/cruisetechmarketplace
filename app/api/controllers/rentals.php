<?php
require_once ROOT . "functions/rentals.php";
require_once "controllers/user.php";
class ApiRentals extends rentals
{
    public $user;
    public function __construct()
    {
        // Call parent constructor to set the name
        parent::__construct();
        $this->user = new ApiUser;
    }
    function getTypes()
    {
        $types = [
            ["type" => "short_term", "network" => 1, "title" => "Short Term USA Number 1"],
            ["type" => "short_term", "network" => 2, "title" => "Short Term USA Number 2"],
            ["type" => "short_term", "network" => 3, "title" => "Germany & Netherlands (Short Term)"],
            ["type" => "short_term", "network" => 5, "title" => "All Countries (Short Term 1)"],
            ["type" => "short_term", "network" => 6, "title" => "All Countries (Short Term 2)"],
            ["type" => "3days", "network" => 1, "title" => "3days Number (USA)"],
            ["type" => "long_term", "network" => 1, "title" => "Long Term Number (USA) (30days)"],
            ["type" => "long_term", "network" => 2, "title" => "LTR (Network 2) (USA) (30days)"],
        ];
        return $this->apiMessage("Types Fetched", 200, $types);
    }

    function get_countries($what = "countries")
    {
        $number_type = htmlspecialchars($_GET['type'] ?? "short_term");
        $countryCode = htmlspecialchars($_GET['countryID'] ?? $_GET['countryCode'] ?? 98);
        $network = (int)htmlspecialchars($_GET['network'] ?? 1);
        $renta_details = $this->get_services($number_type, $network, $countryCode, $what);
        // var_dump( $renta_details);
        if ($what == "countries" && is_array($renta_details['countries'])) {
            $countries = [];
            foreach ($renta_details['countries'] as $country) {
                //    var_dump($country);
                $country = $this->cleanUpCounty((array)$country, $network);
                // continue;
                if (!is_array($country)) continue;
                $countries[] = $country;
            }
            $renta_details['countries'] = $countries;
        }

        if ($what == "services" && is_array($renta_details['services'])) {
            $renta_details['services'] = $this->filter_services($renta_details['services'], $number_type, $renta_details['broker']);
        }
        unset($renta_details['broker']);
        return $this->apiMessage("$what fetched", 200, $renta_details);
    }

    function newNumberOrder()
    {
        $id = htmlspecialchars($_GET['id'] ?? $_GET['ID'] ?? '');
        if ($id == "") return $this->apiMessage("ID not passed or invaild", 400);
        // $_POST['userID'] = ;
        $IDs = explode("||", base64_decode($id));
        // var_dump($IDs);
        if (count($IDs) != 5) return $this->apiMessage("Invaild ID", 400);
        // "$key||$number_type||$countryCode||$network||$brokerKey"
        $_POST['id'] = $IDs[0];
        $_POST['broker'] = base64_encode($this->brokers[$IDs[4]]);
        $_POST['type'] = $IDs[1];
        $_POST['countryCode'] = $IDs[2];
        $number = $this->newNumber($this->user->userID, true);
        // die(var_dump($number));
        if (is_array($number) && isset($number['number'])) {
            return $this->apiMessage("Number booked", 200, $number);
        }
        return $this->handleJsonMessage($number);
    }

    function getCode()
    {
        $order = $this->get_order();
        if (!is_array($order)) return $this->apiMessage("Order not found", 400);
        $codes = $this->getNumberCode($order);
        if ($codes == "" || $codes == null)  return $this->apiMessage("No Code yet.", 200);
        $data = [];
        foreach ($codes as $code) {
            $thisCode = [];
            $thisCode['ID'] = $code['ID'];
            $thisCode['phone_number'] = $code['phone_number'];
            $thisCode['message'] = $code['NumberCode'];
            $thisCode['sender'] = $code['sender'];
            $data[] = $thisCode;
        }
        return count($data) > 0 ? $this->apiMessage("Code received", 200, $data) : $this->apiMessage("No Code yet.", 200);
    }

    function get_order($isApi = "no", $id = null)
    {
        if ($id == null && !isset($_GET['id'])) {
            die($this->apiMessage("No ID passed", 401));
        }
        $id = $id ?? htmlspecialchars($_GET['id'] ?? $_GET['ID']);
        $order = $this->getall("orders", "ID = ? and userID = ?", [$id, $this->user->userID]);
        if (!is_array($order)) die($this->apiMessage("Order not found", 400));
        if ($isApi == "no") return $order;
        return $this->apiMessage("Order fetched", 200, $this->cleanOrder($order));
    }

    function close_rental()
    {
        $order = $this->get_order();
        if (!is_array($order)) return $this->apiMessage("Order not found", 400);
        if ($this->closeRental($this->user->userID, $order['ID'])) {
            return $this->apiMessage("Status Updated", 200);
        }
        return $this->apiMessage("Unable to update status", 401);
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