<!DOCTYPE html>
<html lang="en">
<?php 
define("PATH", "");
require_once "include/auth-ini.php"; ?>  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:11:04 GMT -->
<head>
    <!--  Title -->
    <title><?= company_name ?> Sign In</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Sign In" />
    <meta name="author" content="" />
    <meta name="keywords" content="Sign In" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= $favicon ?>" />
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="dist/css/style-orange.min.css" />

    <style>
          
            
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
    <div class="preloader">
      <img src="<?= $favicon ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
      <img src="<?= $favicon ?>" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
          <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-3">
              <div class="card mb-0">
                <div class="card-body">
                  <a href="../" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                    <img src="<?= $dark_logo ?>" width="180" alt="">
                  </a>
                 
                  <div class="position-relative text-center my-4">
                    <h1 class="mb-0 px-3 d-inline-block text-primary h4 z-index-5 position-relative">Sign In</h1>
                    
                    <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                  </div>
                  <form id="foo" action="auth">
                    <?php 
                      $login_form = [
                        "email" => ["input_type"=>"email", "is_required"=>false, "global_class"=>"w-100", "class"=>"w-100"],
                        "password" => ["input_type"=>"password", "global_class"=>"w-100", "class"=>"w-90"],
                    ];
                     echo $c->create_form($login_form);
                      if(isset($_GET['urlgoto']) && !empty($_GET['urlgoto']))  {
                        $urlgoto = $_GET['urlgoto'];
                        echo "<input type='hidden' value='$urlgoto' name='urlgoto'>";
                      }
                    ?>

                    <input type="hidden" name="signin">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <div class="form-check">
                        <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                        <label class="form-check-label text-dark" for="flexCheckChecked">
                          Remember this Device
                        </label>
                      </div>
                      <a class="text-primary fw-medium" href="forget_password">Forgot Password ?</a>
                    </div>
                    <div>
                      <p>
                      <small>
                        By signing in, you agree to our
                        <a class="" href="/?page=terms" target="_blank" rel="noopener noreferrer">Terms and conditions</a> and 
                        <a class="" href="/?page=policy" target="_blank" rel="noopener noreferrer">Privacy Policy</a>  </small>
                      </p>
                    </div>
                    <div id="custommessage"></div>
                    <button type="submit"  class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</button>
                    <div class="d-flex align-items-center justify-content-center">
                      <p class="fs-4 mb-0 fw-medium">New to <?= $c->get_settings("company_name") ?>?</p>
                      <a class="text-primary fw-medium ms-2" href="register">Sign Up</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!--  Import Js Files -->
    <script src="dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/app.init.js"></script>
    <script src="dist/js/app-style-switcher.js"></script>
    <script src="dist/js/sidebarmenu.js"></script>
    
    <script src="dist/js/custom.js"></script>
    <script src="dist/js/my.js?n=244"></script>
  </body>

<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 14 Aug 2023 16:11:04 GMT -->
</html>