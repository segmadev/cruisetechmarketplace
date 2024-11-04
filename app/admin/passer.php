<?php 
session_start();
    require_once "inis/ini.php";
    if(isset($_POST['page'])) {
        $pageexclude = "yes";
         $page = htmlspecialchars($_POST['page']);
        require_once "pages/page-ini.php";
        // match ($page) {
        //    "deposit", "deposits"  => require_once "inis/deposit-ini.php",
        //    "plan", "plans", "plandetails"  => require_once "inis/plans-ini.php",
        //    "setting", "settings"  => require_once "inis/settings-ini.php",
        //    "wallets","wallet" => require_once "include/ini-wallets.php",
        //    ""=>null,
        // };
    }

    // if(isset($_POST['page'])) {
    //     $page = htmlspecialchars($_POST['page']);
    //     match ($page) {
    //        "user", "users", "userdetails"  => require_once "inis/users-ini.php",
    //        "plan", "plans", "plandetails"  => require_once "inis/plans-ini.php",
    //        "email_template"  => require_once "inis/email_template-ini.php",
    //        "setting", "settings"  => require_once "inis/settings-ini.php",
    //        "wallets","wallet" => require_once "inis/wallets-ini.php",
    //        "deposit" => require_once "inis/deposit-ini.php",
    //        ""=>null,
    //     };
    // }
    if(file_exists("pages/$page/passer.php")) {
        require_once "pages/$page/passer.php";
    }
    
    // FAQ
    if(isset($_POST['new_faq'])){
       echo $f->add_faq($faq_data);
    }
    // compound_profits 
    if(isset($_POST['new_compound_profits'])) {
        echo $p->new_compound_profits($compound_profits_form);
    }
    if(isset($_POST['edit_compound_profits'])) {
        $compound_profits_form["ID"] = [];
        echo $p->update_compound_profits($compound_profits_form);
    }
    // Transfer 
    if(isset($_POST['transfer'])) {
        // echo "yes";
        $u->admin_transfer($transfer_from);
     }
    // promo
    if(isset($_POST['new_promo'])) {
        echo $p->new_promo($promo_form);
    }
    if(isset($_POST['edit_promo'])) {
        $promo_form["ID"] = [];
        echo $p->update_promo($promo_form);
    }

    // deposit 
    if(isset($_POST['updatedeposit'])) {
        echo $u->update_deposit_status();
    }
    // update KYC status
    if(isset($_POST['update_kyc'])) {
        echo $u->update_kyc(["ID"=>[], "kyc_status"=>[]]);

    }
    if(isset($_POST['edit_email_template'])) {
        echo $e->update_template();
    }
    if(isset($_POST['updatesettings'])) {
        $return = $s->message("You can not perfrom this action", "error", "json");
        if($r->validate_action(["settings"=>$_POST['settings']])) {
            $return = match (htmlspecialchars($_POST['settings'])) {
                "settings"=>$s->update_settings($settings_form) ,
                "Withdraw"=>$s->update_settings($settings_withdraw_form) ,
                "deposit"=>$s->update_settings($settings_deposit_form) ,
                "term_and_policy_condition"=>$s->update_settings($term_and_policy_condition) ,
                "logo"=>$s->update_settings($logo_from),
                "compound_profits"=>$s->update_settings($compound_profits_details),
                "social_media"=>$s->update_settings($settings_social_media),
                "seo"=>$s->update_settings($settings_seo),
                "help"=>$s->update_settings($settings_help),
                "backup"=>$s->update_settings($settings_backup),
                "rentals"=>$s->update_settings($rentals_settings),
                "edit_admin"=>$s->edit_admin($adminID, $admin_account),
           };
        }
       
        echo $return;
    }

    if(isset($_POST['updatecontent'])) {
        $key = htmlspecialchars($_POST['content']);
        if(!$r->validate_action(["content"=>"list"], true)) return ;
        if(isset(${"content_".$key})) {
            echo $s->update_settings(${"content_".$key}, "content");
        }
    }

    if(isset($_POST['newdetails'])) {
        $what = htmlspecialchars($_POST['newdetails']);
        echo $s->new_details(${$what}, $what);
    }
    if(isset($_POST['updatedetails'])) {
        $what = htmlspecialchars($_POST['updatedetails']);
        echo $s->edit_details(${$what}, $what);
    }
    if(isset($_POST['deletedetails'])) {
        $what = htmlspecialchars($_POST['deletedetails']);
        echo $s->remove_details(htmlspecialchars($_POST['ID']), $what);
    }

    // users
    if(isset($_POST['newuser'])) {   
        echo $u->newuser($user_form);
        
    }

    if(isset($_POST['block_chat_account'])) {
        echo $u->block_user(htmlspecialchars($_POST['block_chat_account']), "chat_status");
    }
    if(isset($_POST['block_account'])) {
        echo $u->block_user(htmlspecialchars($_POST['block_account']));
    }

    if(isset($_POST['newwallet'])) {   
        echo $w->new_wallet($wallet_from);
    }
    if(isset($_POST['editwallet'])) {   
        echo $w->edit_wallet($wallet_from);
    }

    if(isset($_POST['newplan'])) {
        echo $p->new_plan($plans_form);
    }
    if(isset($_POST['editplan'])) {
        echo $p->edit_plan($plans_form);
    }

    if(isset($_POST['delete_wallet'])) {
        echo $w->delete_wallet("admin");
    }
    if(isset($_POST['what'])) {
       $what = htmlspecialchars($_POST['what']);
       if($what == "user"){
            echo $u->user_list(start: htmlspecialchars($_POST['start']));
       }

       if($what == "userdetails") {
        if(isset($_POST['ID'])) {
            echo $u->user_list(start: htmlspecialchars($_POST['start']), id: htmlspecialchars($_POST['ID']), type: "details");
        }else {
            echo $u->user_list(start: htmlspecialchars($_POST['start']), type: "details");
        }
       }
    }
?>