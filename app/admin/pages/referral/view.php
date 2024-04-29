<?php
$ref_pa = $d->getall("referrals", "referralID = ?", [htmlspecialchars($_GET['id'] ?? "")], fetch: "moredetails");
if ($ref_pa->rowCount() > 0) {
    foreach ($ref_pa as $row) { 
        $info = $u->get_ref_info($row['referral_code']);
        $percentage = $info['percentage'];
        $theme = $c->get_percent_theme($percentage);
        ?>
        <div class="mb-2 pb-1 card p-2">
            <div class="d-flex justify-content-between align-items-center mb-6">
                <div>
                    <h6 class="mb-1 fs-4 fw-semibold"><?= $row['referral_code'] ?></h6>
                    <p class="fs-3 mb-0"><?= $info['no_allocated'] . ' of ' . $info['no_of_users'] ?>
                        <?php if ($info['no_pending'] > 0) {
                            $pending = true;
                        ?>
                            | <b class='text-warning'><?= $info['no_pending'] ?> pending.</b>
                        <?php } ?>
                    </p>
                </div>
                <div>
                    <span class="badge bg-light-<?= $theme ?> text-<?= $theme ?> fw-semibold fs-3"><?= $percentage ?>%</span>
                </div>
            </div>
            <div class="progress bg-light-<?= $theme ?>" style="height: 4px;">
                <div class="progress-bar bg-<?= $theme ?>" style="width: <?= $percentage ?>%;" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <!-- list of users -->
            <div class="d-flex justify-content-between mt-3">
                <ul class="hstack mb-0 ms-2">

                </ul>
                <a href="index?p=referral&action=overview&refid=<?= $row['ID'] ?>" class="bg-light rounded py-1 px-8 d-flex align-items-center text-decoration-none">
                    view
                </a>
            </div>
        </div>
<?php }
}
