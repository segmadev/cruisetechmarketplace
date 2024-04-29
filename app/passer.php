<?php 
    session_start();
    require_once "include/ini.php"; 
    if(isset($_POST['page'])) {
        $pageexclude = "yes";
        $page = htmlspecialchars($_POST['page']);
        require_once "pages/page-ini.php";
    }

    $_POST['userID'] = $userID;
    if(file_exists("pages/$page/passer.php")) {
        require_once "pages/$page/passer.php";
    }

    // profile and settings
    // change profile pic
    if(isset($_POST['change_profile_pic'])){
        echo $u->change_profile_pic($userID);
    }

    // change password
    if(isset($_POST['change_password'])) {
        echo $u->change_password($change_password_from, $userID);
    }
    // update profile
    if(isset($_POST['update_profile'])) {
        echo $u->update_profile($profile_form, $userID);
    }
    // upload KYC
    if(isset($_POST['upload_kyc'])) {
       echo $u->upload_kyc($kyc_form, $userID);
    }
    if(isset($_POST['new_compound_profits'])) {
        echo $i->activate_compound_profits($compound_profits_form);
    }

    if(isset($_POST['update_compound_profits'])) {
         $id = htmlspecialchars($_POST['update_compound_profits']);
         $status = htmlspecialchars($_POST['status']);
        echo $i->pause_compound_profits($id, $status, $userID);
    }
 
    if(isset($_POST['new_wallet'])) {
        echo $w->new_wallet($wallet_from);
    }
    
    if(isset($_POST['editwallet'])) {   
        echo $w->edit_wallet($wallet_from);
    }

    if(isset($_POST['delete_wallet'])) {
        echo $w->delete_wallet($userID);
    }

    if(isset($_POST['transfer_funds'])) {
        echo $u->transfer_funds($tranfer_from);
    }
 
    if(isset($_POST['newdeposit'])) {   
        echo $de->new_deposit($deposit_form, $userID);
        
    }

    if(isset($_POST['new_withdraw'])) {
        echo $w->new_withdraw($withdraw_form);
    }

    if(isset($_POST['newinvestment'])) {
        $type = "tranding_balance";
        if(isset($_SESSION['newuser'])){
            $type = "quick";
        }
        echo $i->new_investment($investment_form, $type);
    }

    if(isset($_POST['what'])) {
        $variable = htmlspecialchars($_POST['what']);
        switch ($variable) {
            case 'wallet':
                if(!isset($_POST['ID'])) { echo "No data found"; break;}
                $wallet = $d->getall("wallets", "ID = ?", [htmlspecialchars($_POST['ID'])]);
                echo $w->wallet_detail_widget($wallet);
                break;
            case "account":
                    $start = htmlspecialchars($_POST['start']) ?? 0;
                    
                    $accounts = $a->fetch_account($start);
                    if($accounts->rowCount() > 0) {
                        $contentHtml = "";
                        foreach($accounts as $account) {
                            $contentHtml .= $a->display_account($account, $userID ?? null);
                            // $contentHtml .= $a->display_account($account);
                        }
                        $return = ["status"=>"ok", "data"=>"$contentHtml"];
                        echo json_encode($return);
                        return ;
                    }
                    $return = ["status"=>"null", "data"=>""];
                    echo json_encode($return);
                    return ;
                break;
            default:
                echo "";
                break;
        }
    }
?>