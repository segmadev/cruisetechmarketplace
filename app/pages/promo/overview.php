<?php 
$promo = $d->getall("promo_assigned", "userID = ? and end_date >= ? and status = ?", [$userID, time(), "active"]);
if(isset($upromo) && is_array($upromo) && !is_array($promo)) {
?>  

<div class="card bg-light-success gradient-border border-success border-2">
    <div class="d-flex p-3">
       
        <div class="unlimited-access-img">
                <img width="50" src="dist/images/backgrounds/rocket.png" alt="" class="img-fluid">
              </div>
   
        <div class="p-2">
            <h1 class="card-title text-success">
                <b>Promo Offer - <?= $upromo['rate'] ?>X</b>
            </h1>
            <b><p class="fs-3">Enjoy <?= $upromo['rate'] ?>X more profit on all your investment trades for the next <?= $upromo['number_of_days'] ?> days.</p></b>
            <a href="index?p=promo&action=new" class="btn btn-success">Activate Promo</a>
        </div>

    </div>
</div>
<?php } ?>