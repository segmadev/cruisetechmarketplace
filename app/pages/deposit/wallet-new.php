<?php
$script[] = "wizard";
$script[] = "fetcher";
$script[] = "qrcode";
if(isset($_GET['amount']) && $_GET['amount'] != "") {
    $deposit_form['input_data']['amount'] = (float)htmlspecialchars($_GET['amount']);
    $deposit_form['amount']['atb'] = 'disabled';
}
?>
<style>
    ul[aria-label="Pagination"] {
        display: none!important;
    }
    .clearfix {
        background-color: transparent!important;
    }
</style>
<script src="qrcodejs/qrcode.min.js"></script>
<div class="col-12">
    <div class="">
        <div class="">
            <div class="card-header">
                <h5>Deposit</h5>
                <p>Make deposit into your account.</p>
            </div>
            <!-- <h4 class="card-title mb-0"></h4> -->
            <h6 class="card-subtitle mb-3"></h6>
            <?php if (!$de->get_deposit_max($userID)) { ?>
                <section>
                <form id="foo" action="passer" class="validation-wizard wizard-circle mt-5">

                    <!-- Step 1 -->
                    <!-- <h6>Deposit Amount</h6> -->
                        <div class="row card-body mt-0 bg-transparent">
                            <?php
                            echo $c->create_form(["ID" => $deposit_form['ID'], "userID" => $deposit_form['userID'], "input_data" => $deposit_form['input_data']]);
                            echo $c->create_form(["amount" => $deposit_form['amount'], "input_data"=>$deposit_form['input_data']]); ?>

                            <!-- Step 2 -->
                            <h6>Select Wallet</h6>

                            <input type="hidden" name="page" value="deposit">
                            <input type="hidden" name="newdeposit" value="deposit">
                            <?php
                            echo $c->create_form(["wallet" => $deposit_form['wallet']],);
                            echo "<div id='display-wallet-info'></div>";
                            echo $c->create_form(["prove_image" => $deposit_form['prove_image']],);
                            ?>
                        </div>
                        <div id="custommessage"></div>
                        <input type="submit" value="Make Deposit" class='btn-lg btn btn-primary'>
                        <!-- Step 3 -->
                </form>
                    </section>
            <?php } else {
                echo $c->empty_page("You have at least " . $d->get_settings("deposit_max") . " pending deposit. <br> Please wait for approval before you make a new deposit", "", "Pending Deposit Limit");
            } ?>
        </div>
    </div>
</div>