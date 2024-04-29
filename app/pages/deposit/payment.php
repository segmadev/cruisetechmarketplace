<?php 
    $NewPayment = $d->api_call("https://coinremitter.com/api/v3/BTC/get-invoice", 
    ["api_key"=>"$2y$10$/jc.UzCXwul3XfAHoh2kYu95iaDBNgde1SCfbxcb3ec7JWDnYNkF.", "password"=>"Danny@584", "invoice_id"=>"BTC001"]);
    var_dump($NewPayment);
?>