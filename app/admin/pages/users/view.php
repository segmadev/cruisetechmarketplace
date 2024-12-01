<?php 
    $script[] = "modal";
    $no_account_bought = $d->getall("orders", "userID = ?", [$userID], fetch: "");
    $bankAcct = $d->getall("user_accounts", "userID = ?", [$userID]);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body position-relative">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center mb-7">
                                <div class="rounded-circle overflow-hidden me-6">
                                    <img src="<?= $u->get_profile_icon_link($userID) ?>" alt="" width="40" height="40">
                                </div>
                                <h5 class="fw-semibold mb-0 fs-5"><?= $u->get_full_name($user) ?>!</h5>
                            </div>
                            <div class="d-flex">
                                <div><button id="new-transder" data-url="modal?p=users&action=transfer&userID=<?= $userID ?>" data-title="Credit or Debit Account" onclick="modalcontent(this.id)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md"  class="btn btn-primary"><i class='ti ti-arrow'></i>Credit/Debit Account</button></div>
                                    <div>
                                        <button id="new-fund" data-url="modal?p=users&action=block" data-title="Block Account" onclick="modalcontent(this.id)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" class='btn btn-danger ms-1'><i class='ti ti-block'></i> Block Account</button>
                                    </div>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <div class="border-end pe-4 border-muted border-opacity-10">
                                    <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><?= $d->money_format($user_data['balance'], currency) ?></h3>
                                    <p class="mb-0 text-dark">Balance</p>
                                </div>
                                
                            </div>

                        </div>
                        <div class="col-sm-4">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex align-items-strech card bg-light-success">
            <div class="card-body">
                <div class="text-success bg-light-success card p-3 d-flex m-0"><b>Total Credit: <?= $d->money_format($u->total_transaction_type($userID, "credit")['total']) ?> </b></div>
                <!-- <hr> -->
                <div class="text-danger card p-3 d-flex m-0 bg-light-danger"><b>Total Debit: <?= $d->money_format($u->total_transaction_type($userID, "debit")['total']) ?> </b></div>
                <!-- <hr> -->
                <!-- index?p=orders&userID= //$userID;  -->
                <a href="index?p=orders&userID=<?= $userID; ?>" class="text-primary card p-3 d-flex m-0 bg-light-primary"><b>No of orders: <br> <?= number_format($no_account_bought) ?> | View </b></a>
                 <div class="text-light card p-3 d-flex m-0 bg-dark"><?php if(is_array($bankAcct)){ ?>
                    <!-- <h5 class="text-light">Bank Details</h5> -->
                    <p class='m-0 p-0  fs-6'><?= $bankAcct['account_number'] ?></p>
                    <p class='m-0 p-0 '><?= $bankAcct['bank_name'] ?></p>
                    <p class='m-0 p-0 '>Txt Ref: <?= $bankAcct['tx_ref'] ?></p>
                    <?php }else{ echo "No bank Account Created yet."; } ?></div> 
            </div>
        </div>
       
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Transctions.</h5>
                    <p>Recent Transctions taken on this account.
                         <!-- <a href="index?p=users&action=transactions&id=<?= $userID ?>">See All</a> -->
                        </p>
                </div>
                <div class="card-body">
                    <?php require_once "pages/users/trans_table.php"; ?>
                </div>
            </div>
        </div>
      
        <!-- tradeviiew chart -->
    </div>
    <div class="col-12">
        <?php // require_once "pages/deposit/table.php";
        ?>
    </div>
</div>