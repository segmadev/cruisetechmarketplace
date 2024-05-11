<div class="card p-3">
    <h1 class="h4">Your Orders</h1>
    <hr>
    <div class='w-100'>
        <?php
        if ($orders == "" || $orders->rowCount() == 0) {
            echo $c->empty_page("No order yet. You havn't make any orders yet", h1: "NO order");
        } else {
           
           require_once "pages/orders/table.php"; 
        }
        ?>
    </div>
</div>