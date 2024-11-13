<?php 
    if(isset($_POST['search_user'])) {
         $searchKey = $_POST['s'];
       $users = $d->getall("users",  "ID  = ? or first_name LIKE CONCAT( '%',?,'%') or last_name LIKE CONCAT( '%',?,'%') or email LIKE CONCAT( '%',?,'%') or phone_number = ?", [$searchKey, $searchKey, $searchKey, $searchKey, $searchKey], fetch: "all");
        require_once "pages/users/table.php";
    }
    if(isset($_POST['search_transaction'])) {
         $searchKey = $_POST['s'];
       $transactions  = $d->getall("transactions",  "ID  = ? or forID = ?", [$searchKey, $searchKey], fetch: "all");
       require_once "pages/users/trans_table.php";
    }
?>