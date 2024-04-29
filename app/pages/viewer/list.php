<?php
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    if (file_exists($path)) {
        echo "<img src='$path' alt='' class='w-100'>";
    }
}

if (isset($_GET['url'])) {
    $path = $_GET['url'];
    echo "<iframe src='$path' alt='' class='w-100' style='height: 600px'>";
}


?>