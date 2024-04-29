<?php
if (!$account || $account == "") {
    echo $c->empty_page("Account not found or sold.", h1: "Not Found");
} else { ?>
    <div class="card p-3">
        <div class="flex d-flex">
            <div>
                <img class="img-fluid rounded-circle" width="60" src="<?= $a->get_platfrom_img_url($account['platformID'])  ?>" alt="">
            </div>
            <div class="ms-3">
                <h2><?= $account['title'] ?></h2>
                <p class="h5">Amount: <?= $d->money_format($account['amount']) ?></p>
            </div>
        </div>
        <form action="" id="foo">
            <input type="hidden" name="ID" value="<?= $account['ID'] ?>">
            <input type="hidden" name="buy" value="yes">
            <input type="hidden" name="page" value='account'>
            <input type="hidden" name="confirm" value="<?= $d->money_format($account['amount']).' will be decducted from your balance for '. $account['title'] ?>">
            <div id="custommessage"></div>
            <button type="submit" class=" d-flex align-items-center gap-3 btn btn-sm btn-success" href="#"><i class="fs-4 ti ti-check"></i>Buy</button>
        </form>
        <hr>
        <p class='text-mute'><?= $account['description'] ?></p>



    </div>
<?php   } ?>