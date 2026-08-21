<?php

    $server = "localhost";
    $username = "root";
    $password = "";
    $db_name = "student_db";

    $connect =mysqli_connect($server, $username, $password, $db_name);

    if(!$connect){
        echo "Failed TO connect db";
    }else{
        // echo "Success";
    }

?>