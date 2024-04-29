<?php
require_once "functions/investment.php";
$i = new investment;
$no = htmlspecialchars($_GET['start'] ?? 0);
$trades = $d->getall("trades", "userID = ? and Xpromo > ? and xpromo is not null  order by trade_time DESC limit $no,  20", [$userID, 0], fetch: "moredetails");
$trade_table_title = "Trades with promo";
$trade_table_des = "All trades where promo are applied on";

$promo = $d->getall("promo_assigned", "userID = ? and end_date >= ? and status = ?", [$userID, time(), "active"]);