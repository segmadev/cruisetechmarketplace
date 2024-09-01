<?php
if (!$account || $account == "") {
    echo $c->empty_page("Account not found or sold.", h1: "Not Found");
} else {
    $accountPreview = $d->get_settings("account_preview");
    $script[] = "sweetalert";
    if($accountPreview == 1)  $script[] = "cart";
    ?>
    <div class="card p-3">
        <div class="flex d-flex">
            <div>
                <img  id="platfromImage" class="img-fluid rounded-circle" width="60"
                    src="<?= $a->get_platfrom_img_url($account['platformID']) ?>" alt="">
            </div>
            <div class="ms-3">
                <h2><?= $account['title'] ?></h2>
                <input type="hidden" id="amountvalue" value="<?= $account['amount'] ?>">
                <div class="d-flex h5">
                    <p>Amount: </p>
                    <p><?= $d->money_format($account['amount']) ?></p>
                </div>
            </div>
        </div>
        <p class='text-mute'><?= $d->short_text(htmlspecialchars_decode($account['description']), 60) ?></p>
        <hr>
        <form action="" id="foo">
            <div class="input-group input-group-sm rounded" id="qtynumberDiv">
                <button class="btn min-width-40 py-0 border-end border-secondary fs-5 border-end-0 text-secondary"
                    type="button" id="minusAmount"><i class="ti ti-minus"></i></button>
                <input type="number" name="qty" id="qtynumber"
                    class="flex-grow-0 border border-secondary text-secondary fs-4 fw-semibold form-control text-center qty"
                    style="width:100px"
                    placeholder="" value="1">
                <button class="btn min-width-40 py-0 border border-secondary fs-5 border-start-0 text-secondary"
                    type="button" id="addAmount"><i class="ti ti-plus"></i></button>
            </div>
            <div class="mt-3"><span
                    class="badge text-bg-success fs-2 fw-semibold rounded-3"><?= $a->get_num_of_login($account['ID']) ?> pcs
                    available</span></div>

                    <div class="p-2 mt-2">
                        <div id="cart-container" class="row gap-3" data-account-id="<?= $account['ID'] ?>"></div>
                    </div>
                    
    <hr>
    <textarea name="choices" style="display: none" id="cartDetails">[]</textarea>
    <input type="hidden" name="ID" value="<?= $account['ID'] ?>">
    <input type="hidden" name="buy" value="yes">
    <input type="hidden" name="page" value='account'>
    <input type="hidden" name="confirm" value="Your account will be charged for <b><?= $account['title'] ?></b>">
    <div id="custommessage"></div>
    <button type="submit" class=" d-flex align-items-center gap-3 btn btn-sm btn-success" href="#"><i
            class="fs-4 ti ti-check"></i>Buy Account</button>
            <p><small>Total Amount: <b id="DisplayAmount"><?= $d->money_format($account['amount']) ?></b></small></p>
    </form>
    <?php 
        if($accountPreview == 1) {
            echo '<div id="logins-container" class="row gap-3"></div>';
        }
    ?>

    </div>
<?php } ?>

<script>
    add = document.getElementById("addAmount");
    minus = document.getElementById("minusAmount");
    var qtynumber = document.getElementById("qtynumber")
    amount = document.getElementById('amountvalue').value;


    add.addEventListener("click", function () {
        currentValue = parseInt(qtynumber.value) + 1;
        if (currentValue > parseInt(<?= $a->get_num_of_login($account['ID']) ?>)) return;
        qtynumber.value = currentValue;
        document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + (parseInt(amount) * currentValue).toLocaleString('en-US');
    });
    minus.addEventListener("click", function () {
        currentValue = parseInt(qtynumber.value) - 1;
        if (currentValue <= 0) return;
        qtynumber.value = currentValue;
        document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + (parseInt(amount) * currentValue).toLocaleString('en-US');
    });
</script>

</script>