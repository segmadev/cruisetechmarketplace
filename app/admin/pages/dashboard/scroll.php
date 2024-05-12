 <?php $script[] = "dashboard3";    ?>
 <!--  Owl carousel -->
 <div class="owl-carousel counter-carousel owl-theme">
     <div class="item">
         <div class="card border-0 zoom-in bg-light-primary shadow-none">
             <div class="card-body">
                 <div class="text-center">
                     <!-- <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-user-male.svg" width="50" height="50" class="mb-3" alt="" /> -->
                     <img src="https://www.svgrepo.com/show/530387/personal-account-account.svg" width="50" height="50" class="mb-3" alt="" />
                     <p class="fw-semibold fs-3 text-primary mb-1"> Users </p>
                     <h5 class="fw-semibold text-primary mb-0"><?= number_format($users_data['users_no']) ?></h5>
                     <a href="index?p=users" class='btn btn-sm btn-outline-primary'>View</a>
                 </div>
             </div>
         </div>
     </div>
     <div class="item">
         <div class="card border-0 zoom-in bg-light-warning shadow-none">
             <div class="card-body">
                 <div class="text-center">
                     <img src="https://www.svgrepo.com/show/530396/upload.svg" width="50" height="50" class="mb-3" alt="" />
                     <!-- <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-briefcase.svg" width="50" height="50" class="mb-3" alt="" /> -->
                     <p class="fw-semibold fs-3 text-warning mb-1">Accounts</p>
                     <h5 class="fw-semibold text-warning mb-0"><?= $users_data['no_account_added'] ?></h5>
                     <a href="index?p=account" class='btn btn-sm btn-outline-warning'>View Account</a>

                 </div>
             </div>
         </div>
     </div>
     <div class="item">
         <div class="card border-0 zoom-in bg-light-danger shadow-none">
             <div class="card-body p-4">
                 <div class="text-center">
                     <img src="https://www.svgrepo.com/show/530375/calendar.svg" width="50" height="50" class="mb-3" alt="" />
                     <!-- <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-favorites.svg" width="50" height="50" class="mb-3" alt="" /> -->
                     <p class="fw-semibold fs-3 text-danger mb-1 w-100">Orders</p>
                     <h5 class="fw-semibold text-danger  mb-0"><?= number_format($users_data['no_orders']) ?></h5>
                     <a href="index?p=orders" class='btn btn-sm btn-outline-danger'>View orders</a>
                 </div>
             </div>
         </div>
     </div>

     <div class="item">
         <div class="card border-0 zoom-in bg-light-info shadow-none">
             <div class="card-body">
                 <div class="text-center">
                     <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-mailbox.svg" width="50" height="50" class="mb-3" alt="" />
                     <p class="fw-semibold fs-3 text-info mb-1">Success Payment</p>
                     <h5 class="fw-semibold text-info mb-0"><?= number_format($users_data['no_of_success_payment']) ?></h5>
                     <a href="index?p=deposit" class='btn btn-sm btn-outline-info'>View</a>
                 </div>
             </div>
         </div>
     </div>
 </div>