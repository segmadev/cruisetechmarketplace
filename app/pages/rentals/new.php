    <div class="card bg-white p-0">
        <div class="card-header">
                <h3>SMS Verifications </h3>
                <p class="m-0">Get USA phone number to receive OTP for <a href="index?p=rentals&network=1&action=new">short term</a> or <a href="index?p=rentals&network=1&action=new&type=long_term"> long term</a> use.</p>
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
                <p class="bg-danger ps-4 text-white"><b>Note that the price are not fixed.</b></p>
                <div class="d-flex p-2 flex gap-2">
               
                    <div class="btn-group mb-2 print-ignore">
                            <button class="btn btn-sm <?php if($number_type == "short_term"){ echo 'btn-primary'; } else { echo 'btn-light-danger'; }; ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-phone"></i> 
                                <?php if($number_type == "short_term"){ echo 'Short Term Number '.$network; }else{ echo " Short term Numbers"; } ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <li><a class="dropdown-item" href="index?p=rentals&action=new">Short Term Number 1</a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=2&action=new">Short Term Number 2</a></li>
                            </ul>
                        </div>
                   
                    <div class="btn-group mb-2 print-ignore">
                            <button class="btn btn-sm <?php if($number_type != "short_term"){ echo 'btn-primary'; } else { echo 'btn-light-danger'; }; ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-phone"></i> <?php if($number_type != "short_term"){ echo str_replace("_", " ", $number_type). ' Network '.$network; }else{ echo "Long Term Number"; } ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">
                                <li><a class="dropdown-item" href="index?p=rentals&action=new&type=3days">Long Term Number <span class="text-primary">(3days)</span> </a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&action=new&type=long_term">Long Term Number <span class="text-primary">(30days)</span></a></li>
                                <li><a class="dropdown-item" href="index?p=rentals&network=2&action=new&type=long_term">LTR (Network 2) <span class="text-primary">(30days)</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php 
                    if($number_type == "short_term" && $network == 1) require_once "pages/rentals/short.php"; 
                    if($number_type == "long_term" || $number_type == '3days' || $network == 2) require_once "pages/rentals/long.php";
                ?>
                <!-- table end -->
            </div>
        </div>

        
<script>
     document.querySelectorAll('.heart-button').forEach(button => {
            button.addEventListener('click', () => {
                const heartPath = button.querySelector('.heart-path');
                // Toggle fill color
                if (heartPath.getAttribute('fill') === '#fa5a15') {
                    heartPath.setAttribute('fill', 'none');
                } else {
                    heartPath.setAttribute('fill', '#fa5a15');
                }
            });
        });
</script>