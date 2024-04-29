<?php 
    if($action == "list") {
        $deposit = $d->getall("deposit", "userID = ? order by date DESC", [$userID], fetch: "moredetails");
        $rejected = $de->get_total_pending($userID, "rejected");
        $Approved = $de->get_total_pending($userID, "Approved");
        $pending = $de->get_total_pending($userID, "pending");
    }

    if(isset($_GET['tx_ref']) && isset($_GET['transaction_id']) && !isset($_POST['start'])) {
            $de->validate_payment(htmlspecialchars($_GET['tx_ref']), htmlspecialchars($_GET['transaction_id']), $userID);
    }
?>