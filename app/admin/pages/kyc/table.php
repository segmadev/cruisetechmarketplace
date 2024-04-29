<table class="table border">
    <thead>
        <tr>
            <th scope="col" class="">User</th>
            <th scope="col" class="">Uploaded ID</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody class="border-top">
        <?php
        $script[] = "modal";
        $script[] = "sweetalert";
        foreach ($kyc as $row) {
        ?>
            <tr>
                <td>
                    <p class="mb-0 fs-3 text-dark"><a href="index?p=users&action=view&id=<?= $row['ID'] ?>"><?= $u->short_user_table($row['ID']); ?></a></p>
                </td>
                <td>
                    <p class="mb-0 fs-3 text-dark"><?= $c->display_image("../assets/images/kyc/" . $row['valid_ID'], $row['ID']) ?></p>
                </td>
                <td>
                    <?= $c->badge($row['kyc_status']) ?>
                </td>
              
                <td class="d-flex">
                    <div class="dropdown dropstart">
                        <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical fs-6"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                            <li>
                                <form action="" id="foo">
                                    <input type="hidden" name="ID" value="<?= $row['ID'] ?>">
                                    <input type="hidden" name="kyc_status" value="approved">
                                    <input type="hidden" name="update_kyc" value="approved">
                                    <input type="hidden" name="page" value='users'>
                                    <input type="hidden" name="confirm" value="You are about to approve <?= $u->get_name($row['ID']) ?> KYC. Are you sure about this?">
                                    <div id="custommessage"></div>
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-success" href="#"><i class="fs-4 ti ti-check"></i>Approve</button>
                                </form>
                            </li>
                            <hr>
                            <li>

                                <form action="" id="foo">
                                    <input type="hidden" name="ID" value="<?= $row['ID'] ?>">
                                    <input type="hidden" name="kyc_status" value="Rejected">
                                    <input type="hidden" name="update_kyc" value="Rejected">
                                    <input type="hidden" name="page" value='users'>
                                    <input type="hidden" name="confirm" value="You are about to  reject <?= $u->get_name($row['ID']) ?> KYC. Are you sure about this?">
                                    <div id="custommessage"></div>
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-3 text-danger" href="#"><i class="fs-4 ti ti-check"></i>Reject</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php  } ?>
    </tbody>
</table>