<!-- <div class='bg-danger text-white p-2 rounded mb-2'>
    <p>We are currently experiencing some technical difficulties, but our team is working hard to resolve them. <br> We appreciate your patience and expect everything to be back to normal soon.</p>
</div> -->
<?php
// $script[] = "badge";
$totalOrders = $d->getall("orders", "userID = ?", [$userID], fetch: "");
require_once "pages/profile/stage.php";
?>

<div class="col-12 d-sm-none d-block">
    <?= $u->show_balance($userID, showBtn: true); ?>
</div>
<div class="col-12 d-flex gap-2">
    <div class="col-lg-8 col-12 d-flex align-items-stretch d-sm-block d-none">
        <div class="card w-100 bg-light-danger overflow-hidden shadow-none">
            <div class="card-body position-relative">
                <div class="row">
                    <div class="col-sm-7">
                        <div class="d-flex align-items-center mb-7">
                            <?= $u->displayProfile($userID, $user_data['stage']['position'] ?? 0) ?>
                            <div class="text">
                                <h5 class="fw-semibold mb-0 fs-5">Welcome
                                    <?= $d->short_text($u->get_name($userID), 15, true) ?>!</h5>
                                <p><b>Stage: <?= $user_data['stage']['name'];  ?></b></p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="border-end pe-4 border-muted border-opacity-10">
                                <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center" id='accountBalanceValue1'
                                    data-load='deposit' data-displayId='accountBalanceValue1' data-isreplace='true'
                                    data-path='passer?p=account&get_balance=yes'><i
                                        class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h3>
                                <p class="mb-0 text-dark">Your Balance</p>
                                <a data-url="index?p=deposit" class="btn btn-sm btn-dark">Deposit</a>
                            </div>
                            <div class="ps-4">
                                <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">
                                    <?= number_format($totalOrders); ?></h3>
                                <p class="mb-0 text-dark">Your Total orders</p>
                                <a href="index?p=orders" class="btn btn-sm btn-primary">View orders</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="welcome-bg-img mb-n7 text-end">
                            <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg"
                                alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="accountpage"></div>

    <!-- rentent number part -->
    <div class="col-lg-4 col-md-4 col-12">
        <div class="card-body bg-light-primary px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Get a number</h4>
                    <nav aria-label="breadcrumb">
                        <p class="text-muted">
                            Get phone number to receive OTP for <a data-url="index?p=rentals&network=1&action=new">
                                short term </a> or
                            <a data-url="index?p=rentals&network=1&action=new&type=long_term"> long term </a> use.
                        </p>
                    </nav>
                    <hr>
                    <a href="index?p=rentals&action=new" class="btn btn-primary">Get Number.</a>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="dist/images/backgrounds/rental.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Overlay styles */
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    background-image: url('https://media3.giphy.com/media/tIHktzgRi8yjIplFVI/200w.gif?cid=6c09b952kvnkv6klold9tyrajjdy2feaukdemmy6bn7u7dz4&ep=v1_stickers_search&rid=200w.gif&ct=s');
}

.overlay.active {
    visibility: visible;
    opacity: 1;
}

.overlay-content {
    position: relative;
    /* background-color: #fff; */
    padding: 20px;
    border-radius: 10px;
    /* box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); */
    max-width: 90%;
    max-height: 90%;
    overflow: auto;
    animation: pop-in 0.5s ease;
}

.overlay img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #f44336;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 16px;
    cursor: pointer;
}

.close-btn:hover {
    background-color: #d32f2f;
}

@keyframes pop-in {
    0% {
        transform: scale(0.5);
        opacity: 0;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
<!-- <div class="overlay" id="imageOverlay">
    <div class="overlay-content">
        <button class="close-btn" id="closeOverlay">&times;</button>
        <a href="index?p=profile&action=new">
            <img src="images/discount.png" alt="Overlay Image">
        </a>
    </div>
</div> -->

<script>
// const overlay = document.getElementById('imageOverlay');
// const closeOverlayBtn = document.getElementById('closeOverlay');

// let overlayCount = localStorage.getItem('overlayCount') || 0;

// if (overlayCount < 2) {
//     overlay.classList.add('active');
//     localStorage.setItem('overlayCount', ++overlayCount);
// }


// closeOverlayBtn.addEventListener('click', () => {
//     overlay.classList.remove('active');
// });
</script>

<hr>
<?php
// echo "<div class='d-flex gap-2'>
// <div class='col-12 col-md-6 col-lg-4'>".$u->show_balance($userID, showBtn: true)."</div>
// <div class='col-12 col-md-6 col-lg-4'>Get Number</div>
// </div>";
$categories = $d->getall("category", 'date != ? order by date ASC', [""], fetch: "all");
if ($categories->rowCount() > 0) {
    require_once "pages/dashboard/category_list.php";
    foreach ($categories as $category) {
        require "pages/dashboard/category.php";
    }
}
$category = ["ID" => "", "name" => "Others"];
require "pages/dashboard/category.php";
?>