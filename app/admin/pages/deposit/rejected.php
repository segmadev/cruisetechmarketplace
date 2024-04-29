<?php
if (!isset($details) || !is_array($details)) {
    echo $c->empty_page("We can not find a deposit that match the ID passed. <br> Maybe it is deleted or closed. <br> If you feel this is not correct reload page and try again", "", "Deposit Not found");
    exit();
}
$reject_from = [
    "ID" => ["input_type" => "hidden"],
    "status" => ["input_type" => "hidden"],
    "amount" => ["input_type" => "text", "atb" => "disabled"],
    "updatedeposit" => ["input_type" => "hidden"],
    "page" => ["input_type" => "hidden"],
    "reason"=> ["type"=>"textarea", "description"=>"Tell the user why are you rejecting the deposit? Note: it is optinal", "global_class"=>"w-100"],
    "input_data"=>["status"=>"rejected", "amount"=>$details['amount'], "ID"=>$details['ID'], "page"=>"users"]
];
?>

<form action="" id="foo" onsubmit="return false;">
    <?php echo $c->create_form($reject_from); ?>
    <div id="custommessage"></div>
    <button type="submit" class="btn btn-danger" href="#">Reject Deposit</button>
</form>