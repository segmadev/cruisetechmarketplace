<?php
     const db_username = "prolcnoz_public";
     const db_password = "sJjJzBeJx2Qx";
     const db_name = "prolcnoz_blog";
     const db_host_name = "localhost";
     if (strpos($_SERVER['DOCUMENT_ROOT'], "htdocs") == false){
         define("rootFile", $_SERVER['DOCUMENT_ROOT']."/app/");
        //  echo "<h1>HERE</h1>";
        }else{
            // echo "<h1>HERE 2</h1>";
            define("rootFile", str_replace("C:/xampp2/htdocs/invest2", "C:/xampp2/htdocs/invest2/app", $_SERVER['DOCUMENT_ROOT']."/invest2/"));
     }


    //  const db_username = "root";
    //  const db_password = "";
    //  const db_name = "invest";
    //  const db_host_name = "localhost";
?>