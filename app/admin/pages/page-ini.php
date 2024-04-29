<?php
require_once "../consts/Regex.php";
$action = "list";
$accepted_actions = ["new","generate", "access", "home", "prove", 'list', "view", "edit", "overview", "rejected", "transfer", "transactions"];
if (isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
}
if (!isset($pageexclude)) {
    $pageexclude = "no";
}

if (file_exists("pages/" . $page . "/ini.php")) {
    require_once "pages/" . $page . "/ini.php";
}

if (file_exists("consts/$page" . ".php")) {
    require_once "consts/$page" . ".php";
}
if (file_exists("functions/" . $page . ".php") && !class_exists($page)) {
    require_once "functions/$page.php";
}

if (class_exists($page)) {
    if (!isset(${substr($page, 0, 1)})) {
        ${substr($page, 0, 1)} = new $page;
    } elseif (get_class(${substr($page, 0, 1)}) != $page) {
        ${substr($page, 0, 2)} = new $page;
    }
}



if (in_array($action, $accepted_actions) && file_exists("pages/" . $page . "/" . $action . ".php") && $pageexclude != "yes") {
    require_once "pages/" . $page . "/" . $action . ".php";
}