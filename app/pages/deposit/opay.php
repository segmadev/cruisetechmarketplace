<?php
$script[] ="sweetalert";
$awaitingPayment = $d->getall("awatingpayment", "userID = ?",[$userID], fetch:"all");
?>
<div class="row">
    <div class="col-sm-6 col-lg-4 col-12">
        <div class="card card-body bg-light-success">
        
            <h5 class="text-danger">⚠️ Important Notice:</h5>
                After successfully making the payment using the account details provided below, please copy the Session ID or Transaction ID from your receipt and submit it below for payment verification.
            <hr>
            <form id="foo" class="p-3 card card-body bg-white">
                <input type="text" placeholder="sessionID or TransactionID" name="session_ID" class="form-control">
                <input type="hidden" name="page" value="deposit">
                <input type="hidden" name="manual_payment" value="deposit">
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
            <hr>
             <h1><?= $d->get_settings(("bank_account")) ?></h1>
            <h5>Bank: <?= $d->get_settings(value: ("bank_name")) ?></h5>
            <p>Account Name: <?= $d->get_settings(("account_name")) ?></p>
            <?php echo $u->show_balance($userID, showBtn: true); ?>          
        </div>

    </div>
    <div class="col-sm-6 col-lg-8 col-12">
        <div class="card bg-white">
            <div class="card-header">
                <h3>Pending Request.</h3>
            </div>
                <div class="card-body">
                <?php
                    if($awaitingPayment->rowCount() == 0) {
                        echo $c->empty_page("If your payment is delayed due to bank network issues, it will appear here as a pending payment request and will be automatically processed once the payment is received.", h1: "Pending Payment Request");
                    }else{
                ?>
<table class="table table-bordered table-striped">
        <thead class="table-dark">
       
        </thead>
        <tbody id="sessionTable">
            <?php foreach($awaitingPayment as $pay) {
                echo "<tr class='".$pay['ID']."'>";
                echo "<td>". $pay['ID']."</td>";
                echo "<td>". $pay['sessionID']."</td>";
                echo "<td>". date("F j, Y", strtotime($pay['date']))."</td>";
                echo "<td>
                <form action='' id='foo'>
                    <input type='hidden' name='page' value='deposit'>
                    <input type='hidden' name='confirm' value='You are about to remove a payment request. <br> Session ID: ".$pay['sessionID']."'>
                    <input type='hidden' name='awaitID' value='".$pay['ID']."'>
                    <button type='submit' class='btn btn-danger btn-sm remove-btn'>Remove</button></td>
                </form>
                ";
                echo "</tr>";
                }?>
            
        </tbody>
    </table>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

