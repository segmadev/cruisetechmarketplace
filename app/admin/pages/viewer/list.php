<?php
if (isset($_GET['path'])) {
    $path = $_GET['path'];
    if (file_exists($path)) {
        echo "<img src='$path?n=".rand(10, 100)."' alt='' style='width:100%'>";
    }
}
?>