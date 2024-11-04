<?php 
error_reporting(0);
// sleep(40);
if(isset($_POST['instalink'])) {
        $username = $i->extractUsername($_POST['instalink']);
        if($username == null || $username == "") echo "Invalid URL or Username for ".$_POST['instalink'];
        $userData = $i->scrapeUser($username);
        echo "<h3> $username </h3>";
        if(!isset($userData['edge_followed_by']['count'])){
            echo "Blocked Account or not found";
    }else{
            echo "Followers ".$userData['edge_followed_by']['count'];
            echo "<br>";
            echo "Following ".$userData['edge_follow']['count'];
            echo "<br>";
            echo "private ".($userData['is_private'] ? "Yes" : "No");
            echo "<br>";
            echo "Verified ".($userData['is_verified'] ? "Yes" : "No");
            echo "<hr>";
        }
    }
?>