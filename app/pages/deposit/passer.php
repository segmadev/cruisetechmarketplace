<?php
if (isset($_POST['ini_payment'])) {
    // echo '{"message":["Sucess","Redirecting...","success"],"function":{"0":"loadpage","data":["https:\/\/checkout.flutterwave.com\/v3\/hosted\/pay\/flwlnk-01jfw4sd4ra5edbmhc5zkg4gad","success"]}}';
    // die();
    echo $de->ini_payment($userID);
}

if (isset($_GET['get_balance'])) {
    echo $d->money_format($user['balance']);
}
if (isset($_GET['get_payments'])) {
    $payment = $de->get_payments($userID, htmlspecialchars($_POST['start'] ?? 0));
    if ($payment->rowCount() < 0) {
        $return = ["status" => "null", "data" => ""];
        echo json_encode($return);
    } else {
        $contentHtml = "";
        foreach ($payment as $pay) {
            require "pages/deposit/pay_table.php";
        }
        return;
    }
}
