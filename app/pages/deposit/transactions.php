<?php 
    $transactions = $d->getall("transactions", "userID = ? order by date DESC LIMIT 50", [$userID], fetch: "moredetails");
    if($transactions->rowCount() < 1) {
        echo $c->empty_page("No Transaction made on account.");
    }else{ ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Transaction.</h5>
                <p>Recent Transactions Taken on this Account.</p>
            </div>
            <div class="card-body">
                <?php require_once "admin/pages/users/trans_table.php"; ?>
            </div>
        </div>
    </div>
   <?php   }
?>