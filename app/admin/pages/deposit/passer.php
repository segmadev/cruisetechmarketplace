<?php 
    if(isset($_GET['get_payments'])) {
        $start = htmlspecialchars($_POST['start'] ?? 0);
        $payment = $d->getall("payment", "userID != ? order by date DESC LIMIT $start, 10", [""], fetch: "moredetails");
        if($payment->rowCount() < 0) {
            $return = ["status"=>"null", "data"=>""];
            echo json_encode($return);
        } else {
            $contentHtml = "";
            foreach ($payment as $pay) {
                require "../pages/deposit/pay_table.php";
            }
            return ;
        }
    }
?>