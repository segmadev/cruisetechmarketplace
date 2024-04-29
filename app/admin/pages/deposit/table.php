<table class="table border">
    <thead>
        <tr>
            <th scope="col" class="ps-0">Wallet</th>
            <th scope="col" class="">User</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            <th scope="col">Date</th>
        </tr>
    </thead>
    <tbody class="border-top">
        <?php
        $script[] = "modal";
        $script[] = "sweetalert";
        foreach ($deposit as $row) {
            $wallet = $d->getall("wallets", "ID = ?", [$row['wallet']], "coin_name, wallet_address");
            $coin = $de->get_coin_details($wallet['coin_name']);
        ?>
            <tr>
                <td class="ps-0">
                    <div class="d-flex align-items-center">
                        <div class="me-2 pe-1">
                            <img src="<?= $coin['image'] ?>" class="rounded-2" width="48" height="48" alt="" />
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1"><?= $coin['name']; ?></h6>
                            <p class="fs-2 mb-0 text-muted"><?= $de->short_wallet_address($wallet['wallet_address']); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="mb-0 fs-3 text-dark"><a href="index?p=users&action=view&id=<?= $row['userID'] ?>"><?= $u->short_user_table($row['userID']); ?></a></p>
                </td>
                <td>
                    <p class="mb-0 fs-3 text-dark"><?= $d->money_format($row['amount'], currency) ?></p>
                </td>
                <td>
                    <?= $c->badge($row['status']) ?>
                </td>
                <td>
                    <p class="fs-3 text-dark mb-0"><?= $d->date_format($row['date']) ?></p>
                </td>
                <td class="d-flex">
                    <a href="#" class="text-muted btn btn-outline-dark" 
                    data-bs-toggle="modal" 
                    data-bs-target="#bs-example-modal-md" 
                    id="modal-<?= $row['ID'] ?>" 
                    data-url="modal?p=deposit&action=prove&id=<?= $row['ID'] ?>" 
                    data-title="Prove Deposit of <?= $d->money_format($row['amount'], currency) ?>" 
                    onclick="modalcontent(this.id)"
                    ><i class="ti ti-eye"></i></a>
                    <div class="dropdown dropstart">
                        <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical fs-6"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <li>
                                <form action="" id="foo">
                                    <input type="hidden" name="ID" value="<?= $row['ID'] ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <input type="hidden" name="amount" value="<?= $row['amount'] ?>">
                                    <input type="hidden" name="reason" value="">
                                    <input type="hidden" name="updatedeposit" value="approved">
                                    <input type="hidden" name="page" value='users'>
                                    <input type="hidden" name="confirm" value="You are about to approve a plan. Are you sure about this?">
                                    <div id="custommessage"></div>
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-success" href="#"><i class="fs-4 ti ti-check"></i>Approve</button>
                                </form>
                            </li>
                            <hr>
                            <li>
                                <button class="dropdown-item d-flex align-items-center gap-3 text-danger" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" id="modal-reject-<?= $row['ID'] ?>" data-url="modal?p=deposit&action=rejected&id=<?= $row['ID'] ?>" data-title="Reject Deposit of <?= $d->money_format($row['amount'], currency) ?>" onclick="modalcontent(this.id)"><i class="fs-4 ti ti-close"></i>Reject</button>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php  } ?>
    </tbody>
</table>