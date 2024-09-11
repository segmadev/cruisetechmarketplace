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
                        <!-- ============================= -->
                        <!-- Home -->
                        <!-- ============================= -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <!-- =================== -->
                        <!-- Dashboard -->
                        <!-- =================== -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index" aria-expanded="false">
                                <span>
                                    <i class="ti ti-aperture"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <!-- ============================= -->
                        <!-- Apps -->
                        <!-- ============================= -->

                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Manage Accounts</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-layout-sidebar"></i>
                                </span>
                                <span class="hide-menu">Accounts</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="index?p=account&action=new" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Publish New Account</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index?p=account" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Manage Accounts</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        


                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-layout-grid"></i>
                                </span>
                                <span class="hide-menu">Platforms</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="index?p=platform&action=new" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Create Platfrom</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index?p=platform" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">List of Platforms</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-layout-grid"></i>
                                </span>
                                <span class="hide-menu">Categories</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="index?p=category&action=new" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Create Category</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="index?p=category" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">List of Categories</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=orders" aria-expanded="false">
                                <span>
                                    <i class="ti ti-list"></i>
                                </span>
                                <span class="hide-menu">Orders</span>
                            </a>
                        </li>
                        <!-- Users start here -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Users</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=users&action=new" aria-expanded="false">
                                <span>
                                    <i class="ti ti-user-plus"></i>
                                </span>
                                <span class="hide-menu">Create</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=users" aria-expanded="false">
                                <span>
                                    <i class="ti ti-users"></i>
                                </span>
                                <span class="hide-menu">Mange Users</span>
                            </a>
                        </li>
                       
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=deposit" aria-expanded="false">
                                <span>
                                    <i class="ti ti-list"></i>
                                </span>
                                <span class="hide-menu">Payments</span>
                            </a>
                        </li>
                        
                        <!-- end of users -->






                       
                        <!-- ============================= -->
                        <!-- PAGES -->


                       



                        <!-- ============================= -->
                        <!-- Forms -->
                        <!-- ============================= -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Management</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=settings" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-settings"></i>
                                </span>
                                <span class="hide-menu">Settings</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=settings&action=edit" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-fingerprint"></i>
                                </span>
                                <span class="hide-menu">Edit Admin Account</span>
                            </a>
                        </li>




                        <!-- content -->

                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                <span class="d-flex">
                                    <i class="ti ti-box-multiple"></i>
                                </span>
                                <span class="hide-menu">Content</span>
                            </a>
                            <ul aria-expanded="false" class="collapse first-level">
                                <li class="sidebar-item">
                                    <a href="index?p=content&action=home" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Home Page</span>
                                    </a>
                                </li>
                                <!-- <li class="sidebar-item">
                    <a href="index?p=withdraw" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Manage withdrawal</span>
                    </a>
                  </li> -->

                                <!-- key features -->
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Key features</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse two-level">
                                        <li class="sidebar-item">
                                            <a href="index?p=features&action=new" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Add feature</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="index?p=features&action=list">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Manage features</span>
                                            </a>

                                        </li>
                                    </ul>
                                </li>
                                <!-- how it works -->
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">How it works</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse two-level">
                                        <li class="sidebar-item">
                                            <a href="index?p=how_it_works&action=new" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Add stage</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="index?p=how_it_works&action=list">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Manage stages</span>
                                            </a>

                                        </li>
                                    </ul>
                                </li>
                                <!-- testimonies -->

                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Testimonies</span>
                                    </a>
                                    <ul aria-expanded="false" class="collapse two-level">
                                        <li class="sidebar-item">
                                            <a href="index?p=testimonies&action=new" class="sidebar-link">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Add testimony</span>
                                            </a>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="index?p=testimonies&action=list">
                                                <div class="round-16 d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-circle"></i>
                                                </div>
                                                <span class="hide-menu">Manage testimonies</span>
                                            </a>

                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <!-- end of content -->

                        <!-- Email Template -->

                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Email Template</span>
                        </li>
                        <!-- <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=email_template&action=new" aria-expanded="false">
                                <span>
                                    <i class="ti ti-plus"></i>
                                </span>
                                <span class="hide-menu">Create Template</span>
                            </a>
                        </li> -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="index?p=email_template" aria-expanded="false">
                                <span>
                                    <i class="ti ti-list"></i>
                                </span>
                                <span class="hide-menu">Template List</span>
                            </a>
                        </li>


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