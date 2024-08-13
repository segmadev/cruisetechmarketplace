<div class="col-12 row">
    <div class="col-12 col-md-5">
        <div class="card bg-white p-0">
        <div class="card-header">
                <h3>SMS Verifications</h3>
                <p>Rent a phone number to receive OTP for <?= $d->get_settings("rental_number_expire_time") ?> minutes.</p>
                <form class="position-relative">
                    <input type="text" 
                    data-search-list="search-items" 
                    data-search-attribute="name" 
                    class="form-control product-search ps-5" 
                    id="input-search-service"
                        placeholder="Search service..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="card-body overflow-scroll p-0 m-0" style="height: calc(100vh - 100px)">
                <?php
                if (count($rental_services) <= 0) {
                    echo $c->empty_page("No Services Available at the moment.");
                } else {
                    require_once "pages/rentals/services_list.php";
                }
                ?>


                <!-- table end -->
            </div>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="card">
            <?php 
                require_once "pages/rentals/rented_list.php";
            ?>
        </div>
    </div>
</div>