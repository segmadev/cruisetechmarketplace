<div class="d-felx row">
    <div class="card bg-light-primary">
        <div class="card-body">
            <p>You can re-enter our promotional offer up to three times after completing each activation. Once your first promo is activated and completed, you can enjoy the opportunity to re-enter up to three times.</p>
        </div>
    </div>
    <?php
    if (is_array($promo)) {
        $promom = $d->getall("promo", "ID = ?", [$promo['promoID']]);
    ?>
        <div class="card ">
            <div class="card-header bg-success">
                <h5 class='text-light'>You have an active promo.</h5>
            </div>
            <div class="card-body">
                <p>Rate: <?= $promom['rate'] . "X your profit"; ?></p>
                <p><b>Start Date:</b> <?= $d->date_format(date("Y-m-d H:i:s", $promo['start_date'])) ?></p>
                <p><b>End Date:</b> <?= $d->date_format(date("Y-m-d H:i:s", $promo['end_date'])) ?></p>
            </div>
        </div>
    <?php } else {
    ?>
        <div class="col-md-8 col-12">
            <div class="card p-4">
                <h6>Activate Promo</h6>
                <form action="" id="foo">
                    <?php echo $c->create_form($assign_promo); ?>
                    <input type="hidden" name="new_promo">
                    <input type="hidden" name="page" value="promo">
                    <div id="custommessage"></div>
                    <input type="submit" class="btn btn-primary" value="Activate Promo">
                </form>

            </div>
        </div>
        <div class="col-md-4">
            <?php echo $u->show_balance($user_data); ?>
        </div>
    <?php } ?>
</div>

<?php
require_once "pages/investment/trade_table.php";
?>