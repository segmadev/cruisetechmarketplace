<?php
if (!isset($_GET['accountID']))
    return $d->message("No ID passed", "error");
$acctID = $_GET['accountID'];
$rent = $d->getall("orders", "userID = ? and accountID = ?", [$userID, $acctID]);
if (!is_array($rent)) {
    $d->message("No rental found", "error");
    return false;
}
$codes = $r->getNumberCode($rent['ID']);
$script[] = "countdown";
$countDuration = (int)$d->get_settings("rental_number_expire_time");
?>
<div class="card card-body bg-light">
    <div class='d-flex gap-2'>
        <a href="index?p=rentals&action=new" class="btn btn-sm btn-primary"><i class="ti ti-phone"></i> Get a new number</a>
        <?php if((int)$rent['status'] == 1) { ?>
            <a onclick="return confirmRedirect('Are you sure you want to close this number?')" href="index?p=rentals&action=view&&close=true&orderID=<?= $rent['ID'] ?>&accountID=<?= $rent['accountID'] ?>" class="btn btn-sm btn-dark"><i class="ti ti-danger"></i> Close Number</a>
            <?php } ?>
        </div>
<hr>
    <h6>Number: <b><?= $rent['loginIDs'] ?></b> <?= $c->copy_text($rent['loginIDs']) ?></h6>
<div 
    data-status="<?= (int)$rent['status'] ?>"
    data-countdown-duration="<?= $countDuration ?>"
    data-countdown-insec="<?= $d->datediffe($rent['date'], date('Y-m-d H:i:s'), "s") ?>">
    <?= $c->badge($rent["status"]) ?>
</div>
<?php if((int)$rent['status'] == 1) { ?>
    <b class="fs-2">If code is taking too long you can reload this page.</b>
<?php } ?>
<hr>

<div
    id="rentalDetails" 
    class="border-top" 
    <?php
    if((int)$rent['status'] == 1) { ?>
    data-load="rentals" 
    data-isreplace="true"
    data-displayid="rentalDetails" 
    data-interval="2000"
    data-path="passer?p=rentals&action=new&accountID=<?= $rent['ID'] ?>"
    <?php } ?>

>
<?php
if ($codes != "") {
    $i =0;
    foreach ($codes as $code) {
        $i++
        ?>
        <div class="hstack gap-3 align-items-start mb-7 justify-content-start">
            <div class="text-start">
                <h6 class="fs-2 text-muted"><?= $d->date_format($code['date']) ?></h6>
                <div class="p-2 bg-light-info text-dark rounded-1 d-inline-block fs-3"> <?= $code['NumberCode'] ?> <?= $c->copy_text($code['NumberCode']) ?></div>
            </div>
        </div>
        <?php
    }
    if($i == 0) {
        echo $c->empty_page("No code received yet.");
    }
}else {
    echo $c->empty_page("No code received yet.");
}
?>
</div>
</div>