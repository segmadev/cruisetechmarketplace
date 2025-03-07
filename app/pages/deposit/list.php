<?php
$script[] = "chart";
$script[] = "modal";
$script[] = "fetcher";
$d->delete("payment", 'userID = ? and status = ? and date < NOW() - INTERVAL 2 DAY', [$userID, 'pending']);
if (!isset($_GET['new_account']) && $d->getall("user_accounts", "userID = ?", [$userID], fetch: "") == 0) {
    echo "Creating account for you. This can take a moment...";
    echo "If taking too long <a href='index?p=deposit&new_account=true'>click here</a>";
    $d->loadpage("index?p=deposit&new_account=true");
} else {
    $account_details = $de->get_account_details($userID);
?>
    <div class="row col-12">

        <div class="col col-12 col-lg-4">
            <div class="card w-100">
                <div class="card-body bg-light p-3">
                    <?php echo $u->show_balance($userID, showBtn: false); ?>
                    <div class="card card-body bg-light p-3">
                        <h5 class="m-0">Bank Details</h5>
                        <small>Make payment here to automatically fund your account.</small>
                        <hr class="mt-2 mb-2">
                        <?php
                            if (!is_array($account_details)) {
                                echo $c->empty_page("No bank details found. Please add funds into your account manually.", h1: "No bank details found");
                            } else {
                                echo '<p>Bank Name: ' . $account_details['bank_name'] . '</p>';
                                echo '<p>Account No: <b>' . $account_details['account_number'] . $c->copy_text($account_details['account_number']) . '</b></p>';
                                echo '<p>' . $account_details['note'] . '</p>';
                            }
                        ?>
                    </div>
                    <?php if(isset($_GET['debug']) && $_GET['debug'] == "opay") {?>
                    <div id="walletDetails"  class="border-top" data-interval="43000000" data-isreplace="true" data-load="deposit" data-displayId="walletDetails" data-path="passer?p=account&get_wallet=yes"></div>
                    <div class="card card-body bg-light-success p-3">
                        <h5>Fund with Opay.</h5>
                        <small>Click Fund with opay button to see how you can fund using opay account.</small>
                        <a href='?p=deposit&action=opay&debug=opay' class='btn btn-sm bg-success text-light mt-2'>+ Fund with Opay</a>
                    </div>

                    <?php } ?>

                    <div class="card card-body bg-light-danger p-3">
                        <h5>Other Payment Option</h5>
                        <small>Click the fund account button below to try other method of funding your account.</small>
                        <a href='#' data-bs-toggle='modal' data-bs-target='#bs-example-modal-md'
                            id='modal-viewer-funaccount' data-url='modal?p=deposit&action=new' data-title='Fund Account'
                            onclick='modalcontent(this)' class='btn btn-sm bg-primary text-light mt-2'>+ Fund Account</a>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!isset($deposit) || $deposit->rowCount() <= 0) {
            //echo $c->empty_page("You haven't made any deposit yet.", "<a href='?p=deposit&action=new' class='btn btn-primary'> <b>Make A Deposit Now</b></a>");
        } else { ?>
            <div class="col col-12 col-lg-8 p-3">
                <a href="index?p=deposit&action=transactions" class="btn btn-sm btn-primary m-0">View Transactions</a>
                <?php require_once "pages/deposit/table.php"; ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>