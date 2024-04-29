<?php
$activities = [
    "ID" => [],
    "ip_address" => [],
    "device" => [],
    "browser" => [],
    "date_time" => [],
    "action_name" => [],
    "link" => [],
    "action_for" => [],
    "action_for_ID" => [],
];
$activities = $d->getall("activities", "userID = ? order by date DESC LIMIT 50", [$userID], fetch: "moredetails");
$act_title = 'Activity';
$act_des = 'Notifications and activity on your account';
// $d->create_table("activities", $activities);

// $browserTheme = exec('node -e "console.log(require(\'child_process\').execSync(\'node -p getBrowserTheme()\').toString())"');
// echo $browserTheme;

// $ip = $_SERVER['REMOTE_ADDR'];
// $ip = '37.120.215.172';
// $apiUrl = "http://ip-api.com/json/{$ip}";

// $locationData = json_decode(file_get_contents($apiUrl));
// // var_dump($locationData);
// if ($locationData && $locationData->status === 'success') {
//     $country = $locationData->country;
//     $region = $locationData->regionName;
//     $city = $locationData->city;

//     echo "Visitor Location: {$city}, {$region}, {$country}";
// } else {
//     echo "Unable to retrieve visitor location.";
// }


?>

