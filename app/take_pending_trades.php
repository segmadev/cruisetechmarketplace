<?php 
require_once "include/cron-ini.php";
$no = htmlspecialchars($_GET['no'] ?? 25);
$i->take_pending_trades($no, htmlspecialchars($_GET['type'] ?? "user"), htmlspecialchars($_GET['order'] ?? "ASC"));