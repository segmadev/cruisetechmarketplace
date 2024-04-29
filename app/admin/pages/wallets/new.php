<?php require_once "../content/textarea.php"; ?>
<div class="card col-12">
    <div class="border-bottom title-part-padding">
        <h4 class="card-title mb-0">Create New Wallet</h4>
    </div>
    <div class="card-body">
    <form class="mt-4" action="" id="foo"  novalidate="">
        <div class="row">
            <?php 
            $wallet_from['input_data']['userID'] = "admin";
            echo $c->create_form($wallet_from); ?>
            <input type="hidden" name="newwallet" value="" id="">
            <input type="hidden" name="page" value="wallets" id="">
        </div>
        <div id="custommessage"></div>
        <input type="submit" value="Submit" class="btn btn-primary col-3">
    </form>
    </div>
</div>