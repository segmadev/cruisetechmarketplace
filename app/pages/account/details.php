<?php
if (!$account || $account == "") {
    echo $c->empty_page("Account not found or sold.", h1: "Not Found");
} else { 
    $script[] = "sweetalert";
    ?>
    <div class="card p-3">
        <div class="flex d-flex">
            <div>
                <img class="img-fluid rounded-circle" width="60" src="<?= $a->get_platfrom_img_url($account['platformID'])  ?>" alt="">
            </div>
            <div class="ms-3">
                <h2><?= $account['title'] ?></h2>
                <input type="hidden" id="amountvalue" value="<?= $account['amount'] ?>">
                <div class="d-flex h5"><p>Amount: </p> <p id="DisplayAmount"><?= $d->money_format($account['amount']) ?></p></div>
            </div>
        </div>
        <hr>
        <form action="" id="foo">
        <div class="input-group input-group-sm rounded">
            <button class="btn min-width-40 py-0 border-end border-secondary fs-5 border-end-0 text-secondary" type="button" id="minusAmount"><i class="ti ti-minus"></i></button>
            <input type="number" name="qty" id="qtynumber" class="min-width-40 flex-grow-0 border border-secondary text-secondary fs-4 fw-semibold form-control text-center qty" placeholder="" value="1">
            <button class="btn min-width-40 py-0 border border-secondary fs-5 border-start-0 text-secondary" type="button" id="addAmount"><i class="ti ti-plus"></i></button>
            <!-- <div class="ms-2">200000</div> -->
        </div>
        <div class="mt-3"><span class="badge text-bg-success fs-2 fw-semibold rounded-3"><?= $a->get_num_of_login($account['ID']) ?> pcs available</span></div>
        <hr>
        
            <input type="hidden" name="ID" value="<?= $account['ID'] ?>">
            <input type="hidden" name="buy" value="yes">
            <input type="hidden" name="page" value='account'>
            <input type="hidden" name="confirm" value="Your account will be charged for <?= $account['title'] ?>">
            <div id="custommessage"></div>
            <button type="submit" class=" d-flex align-items-center gap-3 btn btn-success" href="#"><i class="fs-4 ti ti-check"></i>Buy Account</button>
        </form>
        <hr>
        <p class='text-mute'><?= $account['description'] ?></p>
    </div>
<?php   } ?>

<script>
    add = document.getElementById("addAmount");
    minus = document.getElementById("minusAmount");
    var qtynumber =document.getElementById("qtynumber")
    amount = document.getElementById('amountvalue').value;

    add.addEventListener("click", function() {
        currentValue = parseInt(qtynumber.value) + 1;
        if(currentValue > parseInt(<?= $a->get_num_of_login($account['ID']) ?>))return ;
        qtynumber.value = currentValue;
        document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + parseInt(amount) * currentValue;
    });
    minus.addEventListener("click", function() {
        currentValue = parseInt(qtynumber.value) - 1;
        if(currentValue <= 0)return ;
        qtynumber.value = currentValue;
        document.getElementById("DisplayAmount").innerHTML = "<?= htmlspecialchars(currency ?? "N") ?>" + parseInt(amount) * currentValue;
    });
</script>

</script>