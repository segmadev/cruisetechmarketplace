<?php

if(!file_exists("../consts/Regex.php")) {
    echo "../consts/Regex not found";
    exit();
}
require_once "../consts/Regex.php";
if(!file_exists("../pages/page-ini.php")) {
    echo "../pages/page-ini not found";
    exit();
}

require_once "../pages/page-ini.php";