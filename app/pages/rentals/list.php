
<div class="col-12 row">
    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-header">
                <h3>SMS Verifications</h3>
                <p>Rent a phone for 6 minutes.</p>
                <form class="position-relative">
                    <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Search service..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                  </form>
            </div>
        </div>
        <div class="card-body">
           <div class="responsive">
           <?php 
            if(count($rental_services) <= 0) {
                echo $c->empty_page("No Services Available at the moment.");
            }else{
                require_once "pages/rentals/services_list.php";
            }
           ?>

    
              <!-- table end -->
           </div>
        </div>
    </div>
</div>
<div class="col-12 col-md-7">
    <div class="card">

    </div>
</div>
</div>