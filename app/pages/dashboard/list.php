<?php 
    $categories = $d->getall("category", fetch: "all");
    if($categories->rowCount() > 0) {
        require_once "pages/dashboard/category_list.php";
        foreach($categories as $category){
            require "pages/dashboard/category.php"; 
        }
    }
    $category = ["ID"=>"", "name"=>"Others"];
    require "pages/dashboard/category.php"; 
?>
