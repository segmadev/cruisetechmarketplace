<?php
$script[] = "fetcher";
$script[] = "modal";
?>
<div class="table-responsive w-100">
    <form action="index" method="GET" class="d-flex gap-2">
        <input type="hidden" name="p" value="orders">
        <?php if (isset($_GET['userID'])): ?>
        <input type="hidden" name="userID" value="<?= htmlspecialchars($_GET['userID'], ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
        <input type="search" name="orID" class="form-control" placeholder="Search my ID">
        <input type="submit" value="Search" class="btn btn-sm btn-primary">
    </form>

    <hr>
    <table class="table align-middle text-nowrap mb-0 bg-transparent">
        <thead>
            <tr class="text-muted fw-semibold" style="background-color: green" ;>
                <th scope="col">Action</th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Amount</th>
                <th scope="col" class="ps-0">No</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody class="border-top" data-limit="100" data-start="0"
            data-path="passer?get=orders<?= isset($_GET['userID']) ? '&userID=' . urlencode($_GET['userID']) : ''; ?><?= isset($_GET['orID']) ? '&orID=' . urlencode($_GET['orID']) : ''; ?>"
            data-load="orders" data-displayId="loadorders" id="loadorders"></tbody>
    </table>
</div>