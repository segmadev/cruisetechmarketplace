<link rel="stylesheet" href="dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">

<!-- Core Css -->
<link id="themeColors" rel="stylesheet" href="dist/css/custom.css?n=18" />
<?php
require_once "consts/main.php";
require_once "admin/include/database.php";
require_once "content/content.php";
$d  = new database;
$c = new content;
define("currency", $d->get_settings("default_currency"));
define("company_name", $d->get_settings("company_name"));
// require_once "content/head.php";

echo  '<link id="themeColors" rel="stylesheet" href="dist/css/style.min.css" />';
require_once "content/slide-notify.php";
require_once "content/foot.php";
