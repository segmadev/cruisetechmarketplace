<?php 
require_once "consts/Regex.php";
require_once "admin/include/database.php"; 
$d = new database;
require_once "consts/general.php";
require_once "content/content.php"; 
require_once "functions/users.php"; 
$u = new user;
$c = new content;
require_once "functions/investment.php";
$i = new investment;

// $i->apply_daily_compound_profits();
// $i->auto_genarate_trading_days();
$i->take_pending_trades();