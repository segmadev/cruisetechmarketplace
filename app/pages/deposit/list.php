<?php 
$script[] = "chart";
$script[] = "modal";
$script[] = "fetcher";

?>
<div class="row col-12">
    <?php if(!isset($deposit) || $deposit->rowCount() <= 0) {
        echo $c->empty_page("You haven't made any deposit yet.", "<a href='?p=deposit&action=new' class='btn btn-primary'> <b>Make A Deposit Now</b></a>");
     } else { ?>
    <div class="col col-12 col-md-4">
        <div class="card w-100">
            <div class="card-body ">
                <?php  echo $u->show_balance($userID); ?>
                <a href="index?p=deposit&action=transactions" class="btn btn-sm btn-dark w-100 m-0">View All Transactions</a>
               <hr>
               
                <!-- <h5 class="card-title fw-semibold">Deposit</h5>
                <p class="card-subtitle mb-2">All your Deposit</p>
                <div id="deport-chart" class="mb-4 ms-0 p-0"></div> -->
            </div>
        </div>
    </div>
    <div class="col col-12 col-lg-8 p-3">
        <?php require_once "pages/deposit/table.php"; ?>
    </div>
    <?php } ?>
</div>
