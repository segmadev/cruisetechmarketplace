<?php
    require_once "consts/main.php";
    require_once "include/database.php";
    require_once "functions/bot.php";
    $b = new bot;
    $b->load_blog();