<?php
$thispage =  str_replace(".php", "", basename($_SERVER['PHP_SELF']));
$url = match ($route) {
    'new' => require_once "pages/$thispage/new.php",
    'edit' => require_once "pages/$thispage/edit.php",
    '', null, 'list' => require_once "pages/$thispage/list.php"
};