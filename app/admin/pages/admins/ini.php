<?php 
    $adminDetails = [];
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $adminDetails = $d->getall("admins", "ID = ?",[$id]);
    }