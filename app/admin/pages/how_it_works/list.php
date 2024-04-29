<div class="row">
    <div class="card p-3"><div class="card-header"><a href="index?p=how_it_works&action=new" class='btn btn-primary'><i class='ti ti-plus'></i> Add New</a></div></div>
    <?php
        if($how_it_workss->rowCount() == 0) {
            echo $c->empty_page("No how it works added yet.", "<a class='btn btn-primary' href='index?p=how_it_works&action=new'><i class='ti ti-plus'></i> Add now!</a>");
        }else{
            $script[] = "sweetalert";
            foreach($how_it_workss as $row) {
    ?>
    <div class="col-md-4 col-12 p-2 detail-<?= $row['ID'] ?>">
        <div class="card p-3">
           <div class="">
           <div class="col-12">
                <img style="width: 100%" src="../assets/images/banners/<?= $row['image'] ?>" alt="" srcset="">
           </div>
            <div class="col-12">
                <h5><?= $row['title'] ?></h5>
                <p><?= $row['description'] ?></p>
            </div>
           </div>
           <div class="card-footer d-flex">
               <a href="index?p=how_it_works&action=edit&id=<?= $row['ID'] ?>" class='btn btn-outline-primary'> <i class='ti ti-edit text-primary'></i> </a> 
               <form action="" id="foo">
                    <input type="hidden" name="confirm" value="Are you sure you want to remove this feature?">
                    <input type="hidden" name="page" value="how_it_works">
                    <input type="hidden" name="deletedetails" value="how_it_works">
                    <input type="hidden" name="ID" value='<?= $row['ID'] ?>'>
                    <div id="custommessage"></div>
                    <button type="submit" class='btn btn-outline-danger ms-2'><i class='ti ti-trash text-danger'></i></button>
               </form>
            </div>
        </div>
    </div>
    <?php } } ?>
</div>