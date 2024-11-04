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
$action = $_GET['action'] ?? "list";
if($r->validate_action([$page=>$action]) || $page == ""){
    require_once "../pages/page-ini.php";
}else{
    // var_dump(["$page"=>"$action"]);
    echo $c->empty_page("You do not have access to this page", '<div><a class="btn btn-sm btn-primary sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i> show menu
                            </a></div>');
}
// exit();