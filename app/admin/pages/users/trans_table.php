<?php
$script[] = "table";
?>

<div class="table-responsive">
    <table id="zero_config" class="table border table-striped table-bordered text-nowrap">
        <thead>
            <!-- start row -->
            <tr>
                <th>ID</th>
                <th>user</th>
                <th>Reason</th>
                <th>Action</th>
                <th>Account Type</th>
                <th>Amount</th>
                <th>Current Balance</th>
                <th>Date</th>
            </tr>
            <!-- end row -->
        </thead>
        <tbody>
            <?php foreach ($transactions as $row) {
                $user = $d->getall("users", "ID = ?", [$row['userID']]);
                $full_name = $user['first_name'] . ' ' . $user['last_name'];
            ?>
                <!-- start row -->
                <tr>
                    <td><?= $row['ID'] ?></td>
                    <td class="d-flex gap-2"><?= $u->profile_picture_default($row['userID']) . ucfirst($full_name); ?></td>
                   
                    <td><?= $d->short_text($row['trans_for']) ?></td>
                    <td class='text-<?= $row['action_type'] == 'debit' ? 'danger' : 'success'; ?>'><?= str_replace("_", " ", $row['action_type']) ?></td>
                    <td><?= str_replace("_", " ", $row['acct_type']) ?></td>
                    <td class='text-<?= $row['action_type'] == 'debit' ? 'danger' : 'success'; ?>'><?= $d->money_format($row['amount'], currency) ?></td>
                    <td><?= $d->money_format($row['current_balance'], currency) ?></td>
                    <td><?= $d->date_format($row['date']) ?></td>
                </tr>
                <!-- end row -->
            <?php } ?>
            </tfoot>
    </table>
</div>