<?php 

if(!isset($ref_p_s['ID'])){
    unset($referral_from["ID"]);
}else{
    $referral_from["ID"]["input_type"] = "hidden";
}
$referral_from["input_data"] = $ref_p_s;