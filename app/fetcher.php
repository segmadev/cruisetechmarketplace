<?php 
    session_start();
    require_once "include/ini.php"; 
    if(isset($_GET['p'])) {
        $pageexclude = "yes";
        $page = htmlspecialchars($_GET['p']);
        require_once "pages/page-ini.php";
        if(isset($script['fetcher'])) { unset($script['fetcher']); }
    }

    if(isset($_POST['what'])) {
        $variable = htmlspecialchars($_POST['what']);
        switch ($variable) {
            case 'wallet':
                if(!isset($_POST['ID'])) { echo "No data found"; break;}
                $wallet = $d->getall("wallets", "ID = ?", [htmlspecialchars($_POST['ID'])]);
                echo $w->wallet_detail_widget($wallet);
                break;
            case "trades":
                    if(!isset($_GET['tradeID']) && isset($trades)){
                        require_once "pages/investment/trade_list_table.php";
                    }
                break;
            default:
                echo "No data found";
                break;
        }
    }


    

?>