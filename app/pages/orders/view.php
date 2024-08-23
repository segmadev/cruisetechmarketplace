<?php
if (!$order || $order == "") {
    echo $c->empty_page("Order not found or not sold to you.", h1: "Not Found");
} else {
    $script[] = "divtopdf";
    ?>
    <div class="card p-3 bg-white" id="logindetailsdiv">
        <div>
            <button class="mr-auto print-ignore btn btn-sm mb-2 btn-outline-primary" onclick="printDiv('logindetailsdiv', 'accountlogin' )" type="button"><i class="ti ti-download"></i> Download as PDF</button>
        </div>
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
                <div class=""><b>Login <?php echo $i;
                                                $i++ ?>: </b> <?= $login['login_details'] ?>ddjsbdmnsbdnsbdsnmdvsbdvsbdvsdsdvbsvdbsdvsbdnsvdbnasvdabndvasdbnvsdbasvdasbndvasbndvasdbasndvsbn</div>
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