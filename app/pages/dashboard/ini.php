<?php
require_once "functions/users.php";
require_once "functions/deposit.php";
$u = new user;
$de =  new deposit;
$_GET['date'] = date("Y-m-d");
$deposit = [];
// $deposit = $d->getall("deposit", "userID = ? order by date DESC LIMIT 5", [$userID], fetch: "moredetails");
$script[] = "fetcher";
    // var_dump($users_data);