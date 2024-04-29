<?php 
$rfa = [
    "ID"=>["input_type"=>"number"],
    "userID"=>["unique"=>""],
    "referral_code"=>["unique"=>"userID"],
    "status"=>[],
    "percentage_amount"=>[],
    "input_data"=>["status"=>"pending"]
];
$d->create_table("referral_allocation", $rfa);
$ref_programes = $d->getall("referral_programs", "status = ? order by plan_amount ASC", ['active'], fetch: "moredetails");
if(isset($_GET['id']) && $_GET['id'] != ""){
    $ref = $d->getall("referrals", "id = ? and userID = ?", [htmlspecialchars($_GET['id']), $userID]);
    $ref_a = $d->getall("referral_allocation", "referral_code = ?", [$ref['referral_code']], fetch: "moredetails");
    $refs = $d->getall("referrals", "id != ? and userID = ?", [htmlspecialchars($_GET['id']), $userID], fetch: "moredetails");
}else{
    $refs = $d->getall("referrals", "userID = ?", [$userID], fetch: "moredetails");
}

