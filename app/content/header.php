<?php
require_once "include/ini-session.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once "include/ini.php"; ?>
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/ by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:00:13 GMT -->

<head>
  <!--  Title -->
  <title><?= company_name ?></title>
  <!--  Required Meta Tag -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
  <meta name="handheldfriendly" content="true" />
  <meta name="MobileOptimized" content="width" />
  <meta name="description" content="<?= company_name ?>" />
  <meta name="author" content="" />
  <meta name="keywords" content="<?= company_name ?>" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!--  Favicon -->
  <link rel="shortcut icon" type="image/png" href="<?= $favicon ?>" />

  <!-- Owl Carousel  -->
  <?php require_once "content/head.php"; ?>
  <style>
    input[type="number"] {
      font-size: 15px;
      border: none;
      padding: 5px 0px;
      /* padding-top: ; */

    }

    li {
      list-style: none !important;
    }
    body .preloader {
      background-color: rgba(124, 143, 172, 0.75)!important;
    }
  </style>
  <link rel="stylesheet" href="dist/css/badge.css?n=6432">
</head>

<body>
  <!-- Preloader -->
  <div class="preloader bg-transparent d-none" id="pagepreload">
        <img src="https://proloomtrading.com/images/w-loading.gif" alt="loader" class="lds-ripple img-fluid" />
        <!-- <p class="lds-ripple img-fluid">Finding message</p> -->
    </div>
  <!-- <div class="preloader">
      <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div> -->
  <!-- Preloader -->
  <!-- <div class="preloader">
      <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div> -->
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full" data-sidebar-position="" data-header-position="">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between bg-white">
          <a href="../" class="text-nowrap logo-img">
            <img src="<?= $logo ?>" class="dark-logo" width="140" alt="" />
          </a>
          <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8 "></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
          <ul id="sidebarnav">
            <!-- ============================= -->
            <!-- Home -->
            <!-- ============================= -->
            <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Theme</span>
            </li>
           -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Dashboard</span>
            </li>
            <!-- =================== -->
            <!-- Dashboard -->
            <!-- =================== -->
            <li class="sidebar-item">
              <a class="sidebar-link" data-url="index" aria-expanded="false">
                <span>
                  <i class="ti ti-dashboard"></i>
                </span>
                <span class="hide-menu">Home</span>
              </a>
            </li>
         
            <!-- ============================= -->
            <!-- Apps -->
             
            <!-- ============================= -->
            
            <li class="sidebar-item">
              <a class="sidebar-link" data-url="index?p=rentals" aria-expanded="false">
                <span>
                  <i class="ti ti-phone"></i>
                </span>
                <span class="hide-menu">Manage Numbers</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" data-url="index?p=orders&type=account" aria-expanded="false">
                <span>
                  <i class="ti ti-social"></i>
                </span>
                <span class="hide-menu">Social Media Accounts</span>
              </a>
            </li>
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="index?p=orders" aria-expanded="false">
                <span>
                  <i class="ti ti-align-box-bottom-center"></i>
                </span>
                <span class="hide-menu">Orders</span>
              </a>
            </li> -->
            <!-- ============================= -->
            <!-- PAGES -->
            <!-- ============================= -->
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Transactions</span>
            </li>

            
            <li class="sidebar-item">
              <a class="sidebar-link" data-url="index?p=deposit" aria-expanded="false">
                <span>
                  <i class="ti ti-currency-dollar"></i>
                </span>
                <span class="hide-menu">Wallet</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" data-url="index?p=deposit&action=transactions" aria-expanded="false">
                <span>
                  <i class="ti ti-list "></i>
                </span>
                <span class="hide-menu">Transactions</span>
              </a>
            </li>

            <!-- <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">activities</span>
            </li> -->
            <!-- Activites -->
            <!-- <li class="sidebar-item">
              <a class="sidebar-link" href="index?p=activities" aria-expanded="false">
                <span>
                  <i class="ti ti-list"></i>
                </span>
                <span class="hide-menu">All Activities</span>
              </a>
            </li> -->
            <!-- ============================= -->

          </ul>
       
          <form action="index?logout=" method="post">
            <div class=" fixed-profile bg-light-secondary rounded sidebar-ad m-0 p-0 p-3 mt-3">
              <a href='index?p=profile' class="hstack gap-3">
                <div class="john-img" style="zoom: 0.5; position: relative">
                  <?= $u->displayProfile($userID, $user_data['stage']['position'] ?? 0) ?>

                 
                </div>
                <div class="john-title">
                  <h6 class="mb-0 fs-3 fw-semibold"><?= $d->short_text($u->get_name($userID), 8, true) ?></h6>

                </div>
                <button class="border-0 p-2 rounded-1 text-danger bg-light-danger ms-auto" tabindex="0" type="submit" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                  <i class="ti ti-power fs-6"></i>
                </button>
              </a>
            </div>
          </form>
          <?php if($d->get_settings("telegram")) { ?>
          <ul id="sidebarnav">
            <li class="sidebar-item bg-light-primary mt-2">
              <a class="sidebar-link" target="_BLANK" href="<?= $d->get_settings("telegram") ?>" aria-expanded="false">
                <span>
                  <img src="https://cdn.pixabay.com/photo/2021/12/27/10/50/telegram-icon-6896828_960_720.png" style="width:20px" srcset="Join out telegram channel">
                </span>
                <span class="hide-menu"> <span class="m-0 p-0">Join Our Telegram</span> <p class="fs-2 text-muted m-0 p-0">for news and update</p></span>
                
              </a>
            </li>
            </ul>
            <?php } ?>

            <?php if($d->get_settings("get_help") && $d->get_settings("get_help") != "" && $d->get_settings("get_help") != "array") { ?>
              <ul id="sidebarnav bg-transparent border-1 border-danger">
                <li class="sidebar-item  mt-2">
                  <a class="sidebar-link" href="index?p=viewer&action=new" aria-expanded="false">
                    <span>
                      <img src="https://upload.wikimedia.org/wikipedia/commons/e/ef/Youtube_logo.png" style="width:20px" srcset="">
                    </span>
                    <span class="hide-menu"> <span class="m-0 p-0 text-danger">Get Help</span> <p class="fs-2 text-muted m-0 p-0">How to use our platfrom</p></span>
                    
                  </a>
                </li>
                </ul>
            <?php } ?>

        </nav>

        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header p-2">
        <nav class="navbar navbar-expand-lg navbar-light bg-white p-0 ps-3 rounded">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link sidebartoggler nav-icon-hover ms-n3 " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2 bg-primary text-light p-2 rounded"></i>
              </a>
            </li>
            <li class="nav-item d-none d-lg-block">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="ti ti-search"></i>
              </a>
            </li>
          </ul>
          <div class="d-block d-lg-none">
            <img src="<?= $dark_logo ?>" class="dark-logo" width="180" alt="" />
            <img src="<?= $light_logo ?>" class="light-logo" width="180" alt="" />
          </div>
          <!-- <button onclick="loadnotification()" class="btn mb-1 btn-light-info text-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            <a class="nav-link nav-icon-hover" href="javascript:void(0)">
              <div class="icon bg-primary rounded-circle"></div>
              <i class="ti ti-bell-ringing"></i> <span class='text-danger fw-semibold'><b id="note-no"></b></span>
            </a>
          </button> -->
          <a class="navbar-toggler p-0 border-0" href="index?p=profile" type="button" id="drop2">
            <div class="nav-link nav-icon-hover" style="scale: 0.8">
              <!-- <?php // $u->displayProfile($userID, $user_data['stage']['position'] ?? 0) ?> -->
              <img src="<?= $u->get_profile_icon_link($userID) ?>" class="rounded-circle" width="40" height="40" alt="">
              <!-- <div class="notification bg-primary rounded-circle"></div> -->
            </div>
          </a>
          <div class="navbar-collapse justify-content-end collapse" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
              <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                <i class="ti ti-align-justified fs-7"></i>
              </a>

            </div>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid px-2" id="maincontentdiv">
        