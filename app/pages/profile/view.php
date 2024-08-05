<?php 
// URF_1722766743867_4265735
    require_once 'vendor/autoload.php';
    use Flutterwave\Helper\Config;
    use Flutterwave\Flutterwave;
    use Flutterwave\Service\VirtualAccount;
    $myConfig = Config::setUp(
        $d->get_settings("flutterwave_secret_key"),
        $d->get_settings("flutterwave_public_key"),
        $d->get_settings("flutterwave_encyption_key"),
        'staging'
    );
    Flutterwave::bootstrap($myConfig);
    $service = new VirtualAccount(config: $myConfig);
    $tx_ref = "$userID".time();
    $payload = [
        "email" => "hi@segma.dev",
        "tx_ref"=>$tx_ref,
        "bvn"=>"22420553704",
        "narration"=>"John Smith",
        "is_permanent" => true
    ];
    
    // $response = $service->create($payload);
    $response = $service->delete("URF_1722776038903_6874735");
    var_dump($response);
    if($response->status == "success" || $response->status == "successfull") {
        $response = (array)$response;
    }

    // $u->create_vitual_account($userID);
?>