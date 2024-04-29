<div class="row">
    <?php if (isset($refs) && $refs->rowCount() > 0) { ?>
        <div class="col-md-4 col-12">
            <div class="card bg-primary border-0 w-100">
                <div class="card-body pb-0">
                    <h5 class="fw-semibold mb-1 text-white card-title"> Your Active referral Programs </h5>
                    <p class="fs-3 mb-3 text-white">only referral who made first deposit will refelct in your progess report. </p>
                    <div class="text-center mt-3">
                        <img src="../dist/images/backgrounds/piggy.png" class="img-fluid" alt="">
                    </div>
                </div>
                <div class="card mx-2 mb-2 mt-n2 bg-transparent">
                    <div class="card-body p-2">
                        <?php
                        foreach ($refs as $row) {
                            $code = $row['referral_code'];
                            $info = $u->get_ref_info($code);
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
                                    <a href="index?p=referral&action=view&id=<?= $row['ID'] ?>" class="bg-light rounded py-1 px-8 d-flex align-items-center text-decoration-none">
                                        view
                                    </a>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                    <?php if (isset($pending)) { ?>
                        <div class="card-footer bg-light-warning">
                            <b class="fs-2 text-warning">Pending are those individuals who have registered using your referral code but have not yet made a deposit.</b>
                        </div>
                    <?php } ?>


                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-8 col-12">
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3 m-0">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">Referral</h4>
                        <p>Join any of our referral programs avilable below and start earning rewards.</p>
                        <!-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-muted " href="index-2.html">Dashboard</a></li>
                        <li class="breadcrumb-item" aria-current="page">Grid</li>
                    </ol>
                </nav> -->
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once "pages/referral/r-lists.php";
        ?>
    </div>
</div>