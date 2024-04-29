<div class="d-flex align-items-stretch w-100">
    <div class="card w-100">
    <div class="card-header">
    <h5 class="card-title fw-semibold"><?= $act_title ?></h5>
            <p class="card-subtitle"><?= $act_des ?></p>
    </div>
    <?php if($page != "activities"){ ?>
        <div class="card-body overflow-auto" style="height: 400px">
            <?php }else{  ?>
                <div class="card-body">
                <?php }  ?>
            <?php if (isset($activities) && $activities->rowCount() > 0) {
                $script[] = "modal";
                foreach ($activities as $row) {
            ?>
                    <div class="mt-9 py-6 d-flex align-items-center">
                        <button data-url="modal?p=activities&action=view&id=<?= $row['ID'] ?>" data-title="Activity Details" onclick="modalcontent(this.id)" data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" id="act-<?= $row['ID'] ?>" class="btn flex-shrink-0 bg-light-primary rounded-circle round d-flex align-items-center justify-content-center">
                            <i class="ti ti-eye text-primary fs-6"></i>
                        </button>
                        <div class="ms-3">
                            <h6 class="mb-0 fw-semibold"> <?= $row['action_name'] ?></h6>
                                <span class="fs-3"><?= $row['description'] ?></span>
                        </div>
                        <div class="ms-2">
                            <span class="fs-2"><?= $d->date_format($row['date']); ?></span>
                        </div>
                    </div>
            <?php }
            } ?>
        </div>
        <?php if($page != "activities"){ ?>
        <div class="card-footer">
            <a href="index?p=activities" class="w-100 btn btn-outline-primary text-center">View All</a>
        </div>
        <?php } ?>
    </div>
</div>