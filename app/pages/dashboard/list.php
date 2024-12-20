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
              <h5 class="fw-semibold mb-0 fs-5">Welcome <?= $d->short_text($u->get_name($userID), 15, true) ?>!</h5>
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
                <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center"><?= number_format($totalOrders); ?></h3>
                <p class="mb-0 text-dark">Your Total orders</p>
                <a href="index?p=orders" class="btn btn-sm btn-primary">View orders</a>
              </div>
            </div>
          </div>
          <div class="col-sm-5">
            <div class="welcome-bg-img mb-n7 text-end">
              <img
                src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg"
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
              Get phone number to receive OTP for <a data-url="index?p=rentals&network=1&action=new"> short term </a> or
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