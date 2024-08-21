<div class="card card-body">
<h4 class="fw-semibold mb-8">Get a number</h4>
                  <nav aria-label="breadcrumb">
                    <p class="breadcrumb text-muted">
                        Get a phone number to receive OTP for <?= $d->get_settings("rental_number_expire_time") ?> minutes.
                    </p>
                  </nav>
                  <div>
                  <a href="index?p=rentals&action=new" class="btn btn-primary">Get a Number.</a>
                  </div>

</div>
<div class="col-12">

        <div class="card">
            <?php 
                require_once "pages/rentals/rented_list.php";
            ?>
        </div>
    </div>