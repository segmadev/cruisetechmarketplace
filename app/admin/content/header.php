<?php
require_once "../include/ini-session.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once "inis/ini.php"; ?>
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
    <?php require_once "content/head.php"; ?>
    <style>
        table li {
            list-style: none;
        }

            
            
          /* Center the spinner in the container */
          .loading-spinner-container {
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100%;
          min-height: 100px; /* Adjust based on the size of your modal */
      }
  
      /* Spinner Animation */
      .spinner {
          border: 4px solid rgba(0, 0, 0, 0.1);
          border-top: 4px solid #fa5a15;
          border-radius: 50%;
          width: 40px;
          height: 40px;
          animation: spin 1s linear infinite;
      }
  
      /* Spinner Keyframes */
      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader bg-transparent d-none" id="pagepreload">
        <img src="https://proloomtrading.com/images/w-loading.gif" alt="loader" class="lds-ripple img-fluid" />
        <!-- <p class="lds-ripple img-fluid">Finding message</p> -->
    </div>
    <!-- Preloader -->
    <!-- <div class="preloader">
      <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico" alt="loader" class="lds-ripple img-fluid" />
    </div> -->
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="index-2.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/<?= $d->get_settings('dark_logo') ?>" class="dark-logo" width="180" alt="" />
                        <img src="../assets/images/logos/<?= $d->get_settings('light_logo') ?>" class="light-logo" width="180" alt="" />
                    </a>
                    <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8 text-muted"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    <ul id="sidebarnav">
                        <?php  $c->generateNavigation($navs, $r); ?>
                    </ul>
                    <div class="unlimited-access hide-menu bg-light-danger position-relative my-7 rounded">
                        <div class="d-flex">
                            <div class="unlimited-access-title">
                                <h6 class="fw-semibold fs-4 mb-6 text-dark w-85"></h6>
                                <a href="index?logout" class="btn btn-danger fs-2 fw-semibold lh-sm">Logout</a>
                            </div>
                            <div class="unlimited-access-img">
                                <img src="dist/images/backgrounds/rocket.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="ti ti-search"></i>
                            </a>
                        </li>
                    </ul>

                    <!-- <div class="d-block d-lg-none">
                        <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg" class="dark-logo" width="180" alt="" />
                        <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/light-logo.svg" class="light-logo" width="180" alt="" />
                    </div> -->

                </nav>
            </header>
            <!--  Header End -->
            <form id="foo"></form>
            <div class="container-fluid">






