<?php
    if(isset($_GET['id'])) {
        $id = htmlspecialchars($_GET['id']);
        $data = $d->getall("wallets", "ID = ?", [$id], fetch: "details");
    }