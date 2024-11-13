<?php
require_once "../consts/user.php";
require_once "../functions/users.php";
require_once "functions/users.php";
require_once "../functions/investment.php";
require_once "../functions/wallets.php";
require_once "../functions/deposit.php";
$de = new deposit;
$i = new investment;
$u = new users;
if (isset($_GET['action'])) {
    $route = htmlspecialchars($_GET['action']);
}

if($action == "list" || $action == "table") {
    $acct_type = htmlspecialchars($_GET["acct_type"] ?? "all");
    if($acct_type == "all") {
        $users = $d->getall("users", "ID != ? order by date desc limit 1", [""], fetch: "moredetails");
    }else{
        $users = $d->getall("users", "acct_type = ? order by date desc limit 1", [$acct_type], fetch: "moredetails");
    }
}



if(isset($_GET['download_profile'])) {
    $u->download_profile();
}

if(isset($_GET['make_profile_send_message'])) {
    $u->make_profile_send_message();
}

if(isset($_GET['id'])  && !empty($_GET['id'])) {
    $_GET['date'] = date("Y-m-d");
    $userID = htmlspecialchars($_GET['id']);
    $user = $d->getall("users", "ID = ?", [$userID]);
    $user_data = $u->user_data($userID);
    $deposit = $d->getall("deposit", "userID = ? order by date DESC LIMIT 10", [$userID], fetch: "moredetails");
    if($action == "transactions") {
        $transactions = $d->getall("transactions", "userID = ? order by date DESC LIMIT 100", [$userID], fetch: "moredetails");
    }else{
        $transactions = $d->getall("transactions", "userID = ? order by date DESC LIMIT 10", [$userID], fetch: "moredetails");
    }
}

$transfer_from = [
    "type"=>["type"=>"select", "options"=>["credit"=>"credit", "debit"=>"debit"], "global_class"=>"w-100"],
    "action_on"=>["type"=>"select", "options"=>["balance"=>"Balance"], "global_class"=>"w-100"],
    "amount"=>["input_type"=>"number", "global_class"=>"w-100"],
    "userID"=>["input_type"=>"hidden", "global_class"=>"w-100"],
    "session_ID"=>["global_class"=>"w-100", "is_required"=>false],
    "for"=>["type"=>"textarea", "title"=>"Reason for Action", "is_required"=>false, "global_class"=>"w-100"],
];

$transfer_from['input_data']["action_on"] = "balance";