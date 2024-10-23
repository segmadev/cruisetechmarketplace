<link rel="stylesheet" href="dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">

<!-- Core Css -->
<style>
    a, a["data-url"]{
        cursor: pointer;
    }
</style>
<?php
$script[] = "live_chat";
$mode = "light";
if (isset($_SESSION['browser_theme'])) {
    switch ($_SESSION['browser_theme']) {
        case 'dark2':
            $mode = "dark";
            $logo = $light_logo;
            echo '<link  id="themeColors"  rel="stylesheet" href="dist/css/style-dark.min.css?n=5543" />';
            // echo '<link id="themeColors" rel="stylesheet" href="dist/css/style-orange.min.css?n=89" />';
            $theme_btn = '<a href="index?theme=light" class=" btn btn-light text-dark rounded-pill font-medium me-2 mb-2" style="background-color: white"> <i class="ti ti-sun"></i> Change to Light Theme</a>';
            # code...
            break;
        default:
            $logo = $dark_logo;
            // echo  '<link id="themeColors" rel="stylesheet" href="dist/css/style.min.css?n=409" />';
            echo  '<link id="themeColors" rel="stylesheet" href="dist/css/style-orange.min.css?n=900" />';
            $theme_btn = '<a href="index?theme=dark" class="btn btn-dark rounded-pill font-medium me-2 mb-2" for="option1"><i class="ti ti-moon"></i> Change to Dark Theme</a>';
            break;
    }
}

?>
<link id="themeColors" rel="stylesheet" href="dist/css/custom.css?n=34343" />
<style>
    .bottom-nav {
        position: fixed;
        width: 100%;
        bottom: 20px;
        display: none !important;
        justify-content: center;
        z-index: 100;
    }

    @media only screen and (max-width: 970px) {
        .bottom-nav {
            display: flex !important;
        }
    }

    .botton-navs .btn {
        font-size: 25px;
    }

    aside {
        z-index: 110 !important;
    }

    .bg-blue {
        background-color: #0B5266;
        color: white;
    }
    <?php if($mode == "dark") { ?>
        .bg-blue {
            background-color: white;
            color: #0B5266;
        }
    <?php } ?>

    /* Center the spinner in the container */
.loading-spinner-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    min-height: 200px; /* Adjust based on the size of your modal */
}

/* Spinner Animation */
.spinner {
    border: 8px solid rgba(0, 0, 0, 0.1);
    border-top: 8px solid #fa5a15;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

/* Spinner Keyframes */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<input id="discountData" type="hidden" value="<?= base64_encode(json_encode($user_data['stage'])) ?>">

<!-- <script type="text/javascript">var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();(function(){var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;s1.src='https://embed.tawk.to/6585690607843602b804b9e1/1hi8i4gpn';s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);})();</script> -->
