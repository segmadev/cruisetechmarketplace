<div class="table-responsive w-100">
    <table class="table align-middle text-nowrap mb-0 bg-transparent">
        <thead>
            <tr class="text-muted fw-semibold" style="background-color: green" ;>
                <th scope="col">Action</th>
                <th scope="col">Name</th>
                <th scope="col">Amount</th>
                <th scope="col" class="ps-0">No</th>
                <th scope="col">Date</th>
            </tr>
        </thead>
        <tbody class="border-top">
            <?php 
            $script[] = "modal";
            foreach ($orders as $order) { ?>
                <tr>
                    <td><a <?= $c->modal_attributes("modal?p=orders&action=view&id=".$order['ID'], "Account Details") ?> class="btn btn-sm btn-primary">View</a></td>
                    <td><?= $a->display_account_name($order['accountID']) ?></td>
                    <td><?= $d->money_format($order['amount'], currency) ?></td>
                    <td><?= number_format($order['no_of_orders']) ?></td>
                    <td><?= $d->date_format($order['date']) ?></td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>
</div>