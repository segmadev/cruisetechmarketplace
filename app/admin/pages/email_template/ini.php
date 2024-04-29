<?php
    if (isset($_GET['id'])) {
        $template = $d->getall("email_template", "ID = ?", [htmlspecialchars($_GET['id'])]);
    }
    require_once "consts/email_template.php";
    require_once "functions/email_template.php";
    $e = new email_template;
