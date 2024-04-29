<?php
require_once "pages/users/ini.php";
require_once "pages/deposit/ini.php";
// Recent users
$users = $d->getall("users", "ID != ? ORDER BY date DESC LIMIT 5", [""], fetch: "moredetails");
$users_data = $u->users_data();
$pay_table = $d->getall("payment", "ID != ?  ORDER BY date DESC LIMIT 5", [""], fetch: "moredetails");
$accounts = $d->getall("account", "ID != ?  ORDER BY date DESC LIMIT 5", [""], fetch: "moredetails");
  
// var_dump($users_data);
// no of trades taken today
// total profit  made today
// no of users
// number of investment
// total amount invest
