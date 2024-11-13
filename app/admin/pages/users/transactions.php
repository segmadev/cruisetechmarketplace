<?php 
    if(!isset($transactions) || $transactions->rowCount() < 1) {
       ?>
         <div class="card">
            <div class="card-header">
                <h3 class="title">Search Trancations</h3>
                <p>Search transactions by SessionID or orderID</p>
            </div>
            <div class="card-body">
            <form action="" id="foo">
            <input type="search" placeholder="Search Trancations" name="s" class="form-control w-100" minlength="4" required>
            <input type="hidden" name="search_transaction" value="">
            <input type="hidden" name="page" value="users">
            <input type="submit" value="Search Trancations" class="btn btn-primary mt-3">
            <hr>
            <div id="custommessage"></div>
        </form>
            </div>
        </div>
    <?php }else{ ?>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Transcation.</h5>
                <p>All Transcations Taken on this Account.</p>
            </div>
            <div class="card-body">
                <?php 
                require_once "pages/users/trans_table.php"; ?>
            </div>
        </div>
    </div>
   <?php   }
?>