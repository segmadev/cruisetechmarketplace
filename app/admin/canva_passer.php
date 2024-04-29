<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../consts/main.php";
if (!defined('Regex')) {
    define("Regex", "");
}
require_once "include/database.php";
$d = new database;
$screenshot_settings = [
    "ID" => ["input_type" => "number"],
    "width" => [],
    "height" => [],
    "imageUrl" => ["unique"],
    "shapes" => [],
];
$d->create_table("screenshot_settings", $screenshot_settings);

if (isset($_POST['imageUrl'])) {
    unset($screenshot_settings['ID']);
    $data = $d->validate_form($screenshot_settings);
    if (!is_array($data)) {
        return;
    }
    $check = $d->getall("screenshot_settings", "imageUrl = ?", [$data['imageUrl']]);
    if (is_array($check)) {
        // update
        $d->update("screenshot_settings", $data, "ID = '" . $check['ID'] . "'", "Updated");
    } else {
        // insert
        $d->quick_insert("screenshot_settings", $data, "Inserted");
    }
}


if (isset($_POST["getEdit"])) {
    $id = htmlspecialchars($_POST["getEdit"]);
    $data = $d->getall("screenshot_settings", "ID = ?", [$id]);
    if (is_array($data)) {
        $data['imageUrl'] = htmlspecialchars_decode($data['imageUrl']);
        $data['shapes'] = getDataValue(htmlspecialchars_decode($data['shapes']));
        echo  json_encode($data);
    }
}

if (isset($_GET['get'])) {
    getDataValue();
}

