<?php
$script[] = "modal";
$script[] = "sweetalert";
$script[] = "fetcher";
$script[] = "account";
require_once "../content/textarea.php";
?>
<div class="card">
    <div class="card-header">
        <h3>Edit Account</h3>
    </div>
    <div class="card-body">
        <form action="" id="foo">
            <div class="row">
                <?php
                $add = $account_from['Aditional_auth_info'];
                unset($account_from['Aditional_auth_info']);
                $account_from['Aditional_auth_info'] = $add;
                echo $c->create_form($account_from); ?>
            </div>
            <input type="hidden" name="page" value="account">
            <input type="hidden" name="upadate_account" value="account">
            
            <div id="custommessage"></div><br>
            <button type="submit" class="btn btn-primary">
                Update Account
            </button>
        </form>
    </div></div>
        <div class="card card-body mt-4">
            <?php require_once "pages/account/logins.php"; ?>
           <div class="mt-2">
           <input type="button" id="uploadbatch" value="Upload logins in batch" class="btn btn-dark">
           </div>
        </div>
        <hr>
        <h3>Logins Added</h3>
        <hr>
        <div class="container my-3">

            <div class="container my-3">
                <!-- Toggle Button -->
                <button class="btn btn-outline-primary mb-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filterForm" aria-expanded="false" aria-controls="filterForm">
                    Toggle Filter Form
                </button>

                <!-- Collapsible Form -->
                <div class="collapse" id="filterForm">
                    <form id="loadloginForm" action="loadlogin" method="get" class="row g-3">
                        <input type="hidden" name="p" value="account">
                        <!-- Search Input -->
                        <div class="col-12 col-md-5">
                            <input type="search" value="<?php if (isset($_GET['s'])) {
                                                            echo $_GET['s'];
                                                        } ?>" name="s" class="form-control" placeholder="Search Login"
                                data-id="#accountList" id="searchMarket">
                        </div>

                        <!-- Date and Filter Options -->
                        <div class="col-12 col-md-7">
                            <div class="d-flex flex-wrap gap-2 align-items-center">

                                <label for="startDate" class="form-label mb-0 me-2">Start Date</label>
                                <input class="form-control" type="datetime-local" name="startDate" id="startDate">

                                <label for="endDate" class="form-label mb-0 me-2">End Date</label>
                                <input class="form-control" type="datetime-local" name="endDate" id="endDate">

                                <select name="is_sold" class="form-select" style="width: auto;">
                                    <option value="all">All</option>
                                    <option value="no">Not Sold</option>
                                    <option value="yes">Sold</option>
                                    <option value="sold_report">Sold Report</option>
                                </select>
                                <input type="checkbox" name="delete_logins" id="delete_logins" class="danger" /> <label class="text-danger" for="delete_logins">Delete logins</label>
                                <button type="submit" id="fliterButton" class="btn btn-primary btn-sm">Filter</button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="exportLogins()">Export</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

       <div class="card card-body">
       <div class="note-has-grid row" data-limit="100" data-start="0"
            data-path="passer?p=account&id=<?= $id ?>&get=logins&type=active&action=edit" data-load='account'
            data-displayId="loadlogin" id="loadlogin"></div>
       </div>