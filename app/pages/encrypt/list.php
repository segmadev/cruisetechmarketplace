<?php 
    if(isset($_GET['data']) && $_GET['key'] == $_ENV['GKEY']) {
        echo "<pre>";
            print_r($d->enypt_and_save_data($_GET['data']));
        echo "</pre>";
    }else{
        echo "error";
    }