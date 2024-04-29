<div class="card overflow-hidden">
    <div class="card-body p-5">
        <div class="mt-2"></div>
        <!-- <img src="https://img.freepik.com/free-vector/stock-market-doodle-concept-trader-work-poster_107791-13472.jpg?w=1380&t=st=1695116644~exp=1695117244~hmac=d17086a86825ae0b095d5f43170af81de73b7a614c6b6deece0bd6a8365f2a46" alt="" class="img-fluid"> -->
        <div class="row align-items-center">
            <div class="col-lg-4 order-lg-1 order-2">
                <div class="d-flex align-items-center justify-content-around m-4">
                    <div class="text-center">
                        <i class="ti ti-file-description fs-6 d-block mb-2"></i>
                        <h4 class="mb-0 fw-semibold lh-1">938</h4>
                        <p class="mb-0 fs-4">Posts</p>
                    </div>
                    <div class="text-center">
                        <i class="ti ti-user-circle fs-6 d-block mb-2"></i>
                        <h4 class="mb-0 fw-semibold lh-1">3,586</h4>
                        <p class="mb-0 fs-4">Followers</p>
                    </div>
                    <div class="text-center">
                        <i class="ti ti-user-check fs-6 d-block mb-2"></i>
                        <h4 class="mb-0 fw-semibold lh-1">2,659</h4>
                        <p class="mb-0 fs-4">Following</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                <div class="mt-n5">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;" ;>
                            <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;" ;>
                                <img src="<?= $u->get_profile_icon_link($userID) ?>" alt="" class="w-100 h-100">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="fs-5 mb-0 fw-semibold"><?= ucwords($user['first_name'].' '.$user['last_name']); ?></h5>
                        <p class="mb-0 fs-4"><?= $user['email'] ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-last">
                <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                    
                    <li><button class="btn btn-primary">Invest</button></li>
                </ul>
            </div>
        </div>
    </div>
</div>