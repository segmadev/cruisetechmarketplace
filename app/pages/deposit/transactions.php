<?php 
    $transactions = $d->getall("transactions", "userID = ? order by date DESC", [$userID], fetch: "moredetails");
    if($transactions->rowCount() < 1) {
        echo $c->empty_page("No Transcation made on account.");
    }else{ ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Transcation.</h5>
                <p>All Transcations Taken on this Account.</p>
            </div>
            <div class="card-body">
                <?php require_once "admin/pages/users/trans_table.php"; ?>
            </div>
        </div>
    </div>
   <?php   }
?>