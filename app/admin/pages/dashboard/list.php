<?php
    require_once "pages/dashboard/scroll.php";

    ?>
<div class="row">
    <div class="col-12">
        <div class="border-top">
            <div class="row gx-0">
                <div class="col-md-4 border-end">
                    <div class="p-4 py-3 py-md-4">
                        <p class="fs-4 text-danger mb-0">
                            <span class="text-danger">
                                <span class="round-8 bg-danger rounded-circle d-inline-block me-1"></span>
                            </span>Total Deposit
                        </p>
                        <h3 class=" mt-2 mb-0"><?=$d->money_format($users_data['total_success_payment'], currency)?></h3>
                    </div>
                </div>
                <div class="col-md-4 border-end">
                    <div class="p-4 py-3 py-md-4">
                        <p class="fs-4 text-success mb-0">
                            <span class="text-success">
                                <span class="round-8 bg-success rounded-circle d-inline-block me-1"></span>
                            </span>Total Amount of Account Sold
                        </p>
                        <h3 class=" mt-2 mb-0"><?=$d->money_format($users_data['total_amount_sold'], currency)?></h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 py-3 py-md-4">
                        <p class="fs-4 text-info mb-0">
                            <span class="text-info">
                                <span class="round-8 bg-info rounded-circle d-inline-block me-1"></span>
                            </span>All users balance
                        </p>
                        <h3 class=" mt-2 mb-0"><?=$d->money_format($users_data['balance'], currency)?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <!-- deposit -->
            <div class="card">
                <div class="card-header">
                    <h5>Recent Registered Users | <a href="index?p=users">See all users</a> </h5>
                </div>
                <div class="card-body table-responsive">
                    <?php require_once "pages/users/table_body.php"?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!-- withdraw -->
            <div class="card">
                <div class="card-header">
                    <h5>Recent Payments <a href="index?p=deposit">View All</a> </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive ">
                       <?php require_once "pages/payment/table_body.php";?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>