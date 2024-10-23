<div class="col-12 card">
    <div class="card-body p-0 p-2">
        <div class="mb-2">
            <div class="d-flex justify-content-between align-items-center m-0">
                <div style="margin-left: -20px; position: relative; scale: 0.5; height: 27px; width: 200px;  position: relative"
                    class="d-flex align-items-center">

                    <?= $u->displayProfile($userID, $user_data['stage']['position'] ?? 0) ?>

                    <h2 class="ms-2 fw-semibold"><?= $user_data['stage']['name'] ?></h2>
                </div>

                <?php if (!$user_data['isLastStage']) { ?>
                    <div style="position: relative; scale: 0.5; height: 27px; width: 300px; margin-right: -9%;"
                        class="d-flex align-items-center justify-content-end">
                        <h3 class="m-0fs-4 fw-semibold"><?= $user_data['percentage'] ?>% to Level <?= $user_data['stage']['position']?> </h3>
                        <div class='ms-2'><?= $u->stagesBadge(stage: ($user_data['stage']['position'] ?? 0) + 1) ?></div>
                    </div>
                    <!-- <span class="badge bg-light-primary text-primary fw-semibold fs-1">55% to Stage 2</span> -->
                </div>
            <?php } ?>
        </div>
        <div class="progress bg-light-primary" style="height: 4px;">
            <div class="progress-bar w-<?= $user_data['percentage'] ?? 0 ?>" role="progressbar" aria-valuenow="90"
                aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>


    <?php if ($page != "profile")
        echo "<center  style='width: 100%'><a href='index?p=profile&action=new' target='_BLANK' class='btn btn-sm btn-dark'>Know more about discount</a></center>" ?>
    <?php if ($page == "profile") { ?>
        <div class="p-2 bg-light-success">
            <ul>
                <li><b>Your Total Deposit:</b> <?= $d->money_format($user['total_credit']) ?></li>
                <li><b>Current Stage:</b> <?= $user_data['stage']['name'] ?></li>
                <li><b>Amount Remaining To next stage:</b> <?= $d->money_format($user_data['amountRemaining']) ?> </li>
            </ul>
        </div>
    <?php } ?>

</div>