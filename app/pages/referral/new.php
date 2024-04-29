<?php 
    if(isset($_GET['activate']) && !empty($_GET['activate'])){
        $referralID = htmlspecialchars($_GET['activate']);
        if($u->activate_referral($userID, $referralID)){
            $code = htmlspecialchars($_POST['referral_code']);
            $refs = $d->getall("referrals", "referral_code = ?", [$code], fetch: "details");
            echo "<b class='text-warning'>Please wait...</b>";
            $d->loadpage("index?p=referral&action=view&id=".$refs['ID']);
        }
    }
?>