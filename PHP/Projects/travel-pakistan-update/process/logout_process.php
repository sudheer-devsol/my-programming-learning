<?php

include "../config/database.php";

session_start();


//=================Admin Logout=========================

if(isset($_POST["action"]) && $_POST["action"] == "logout"){
    
    //===============Destroy Session==========================
    session_unset();
    session_destroy();
    echo "success";
}

else{
    echo "Invalid Request.";
}

?>