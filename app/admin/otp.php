<!DOCTYPE html>
<html lang="en">
<?php 
define('PATH', str_replace('//', '/', str_replace('app/api', '', rtrim(parse_url(str_replace('/cruise/', '', $_SERVER['REQUEST_URI']), PHP_URL_PATH), '/'))));
require_once "include/auth-ini.php"; ?>  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:11:04 GMT -->
<head>
    <!--  Title -->
    <title><?= company_name ?> OTP Login</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="<?= company_name ?>" />
    <meta name="author" content="" />
    <meta name="keywords" content="<?= company_name ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= $favicon ?>" />
    
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="../dist/css/style-orange.min.css" />
    <style>
        /* Center the spinner in the container */
        .loading-spinner-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        min-height: 100px;
        /* Adjust based on the size of your modal */
    }

    /* Spinner Animation */
    .spinner {
        border: 4px solid lightgrey;
        border-top: 4px solid green;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
</style>
  </head>
  <body>
      <!-- Preloader -->
      <div class="preloader">
      <img src="<?= $favicon ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>
    
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100">
        <div class="position-relative z-index-5">
          <div class="row">
            <div class="col-lg-6 col-xl-8 col-xxl-9">
              <a href="index" class="text-nowrap logo-img d-block px-4 py-9 w-100">
              <img src="<?= $dark_logo ?>" width="180" alt="">
              </a>
              <div class="d-none d-lg-flex align-items-center justify-content-center" style="height: calc(100vh - 80px);">
                <img src="http://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/login-security.svg" alt="" class="img-fluid" width="500">
              </div>
            </div>
            <div class="col-lg-6 col-xl-4 col-xxl-3">
              <div class="card mb-0 shadow-none rounded-0 min-vh-100 h-100">
                <div class="d-flex align-items-center w-100 h-100">
                    <?php if(isset($_GET['em']) && $_GET['em'] != ""){ ?>
                    <div class="card-body">
                    <div class="mb-5">
                      <h2 class="fw-bolder fs-7 mb-3">Verify who you are?</h2>
                      <p class="mb-0 ">   
                        We have sent a verification code to your email <b><?= isset($_GET['em']) && filter_var($email = base64_decode($_GET['em']), FILTER_VALIDATE_EMAIL) ? substr($email, 0, 2) . '***@' . substr(explode('@', $email)[1], 0, 2)  : 'Invalid email'; ?>                        </b>, input the code below for verification.
                      </p>
                    </div>
                    <form id="foo" action="auth">
                      <div class="mb-3">
                        <label for="codeverify" class="form-label">Code</label>
                        <input type="number" name="codeverify" class="form-control" id="codeverify" placeholder="0000" aria-describedby="emailHelp">
                      </div>
                      <input type="hidden" name="verify_otp">
                      <div id="custommessage"></div>
                      <button type="submit" class="btn btn-primary w-100 py-8 mb-3">Verify</button>
                      <a href="login" class="btn btn-light-primary text-primary w-100 py-8">Back to Login</a>
                    </form>
                  </div>
<?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
    
    <!--  Import Js Files -->
    <script src="../dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="../dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="../dist/js/app.min.js"></script>
    <script src="../dist/js/app.init.js"></script>
    <script src="../dist/js/app-style-switcher.js"></script>
    <script src="../dist/js/sidebarmenu.js"></script>
    
    <script src="../dist/js/custom.js"></script>
    <script src="../dist/js/my.js?n=2"></script>
  </body>

<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:11:04 GMT -->
</html>