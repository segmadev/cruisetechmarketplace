<?php 
if(isset($_POST['new_rent']) && isset($_POST['id']) && $_POST['id'] != "") {
    echo $r->newNumber($userID, htmlspecialchars($_POST['id']));
}

if(isset($_POST['like_rental']) && isset($_POST['id']) && $_POST['id'] != "") {
    $r->likeService($userID, htmlspecialchars($_POST['id']));
}