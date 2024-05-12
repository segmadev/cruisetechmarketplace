<style>
    li {
        list-style: none;
    }
</style>
<?php 
    echo $u->short_user_table($order['userID']);
    require "../pages/orders/view.php"; 
?>