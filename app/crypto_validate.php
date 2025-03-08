<?php 
  header("Content-Type: application/json; charset=UTF-8");
$iniPath = str_replace("/cruise/", "", $_SERVER['REQUEST_URI']);
    // Parse the URL to extract only the path
    $parsedUrl = parse_url($iniPath);
    $cleanPath = rtrim($parsedUrl['path'], '/'); // Remove trailing slash if it exists
    // Define PATH without GET parameters
    $thePath = str_replace("app/api", "", $cleanPath);
    define("PATH", str_replace("//", "/", $thePath));
    define("side", "user");
    require_once "consts/main.php";
    require_once 'vendor/autoload.php';
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(rootFile);
    $dotenv->load();
    require_once "admin/include/database.php";
    require_once "content/content.php";
    require_once "functions/users.php";
    require_once "functions/deposit.php";
    $de = new deposit;
    $de->processWebhookTransaction();