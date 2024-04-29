<?php 
    if($users->rowCount() < 1) {
        $c->empty_page("No user found.");
    }else{
        // table goes in here
        require_once "pages/users/table.php";
    }
?>