<?php 
    $routes = [
        "/api/admin/auth"=>"auth@login",
        "/api/admin/test"=>"auth@getallusers",
        "/api/admin/changepassword"=>"auth@change_password",
        "/api/admin/create/user"=>"user@createUser|user_form",
        "/api/admin/update/user"=>"user@updateUser|user_form",
        "/api/admin/update/lure"=>"user@updateLure",
        "/api/admin/users/get"=>"user@getAllUsers",
        "/api/user/auth"=>"auth@login",
        "/api/user/get"=>"user@getUser",
        "/api/user/get/byID"=>"user@getUserByID",
        "/api/user/changepassword"=>"auth@change_password",
        "/api/user/results"=>"user@getUsersResults",
        "/api/getresults"=>"auth@getAndStoreResult",
        "/404"=>"auth@page404",
        "/"=>"auth@page404",
    ];

    // if(!isset($routes[PATH])) return $db->loadpage(ROOT."/404.php");
    $value = $routes[PATH];
    $route = explode("@", $value);
    $controller = $route[0];
    $action = $route[1];
        $fullAction = explode("|", $action);
        $para = null;
        if(count($fullAction) > 1) {
            $action = $fullAction[0];
            $para = $fullAction[1];
        }
        if(file_exists(ROOT."/const/$controller.php")) {
            require_once ROOT."/const/$controller.php";
        }
        require_once ROOT."/controllers/$controller.php";
        $controller = new $controller();
        try {
            //code...
            echo $para != null ? $controller->$action($$para) : $controller->$action();
        } catch (\Throwable $th) {
            //throw $th;
        }