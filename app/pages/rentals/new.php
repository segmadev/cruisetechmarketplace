        <div class="card bg-white p-0">
        <div class="card-header">
                <h3>SMS Verifications </h3>
                <p class="m-0">Rent a USA phone number to receive OTP for <?= $d->get_settings("rental_number_expire_time") ?> minutes.</p>
                <p class="text-muted m-0">To view rented numbers <a href="index?p=rentals" class="btn-sm">click here</a>.</p>
                <form class="position-relative">
                    <input type="text" 
                    data-search-list="search-items-details" 
                    data-search-attribute="name" 
                    class="form-control product-search ps-5" 
                    id="input-search-service"
                        placeholder="Search service..." />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="card-body p-0 m-0">
                <p class="bg-danger ps-4 text-white"><b>Note that the price are not fixed price.</b></p>
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