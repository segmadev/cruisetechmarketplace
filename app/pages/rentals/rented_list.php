<?php 
    if($no_rented < 1) {
        echo $c->empty_page("No number(s) rented yet.");
    }else{
      $script[] = "countdown";
        $rented_numbers = $d->getall("orders", "userID = ? and accountID != ? and order_type = ? and status = ? ORDER BY date desc", [$userID, "", "rentals", 1], fetch: "all");
        $rented_numbers_non_active = $d->getall("orders", "userID = ? and accountID != ? and order_type = ? and (status = ? or status = ?) ORDER BY date LIMIT 20", [$userID, "", "rentals", 0, 2], fetch: "all");
?>
<div class="card overflow-hidden chat-application bg-white">
            <div class="d-flex align-items-center justify-content-between gap-3 m-3 #d-lg-none  ">
              
              <form class="position-relative w-100">
                <input type="text" 
                data-search-list="service-details"
                data-search-attribute="username"
                class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Number or service type">
                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
              </form>
            </div>
            <div class="d-flex w-100">
                <!-- side menu -->
              <div class="left-part border-end w-20 flex-shrink-0 d-none">
                <div class="px-9 pt-4 pb-3">
                  <button class="btn btn-primary fw-semibold py-8 w-100">Add New Contact</button>
                </div>
                <ul class="list-group" style="height: calc(100vh - 400px)" data-simplebar>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-inbox fs-5"></i>All Contacts </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-star"></i>Starred </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-file-text fs-5"></i>Pening Approval </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-alert-circle"></i>Blocked </a>
                  </li>
                  <li class="border-bottom my-3"></li>
                  <li class="fw-semibold text-dark text-uppercase mx-9 my-2 px-3 fs-2">CATEGORIES</li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-primary"></i>Engineers </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-warning"></i>Support Staff </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-success"></i>Sales Team </a>
                  </li>
                </ul>
              </div>
              <div class="d-flex w-100">
                <div class="w-100">
                  <div class="border-end user-chat-box h-100">
                    <div class="px-4 pt-9 pb-6 d-none d-none">
                      <form class="position-relative">
                        <input type="text" 
                        data-search-list="service-details"
                        data-search-attribute="username"
                        class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Number or service type" />
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                      </form>
                    </div>
                    <div class="app-chat p-3">
                      <ul class="chat-users" style="height: calc(100vh - 100px)" data-simplebar>
                      <div class="flex-row row ">
                      <?php 
                      foreach($rented_numbers as $rent) {  
                        require "pages/rentals/single_number.php";
                       } 
                       echo "<h5 class='w-100 bg-light-danger p-2 ps-4'> Non Active Numbers</h5>";
                      foreach($rented_numbers_non_active as $rent) {  
                        require "pages/rentals/single_number.php";
                       } 
                       
                       ?>
                      </div>
                        
                      </ul>
                    </div>
                  </div>
                </div>
              
              </div>
              <div class="offcanvas offcanvas-start user-chat-box" tabindex="-1" id="chat-sidebar" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                  <h5 class="offcanvas-title" id="offcanvasExampleLabel"> Contact </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="px-9 pt-4 pb-3">
                  <button class="btn btn-primary fw-semibold py-8 w-100">Add New Contact</button>
                </div>
                <ul class="list-group" style="height: calc(100vh - 150px)" data-simplebar>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-inbox fs-5"></i>All Contacts </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-star"></i>Starred </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-file-text fs-5"></i>Pening Approval </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-alert-circle"></i>Blocked </a>
                  </li>
                  <li class="border-bottom my-3"></li>
                  <li class="fw-semibold text-dark text-uppercase mx-9 my-2 px-3 fs-2">CATEGORIES</li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-primary"></i>Engineers </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-warning"></i>Support Staff </a>
                  </li>
                  <li class="list-group-item border-0 p-0 mx-9">
                    <a class="d-flex align-items-center gap-2 list-group-item-action text-dark px-3 py-8 mb-1 rounded-1" href="javascript:void(0)">
                      <i class="ti ti-bookmark fs-5 text-success"></i>Sales Team </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <?php } ?>