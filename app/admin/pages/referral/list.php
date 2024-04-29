<div class="card">
    <div class="card-header">
        <h5 class="title">Referral Programs</h5>
        <a href="index?p=referral&action=new" class='btn btn-success'> Add new Referral Program</a>
    </div>
    <div class="card-body">
        <?php 
            if($ref_p->rowCount() > 0) {
                require_once "pages/referral/table.php";
            }
        ?>
    </div>
</div>