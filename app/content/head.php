<link rel="stylesheet" href="dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">

<!-- Core Css -->

<?php
if (isset($_SESSION['browser_theme'])) {
    switch ($_SESSION['browser_theme']) {
        case 'dark':
            $logo = $light_logo;
            echo '<link  id="themeColors"  rel="stylesheet" href="dist/css/style-dark.min.css?n=5543" />';
            // echo '<link id="themeColors" rel="stylesheet" href="dist/css/style-orange.min.css?n=89" />';
            $theme_btn = '<a href="index?theme=light" class=" btn btn-light text-dark rounded-pill font-medium me-2 mb-2" style="background-color: white"> <i class="ti ti-sun"></i> Change to Light Theme</a>';
            # code...
            break;

        default:
            $logo = $dark_logo;
            // echo  '<link id="themeColors" rel="stylesheet" href="dist/css/style.min.css?n=409" />';
            echo  '<link id="themeColors" rel="stylesheet" href="dist/css/style-orange.min.css" />';
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

</style>



<!-- <script type="text/javascript">var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();(function(){var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;s1.src='https://embed.tawk.to/6585690607843602b804b9e1/1hi8i4gpn';s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);})();</script> -->
