<?php
$script[] = "table";
?>
<div class="row">
    <div class="col-12">
        <!-- start Zero Configuration -->
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <h5 class="mb-0">Users</h5>
                </div>
                <p class="card-subtitle mb-3">
                    List of all registered users.
                </p>
                <?php require_once "pages/users/table_body.php"; ?>
            </div>
        </div>
        <!-- end Zero Configuration -->
    </div>
</div>