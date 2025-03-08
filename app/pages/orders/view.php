<style>
.addition p {
    margin: 0;
    padding: 0;
}
</style>
<?php
if (!$order || $order == "") {
    echo $c->empty_page("Order not found or not sold to you.", h1: "Not Found");
} else {
    $script[] = "divtopdf";
?>
<div class="card p-3 bg-white" id="logindetailsdiv">
    <div>
        <div class="btn-group mb-2 print-ignore">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-download"></i> Download Login
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                <li><a class="dropdown-item" href="#"
                        onclick="printDiv('logindetailsdiv', '<?= preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $account['title']) ?>')">Download
                        as PDF</a></li>
                <li><a class="dropdown-item" href="#"
                        onclick="downloadText('logindetailsdiv', '<?= preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $account['title']) ?>')">Download
                        as txt</a></li>
            </ul>
        </div>
    </div>
    <hr class="print-ignore">
    <div class="flex d-flex">
        <div>
            <img class="img-fluid rounded-circle" width="40"
                src="<?= $a->get_platfrom_img_url($account['platformID']) ?>" alt="">
        </div>
        <div class="ms-3">
            <small>Order ID: <?= $order['ID'] ?></small>
            <small>Account ID: <?= $account['ID'] ?></small>
            <h2 class="m-0"><?= $account['title'] ?></h2>

            <p class="h5 print-ignore">Amount: <?= $d->money_format($order['amount']) ?></p>
        </div>
    </div>
    <div class="card bg-light-success p-3">
        <h6 class="print-ignore"><b>Account Access Details</b></h6>
        <?php $i = 1;
            foreach ($logins as $login) {
                $login = $d->getall("logininfo", "ID = ?", [$login]);
            ?>
        <hr>
        <div class="fs-2">ID: <?= $login['ID'] ?></div>

        <div class=""><b>Login <?php echo $i;
                                        $i++ ?>: </b> <?= $login['login_details'] ?></div>
        <?php
            } ?>
        <?php if ($account['Aditional_auth_info'] != "") { ?>
        <hr>
        <ul>
            <li class="print-ignore addition"><b>Aditional Details:</b>
                <?= $d->handleLinkInText(htmlspecialchars_decode($account['Aditional_auth_info'])) ?> </li>
        </ul>
        <?php } ?>
    </div>
    <?php if ($account['description'] != "") { ?>
    <hr>
    <p class='text-mute print-ignore'><?= $d->handleLinkInText(htmlspecialchars_decode($account['description'])); ?>
    </p>
    <?php } ?>


</div>
<?php
} ?>