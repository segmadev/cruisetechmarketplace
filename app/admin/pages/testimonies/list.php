<div class="row">
    <div class="card p-3">
        <div class="card-header"><a href="index?p=testimonies&action=new" class='btn btn-primary'><i class='ti ti-plus'></i> Add testimony</a></div>
    </div>
    <?php
    if ($testimoniess->rowCount() == 0) {
        echo $c->empty_page("No testimony added yet.", "<a class='btn btn-primary' href='index?p=testimonies&action=new'><i class='ti ti-plus'></i> Add now!</a>");
    } else {
        $script[] = "sweetalert";
        foreach ($testimoniess as $row) {
    ?>
            <div class="col-md-4 col-12 p-2 detail-<?= $row['ID'] ?>">
                <div class="card">
                    <div class="row d-flex card-header">
                        <div class="col-3">
                            <div class="rounded-circle overflow-hidden me-6">
                                <img src="../assets/images/profile/<?= $row['profile_image'] ?>" alt="" width="40" height="40">
                            </div>
                        </div>
                        <div class="col-9">
                            <h5><?= $row['name'] ?></h5>
                            <small><b><?= $row['position'] ?></b></small>
                        </div>
                    </div>
                    <div class="card-body"><p>"<?= $row['testimony'] ?>"</p></div>
                    <div class="card-footer d-flex">
                        <a href="index?p=testimonies&action=edit&id=<?= $row['ID'] ?>" class='btn btn-outline-primary'> <i class='ti ti-edit text-primary'></i> </a>
                        <form action="" id="foo">
                            <input type="hidden" name="confirm" value="Are you sure you want to remove this feature?">
                            <input type="hidden" name="page" value="testimonies">
                            <input type="hidden" name="deletedetails" value="testimonies">
                            <input type="hidden" name="ID" value='<?= $row['ID'] ?>'>
                            <div id="custommessage"></div>
                            <button type="submit" class='btn btn-outline-danger ms-2'><i class='ti ti-trash text-danger'></i></button>
                        </form>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</div>