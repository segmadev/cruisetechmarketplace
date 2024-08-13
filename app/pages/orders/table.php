<div class="table-responsive w-100">
    <div class="d-flex gap-2">
        <a href="index?p=rentals" class="btn btn-sm btn-primary">Rentals</a>
        <a href="index?p=orders&type=account" class="btn btn-outline-primary">Accounts</a>
    </div>
    <hr>
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
            foreach ($orders as $order) { 
                if($order['order_type'] == "rentals" && $order['loginIDs'] == "") continue;
                $page = "orders";
                $idInfo = "id";
                $id = $order['ID'];
                if($order['order_type'] == "rentals") {
                    $page = "rentals";
                    $idInfo = "accountID";
                    $id = $order['accountID'];
                }
                ?>
                <tr>
                    <td><a <?= $c->modal_attributes("modal?p=$page&action=view&$idInfo=".$id, "Order Details") ?> class="btn btn-sm btn-primary">View</a></td>
                    <td><?php if($order['order_type'] == "account") { 
                        echo $a->display_account_name($order['accountID']);
                        }else{
                            echo "<h5>".$order['loginIDs'] ?? "unbooked"."</h5>";
                        }  ?></td>
                    <td><?= $d->money_format($order['amount'], currency) ?></td>
                    <td><?= number_format($order['no_of_orders']) ?></td>
                    <td><?= $d->date_format($order['date']) ?></td>
                </tr>
            <?php  } ?>
        </tbody>
    </table>
</div>