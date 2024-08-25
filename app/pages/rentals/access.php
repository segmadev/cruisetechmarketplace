<?php
// $postDetails = json_encode(["email"=>"serikioluwagbenga@gmail.com", "password"=>"Admin111"]);
$postDetails = json_encode(["email"=>"alaooluwafunkemercy@gmail.com", "password"=>"Alao3026", "type"=>"long_term"]);
 $request = $d->api_call("https://nonvoipusnumber.com/manager/api/products", $postDetails, ["Content-Type: application/json"]);
//  var_dump($request);