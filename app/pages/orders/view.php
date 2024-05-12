<?php
if (!$order || $order == "") {
    echo $c->empty_page("Order not found or not sold to you.", h1: "Not Found");
} else { ?>
    <div class="card p-3">
        <div class="flex d-flex">
            <div>
                <img class="img-fluid rounded-circle" width="40" src="<?= $a->get_platfrom_img_url($account['platformID'])  ?>" alt="">
            </div>
            <div class="ms-3">
                <h2><?= $account['title'] ?></h2>
                <p class="h5">Amount: <?= $d->money_format($order['amount']) ?></p>
            </div>
        </div>
        <div class="card bg-light-success p-3">
            <h6><b>Account Access Details</b></h6>
            <?php $i = 1;
            foreach ($logins as $login) {
                $login = $d->getall("logininfo", "ID = ?", [$login]);
            ?>
                <hr>
                <div class="d-flex"><b>Login <?php echo $i;
                                                $i++ ?>: </b> <?= $login['login_details'] ?></div>
            <?php } ?>
            <hr>
            <ul>
                <li>Aditional Details: <?= $account['Aditional_auth_info'] ?></li>
            </ul>
        </div>
        <hr>
        <p class='text-mute'><?= $account['description'] ?></p>



    </div>
<?php   } ?>