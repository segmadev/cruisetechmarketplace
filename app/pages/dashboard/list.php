<?php
$script[] = "chart";
$script[] = "modal";
$platforms = $d->getall("platform", fetch: "moredetails");
$accountNo = $d->getall("account", "status = ?", [1], fetch: "");
if (isset($_SESSION['newuser'])) {
    // require_once "pages/dashboard/new.php";
}
// var_dump(session_get_cookie_params());
?>

<div class="p-2 fixed m-0">
    <h1 class="h5">Market Place</h1>
    <p>Buy last longing account from us.</p>
</div>
<div class="card p-2 mt-2">
    <div class="card-header">
        <div class="row flex d-flex">
            <div class="col-7 p-0">
                <input type="search" name="" class="form-control w-100 m-0" placeholder="Search Account" data-id="#accountList" id="searchMarket">
            </div>
            <div class="col-5">
                <div class="dropdown">
                    <a href="javascript:void(0)" id="m2" class="btn btn-primary flex grow d-flex" style="width: 100%;" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-filter fs-4"></i> <b id="PlaformName">All</b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="m2" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 21px, 0px);">
                    <li>
                                    <a class="dropdown-item" href="#" onclick="addPlatfrom('', 'All')">
                                         All Platform </a>
                                </li>
                        <?php if ($platforms->rowCount() > 0) {
                            foreach ($platforms as $platform) {
                        ?>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="addPlatfrom('<?= $platform['ID'] ?>', '<?= $platform['name'] ?>')">
                                        <img src="assets/images/icons/<?= $platform['icon'] ?>" style="width: 20px;"  class="rounded" alt=""> <?= $platform['name'] ?> </a>
                                </li>
                        <?php  }
                        } else {
                            echo "No Filter Avilable";
                        } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0 m-0">
            <?php
            if ($accountNo == 0) {
                echo $c->empty_page("No account avilable at the moment, <br> Please check back later.");
            } else {
                require_once "pages/account/list_account_fetch.php";
            }
            ?>
    </div>
</div>