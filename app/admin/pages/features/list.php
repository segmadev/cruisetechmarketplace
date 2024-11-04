<style>
    .box-icon {
        width: 40px;
        height: 40px;
        display: flex;
        text-align: center;
        align-items: center;
        justify-content: center;

    }
</style>
<div class="row">
    <div class="card p-3"><div class="card-header"><a href="index?p=features&action=new" class='btn btn-primary'><i class='ti ti-plus'></i> Add feature</a></div></div>
    <?php
        if($featuresList->rowCount() == 0) {
        echo $c->empty_page("No key feature added yet.", "<a class='btn btn-primary' href='index?p=features&action=new'><i class='ti ti-plus'></i> Add now!</a>");
        }else{
            $script[] = "sweetalert";
            foreach($featuresList as $row) {
    ?>
    <div class="col-md-4 col-12 p-2 detail-<?= $row['ID'] ?>">
        <div class="card p-3">
           <div class="row d-flex">
           <div class="col-2"><div class="box-icon rounded-circle border border-success  text-success fs-5"><?= htmlspecialchars_decode($row['icon']) ?></div></div>
            <div class="col-10">
                <h5><?= $row['title'] ?></h5>
                <p><?= $row['description'] ?></p>
            </div>
           </div>
           <div class="card-footer d-flex">
               <a href="index?p=features&action=edit&id=<?= $row['ID'] ?>" class='btn btn-outline-primary'> <i class='ti ti-edit text-primary'></i> </a> 
               <form action="" id="foo">
                    <input type="hidden" name="confirm" value="Are you sure you want to remove this feature?">
                    <input type="hidden" name="page" value="features">
                    <input type="hidden" name="deletedetails" value="features">
                    <input type="hidden" name="ID" value='<?= $row['ID'] ?>'>
                    <div id="custommessage"></div>
                    <button type="submit" class='btn btn-outline-danger ms-2'><i class='ti ti-trash text-danger'></i></button>
               </form>
            </div>
        </div>
    </div>
    <?php } } ?>
</div>