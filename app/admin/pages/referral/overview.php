<!-- 
    progress bar
    list of users

-->
<div class="card bg-light-success shadow-none  position-relative overflow-hidden">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Referral Code: <b class='text-success'><?= $ref['referral_code'] ?></b></h4>
                
                <?php if($ref['status'] == "completed") {
                    echo $c->badge($ref['status']);
                }else{ ?>
                <div class="input-group flex-nowrap">

                    <input class="form-control" type="text" value='<?php $referral_link = $d->get_settings("website_url") . "/app/register?ref=" . $ref['referral_code'];
                                                                    echo $referral_link; ?>' id="referral_link" disabled>

                    <button onclick="copytext('<?= $referral_link ?>')" data-copy="<?= $referral_link ?>" class="btn btn-light-info text-info font-medium" type="button">
                        <i class="ti ti-copy"></i> Copy
                    </button>
                </div>
                <!-- <a href="javascript:void(0)" ><i class="ti ti-copy"></i></a> -->
                <p>Share this referral code or link with your friends and family to begin earning rewards following their first deposit.</p>
                <?php } ?>
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
    if($ref_a->rowCount() == 0){
       echo $c->empty_page("Your rewards will be displayed here <br> once you make a successful referral.");
    }else{
?>
<div class="row">
    <?php if ($ref['investID'] != "") { ?>
        <div class="col-md-4">
            <div class="card card-header mb-0 bg-light-success">
                <h5 class="title">Investment Reward <i class="ti ti-award "></i>.</h5>
            </div>
        <?php 
            $row = $d->getall("investment", "ID = ?", [$ref['investID']]);
            if(is_array($row)){
                $plan = $d->getall("plans", "ID = ?", [$row['planID']]);
                if(is_array($plan)) {
                    require_once "../functions/investment.php";
                    $i = new investment;
                    require "../pages/investment/single.php"; 
                }
            }
            
        ?>
        </div>
    <?php } ?>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="title">User(s) referral.</h4>
                <p>Your reward on first deposit will automatically be added to your balance.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead>
                            <tr class="text-muted fw-semibold">
                                <th scope="col" class="ps-0">Name</th>
                                <th scope="col">Referral Code</th>
                                <th scope="col">status</th>
                                <th scope="col">Your reward</th>
                            </tr>
                        </thead>
                        <tbody class="border-top">
                            <?php foreach ($ref_a as $row) { ?>
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 pe-1">
                                                <img src="<?= $u->get_profile_icon_link($row['userID']) ?>" class="rounded-circle" width="40" height="40" alt="">
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold mb-1"><?= $d->short_text($u->get_name($row['userID']), "10") ?></h6>

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fs-3"><?= $row['referral_code'] ?></p>
                                    </td>
                                    <td>
                                        <?= $c->badge($row['status']) ?>
                                    </td>
                                    <td>
                                        <p class="fs-3 text-dark mb-0"><?php if ((float)$row['percentage_amount'] > 0) {
                                                                            echo $d->money_format($row['percentage_amount'], currency);
                                                                        } else {
                                                                            echo "No deposit yet";
                                                                        } ?></p>
                                    </td>
                                </tr>
                            <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>