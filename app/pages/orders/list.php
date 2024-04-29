<div class="card p-3">
    <h1 class="h4">Your Orders</h1>
    <hr>
    <div class='w-100 row row-cols-1 row-cols-lg-3 row-cols-md-2 g-1 g-lg-3 m-0 p-0'>
        <?php
        if ($orders == "" || $orders->rowCount() == 0) {
            echo $c->empty_page("No order yet. You havn't make any orders yet", h1: "NO order");
        } else {
            $script[] = "modal";
            foreach ($orders as $account) {
                echo $a->display_account($account, $userID);
            }
        }
        ?>
    </div>
</div>