function loadShapeData($shape, $shapes)
{
    switch ($shape->inputType) {
        case 'name':
            if (isset($shape->copy) && $shape->copy != "") {
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $Tuser = generate_name();
            $shape->content = $Tuser['name'];
            setcookie("last_name", $Tuser['ID'], time() + 30 * 60);
            break;
        case 'money':
            if (isset($shape->copy) && $shape->copy != "") {
                $shape->content = str_replace("+", "", $shapes[$shape->copy]->content);
                break;
            }
            $shape->content = generate_money($shape);
            break;
        case 'number':
            if (isset($shape->copy) && $shape->copy != "") {
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $shape->content = generate_number($shape);
            break;
        case 'regex':
            if (isset($shape->copy) && $shape->copy != "") {
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $shape->content = generate_regex($shape);
            break;
        case 'static':
            if (isset($shape->copy) && $shape->copy != "") {
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $shape->content = generate_static($shape);
            break;
        case 'date':
            if (isset($shape->copy) && $shape->copy != "") {
                if ($shape->datepattern != $shapes[$shape->copy]->datepattern) {
                    date($shape->datepattern, strtotime($shapes[$shape->copy]->content));
                    break;
                }
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $randomTimestamp = generate_date($shape);
            $shape->content = date($shape->datepattern, $randomTimestamp);
            setcookie("last_date", $randomTimestamp, time() + 30 * 60);
            break;
        case 'bitcoin':
            if (isset($shape->copy) && $shape->copy != "") {
                if ($shape->inputType != $shapes[$shape->copy]->inputType) {
                    $amount = $shapes[$shape->copy]->content;
                    $Namount = substr($amount, 1);
                    $Namount = str_replace(",", "", $Namount);
                    $shape->content = generate_btc($Namount);
                    break;
                }
                $shape->content = $shapes[$shape->copy]->content;
                break;
            }
            $shape->content = generate_btc(rand(50, 100000));
            if(!$shape->content){ return loadShapeData($shape, $shapes); }
            break;

        default:
            # code...
            break;
    }
    return $shape;
}
function getDataValue($shapes = 2)
{
    $d = new database;
    if ($shapes == 2) {
        $data = $d->getall("screenshot_settings", "ID = ?", [$shapes]);
        $shapes = htmlspecialchars_decode($data['shapes']);
    }
    $shapes = json_decode($shapes);
    $waiting = [];
    // var_dump($shapes);
    //     array_map(function($x)
    // {
    //    return $x['type'];
    // }, $shapes);

    foreach ($shapes as $key => $shape) {
        if (isset($shape->copy)) {
            array_push($waiting, $key);
            continue;
        }
        $shape = loadShapeData($shape, $shapes);
    }

    if (count($waiting) > 0) {
        foreach ($waiting as $key) {
            $shapes[$key] = loadShapeData($shapes[$key], $shapes);
        }
    }
    // setcookie("last_amount", $amount, time() + 30 * 60);
    return json_encode($shapes);
}

function handle($shape)
{
}
// name
function generate_name($type = "name")
{
    $d = new database;
    // getradom name from database and retrun the name
    $user = $d->generate_withdrawal_user($_COOKIE['last_date'] ?? time());
    return ["name" => $user['first_name'] . ' ' . $user['last_name'], "ID" => $user['ID']];
}
// BTC
function generate_btc($amount)
{
    return convertBTC($amount);
}

function generate_date($data)
{
    $d = new database;
    $start = $d->get_settings("last_screenshot_date");
    // $end = date("Y-m-d H:i:s");
    $end = strtotime(date('Y-m-d 23:59:59', $start));
    if ($start > $end || $start > strtotime(date("Y-m-d"))) {
        exit();
    }
    $date = mt_rand($start, $end);
    // setcookie("last_date", $date, time() + 30 * 60);
    return $date;
    // return $randomDate;
}
// number
function generate_number($data)
{
    return rand($data->nomin, $data->nomax);
}
// money
function generate_money($data)
{
    $d = new database;
    $amount = generateRandomDecimal($data->amountmin, $data->amountmax);
    // set the cookies for the last amount.
    if ($amount >= 30000) {
        setcookie("last_amount", $amount, time() + 30 * 60);
    }
    return $d->money_format($amount, $data->currency);
}
// regex
function generate_regex($data)  
{
    $d = new database;
    $data = $d->api_call("https://regex.coinpulse.tech/?regex=" . $data->regexpattern);
    if (!$data->message) {
        exit();
        // return ;
    }
    return $data->message;
}

function generateRandomDecimal($min, $max, $precision = 2)
{
    $randomNumber = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    return round($randomNumber, $precision);
}

function generateRandomText($pattern, $length = 10)
{
    $randomText = '';
    $patternLength = strlen($pattern);

    for ($i = 0; $i < $length; $i++) {
        $randomChar = $pattern[rand(0, $patternLength - 1)];
        $randomText .= $randomChar;
    }

    return $randomText;
}

// static
function generate_static($data)
{
    return $data->statictext;
}
// convert usd to btc






function convertBTC($usdAmount)
{
    $d = new database;
    // var_dump($usdAmount);
    $usdAmount = preg_replace("/[^0-9\.]/", "", $usdAmount);
    // var_dump($usdAmount);
    // $d = new database;
    $coinId = 'BTCUSDT';
    // (int)$row["trade_time"] * 1000
    $date = $_COOKIE['last_date'] ?? time();
    $startTimestamp = $date * 1000;
    $api_ROOT = "https://api.binance.us";
    if(ROOT == "../") {
        $api_ROOT = "https://api.binance.com";
    }
    // The amount in USD you want to convert
    $data = $d->api_call("https://api.binance.us/api/v3/klines?symbol=$coinId&interval=1m&limit=1&startTime=$startTimestamp");
    
    // echo "API";
    // setcookie("btc_price", serialize($data), time() + 30 * 60);  
    // $data = json_decode($data, true);
    // Get the BTC price in USD
    if(!isset($data[0])) {
        return false;
    }
    $price = $data[0][4];
    // Perform the conversion
    $amount = $usdAmount / $price;
    $btcAmount = round($usdAmount / $price, 2);
    if ($amount < 1) {
        $btcAmount = round($usdAmount / $price, 8);
    }
    // Output the result
    return "$btcAmount BTC";
}

if (isset($_POST['image'])) 
{
    // save image
    // insert image into chat.
    $rand_no = rand(7, 15);
    $last_s_date = $d->get_settings("last_screenshot_date");
    $start_of_day = strtotime(date('Y-m-d', $last_s_date));
    $end_of_day = strtotime(date('Y-m-d 23:59:59', $last_s_date));
    if ($d->getall("message", "is_group = ? and time_sent >= ? and time_sent <= ? and upload is not null", ["yes", $start_of_day, $end_of_day], fetch: "") >= $rand_no) {
        $d->update("settings", ["meta_value" => strtotime(date('Y-m-d', $last_s_date + 86400))], "meta_name = 'last_screenshot_date'");
        return "";
    } else {
        $userID = $_COOKIE['last_name'] ?? generate_name()['ID'];
        $amount = $_COOKIE['last_amount'] ?? 0;
        $filename = saveImage();
        if(!generate_chat_sreenshot($filename, $_COOKIE['last_date'] ?? time(), $userID)){
            exit();
        }
        $date = date("Y-m-d H:i:s", strtotime('-' . rand(5, 10) . ' minutes', $_COOKIE['last_date']));
        if ($userID && $amount > 0) {
            $withdraw = [
                "ID" => uniqid(),
                "userID" => $userID,
                "amount" => $amount,
                "wallet" => "64fe21832f8b4",
                "status" => "bot",
                "date" => $date
            ];
            $d->quick_insert("withdraw", $withdraw);
        }
    }
}
function saveImage()
{
    // Get the incoming image data
    $image = $_POST["image"];

    // Remove image/jpeg from left side of image data
    // and get the remaining part
    $image = explode(";", $image)[1];

    // Remove base64 from left side of image data
    // and get the remaining part
    $image = explode(",", $image)[1];

    // Replace all spaces with plus sign (helpful for larger images)
    $image = str_replace(" ", "+", $image);

    // Convert back from base64
    $image = base64_decode($image);

    // $image = compress($_POST["image"]);
    $filename = uniqid("S-");
    $path = "../assets/images/chat/";
    // Save the image as filename.jpeg
    file_put_contents($path . "$filename.png", $image);
    return "$filename.png";
}

function compress($img, $percent = 0.5)
{
    if ($img) {


        // Content type
        header('Content-Type: image/jpeg');
        $data = base64_decode($img);
        $im = imagecreatefromstring($data);
        $width = imagesx($im);
        $height = imagesy($im);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        $thumb = imagecreatetruecolor($newwidth, $newheight);

        // Resize
        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        return imagejpeg($thumb);
    }
}


// date
// file / upload 
// message
// senderID
// receiverID
// is_group = yes

function generate_chat_sreenshot($filename, $date, $senderID, $receiverID = 2, $is_group = "yes")
{
    $d =  new database;
    $data = [
        "senderID" => $senderID,
        "receiverID" => $receiverID,
        "message" => getword(),
        "upload" => $filename,
        "is_group" => $is_group,
        "time_sent" => strtotime('+' . rand(7, 30) . ' minutes', $date)
    ];
    if($data['upload']== "" || $data['upload'] == null) {
        return false;
    }
    $d->quick_insert("message", $data, "success upload");
    // save time to strack.txt 
    // $file = fopen('strack.txt', 'a');
    // fwrite($file, $data['time_sent'] . ", ");
    // fclose($file);
    return true;
}


function getword()
{
    $d = new database;
    $messages = $d->get_settings("screenshot_messages");
    $messages_backup = $d->get_settings("screenshot_messages_backup");
    if ($messages == "" && $d->getall("settings", "meta_name  = ?", ["screenshot_messages"], fetch: "") == 0) {
        $messages = ["meta_name" => "screenshot_messages", "meta_value" => "Made my first withdraw", "meta_for" => "all"];
        $d->quick_insert("settings", $messages);
        $messages = $messages['meta_value'];
    }
    // var_dump($messages);
    $sentences = array_filter(array_map('trim', explode("\n", $messages)));
    $selectedSentence = getwordfromstring($messages);
    $remainingSentences = implode("\n", array_diff($sentences, [$selectedSentence])); 
    $d->update("settings", ["meta_value" => $remainingSentences], "meta_name = 'screenshot_messages'");
    if ($selectedSentence == "") {
        $selectedSentence = getwordfromstring($messages_backup);
    } else {
        // insert the selected into backup message.
        $d->update("settings", ["meta_value" => $messages_backup . "\n". $selectedSentence], "meta_name = 'screenshot_messages_backup'");
    }
    return $selectedSentence;
}

function getwordfromstring(string $messages)
{
    $sentences = array_filter(array_map('trim', explode("\n", $messages)));
    $randomIndex = rand(0, count($sentences) - 1);
    $selectedSentence = $sentences[$randomIndex];
    return $selectedSentence;
}
