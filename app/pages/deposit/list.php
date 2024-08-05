<?php 
$script[] = "chart";
$script[] = "modal";
$script[] = "fetcher";

?>
<div class="row col-12">
    <?php if(!isset($deposit) || $deposit->rowCount() <= 0) {
        echo $c->empty_page("You haven't made any deposit yet.", "<a href='?p=deposit&action=new' class='btn btn-primary'> <b>Make A Deposit Now</b></a>");
     } else { ?>
    <div class="col col-12 col-md-4">
        <div class="card w-100">
            <div class="card-body bg-light p-3">
                <?php  echo $u->show_balance($userID, showBtn: false); ?>
                <div class="card card-body bg-light-success p-3">
                    <h5 class="m-0">Bank Details</h5>
                    <small>Make payment here to automatically fund your account.</small>
                    <hr class="mt-2 mb-2">
                    <?php 
                        if(!is_array($account_details)) {
                            echo $c->empty_page("No bank details found. Please add funds into your account manually.", h1: "No bank details found");
                        }else {
                            echo '<p>Bank Name: '. $account_details['bank_name'] .'</p>';
                            echo '<p>Account No: <b>'. $account_details['account_number'] .$c->copy_text($account_details['account_number']).'</b></p>';
                            echo '<p>'. $account_details['note'] .'</p>';
                        }
                    ?>
                </div>
                <div class="card card-body bg-light-danger p-3">
                    <h5>Other Payment Option</h5>
                    <small>Click the fund account button below to try other method of funding your account.</small>
                    <a href='#' data-bs-toggle='modal' data-bs-target='#bs-example-modal-md' id='modal-viewer-funaccount' data-url='modal?p=deposit&action=new' data-title='Fund Account' onclick='modalcontent(this.id)' class='btn btn-sm bg-primary text-light mt-2'>+ Fund Account</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col col-12 col-lg-8 p-3">
    <a href="index?p=deposit&action=transactions" class="btn btn-sm btn-outline-dark m-0">View All Transactions</a>
        <?php require_once "pages/deposit/table.php"; ?>
    </div>
    <?php } ?>
</div>
