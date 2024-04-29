<tr>
    <td>
        <p class="mb-0 fs-3"><?= $d->money_format($pay['amount'], currency) ?></p>
    </td>
    <td>
        <?= $c->badge($pay['status']) ?> <br>
        <?php 
            if(!$d->validate_admin() && ($pay['status'] == "pending" || $pay['status'] == "initiate") && $pay['pay_url'] != "") {
                echo "<a href='".$pay['pay_url']."' target='_blank' class='btn btn-sm btn-outline-primary'>Complete Payment</a>";
            }
        ?>
        
    </td>
    <td>
        <p class="mb-0 fs-3"><?= $pay['title'] ?></p>
    </td>

    <td class="ps-0">
        <div class="d-flex align-items-center">
            <div>
                <h6 class="fw-semibold mb-1"><?= $pay['tx_ref'] ?></h6>
        </div>
        </div>
    </td>

    <td>
        <p class="fs-3 text-dark mb-0"><?= $d->date_format($pay['date']) ?></p>
    </td>
</tr>