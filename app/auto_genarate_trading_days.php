<?php 
    require_once "include/cron-ini.php";
    $i = new investment;
    if(isset($_GET['update'])) {
        $i->change_trade_date();
        exit;
    }

    if(isset($_GET['type']) && $_GET['type'] == "bot") {
        // $i->generate_trade_per_day();
        // auto_genarate_trading_days("bot", (int)$rand_no - (int)$check, $last_date)
        $i->auto_genarate_trading_days(htmlspecialchars($_GET['type']), rand(200, 300), date("Y-m-d"));   
    }else{
        $i->auto_genarate_trading_days();   
    }
    
?>