<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    header("Content-Type: application/json; charset=UTF-8");
    // define("ROOT", $_SERVER['DOCUMENT_ROOT']);
    define("PATH", $_SERVER['REQUEST_URI']);
    // var_dump(PATH);
    require_once "../consts/main.php";
    require_once "../admin/include/database.php";
    $d = new database;
    echo $d->apiMessage("Testing message", 200, ["path"=>PATH, "root"=>ROOT]);
    exit();
    require ROOT.'/vendor/autoload.php';
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(ROOT);
    $dotenv->load();
    require_once ROOT."/server/database.php";
    $db = new database;
    require_once ROOT."/const/users.php";