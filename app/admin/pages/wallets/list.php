<?php  
    $script[] = "sweetalert";
    $wallets = $d->getall("wallets", "userID = ?", ["admin"], fetch: "moredetails");
?>

<div class="col-12 row">
    <?php
    if($wallets->rowCount() > 0) {
        foreach ($wallets as $row) {
            echo $w->wallet_widget($row);
        }
    }else{
        echo $c->empty_page("You have no wallet added yet. <br> You can add your wallet with the button below.", "<a href='wallets?action=new' class='btn btn-primary'>Create New Wallet</a>");
    }
    ?>
</div>