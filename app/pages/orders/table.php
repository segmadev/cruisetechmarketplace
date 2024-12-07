<?php
$script[] = "fetcher";
$script[] = "modal"; ?>
<div class="table-responsive w-100">
    <div class="d-flex gap-2">
        <!-- <a href="index?p=rentals" class="btn btn-sm btn-primary">Rentals</a> -->
        <a href="index#accountpage" class="btn btn-primary">Buy New Account</a>
    </div>
    <hr>
    <table class="table align-middle text-nowrap mb-0 bg-transparent">
        <form action="index" method="GET" class="d-flex gap-2">
            <input type="hidden" name="p" value="orders">
            <?php if (isset($_GET['type'])): ?>
            <input type="hidden" name="type" value="<?= htmlspecialchars($_GET['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; ?>
            <div class="d-flex gap-2">
                <input type="search" name="orID" class="form-control" style="width: 90%" placeholder="Search my ID">
                <input type="submit" value="Search" class="btn btn-sm btn-primary">
            </div>
        </form>
        <hr>
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
            data-path="passer?get=orders<?= isset($_GET['orID']) ? '&orID=' . urlencode($_GET['orID']) : ''; ?><?= isset($_GET['type']) ? '&type=' . urlencode($_GET['type']) : ''; ?>"
            data-load="orders" data-displayId="loadorders" id="loadorders">
        </tbody>
    </table>
</div>