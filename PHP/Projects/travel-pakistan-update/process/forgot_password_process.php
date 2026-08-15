<?php

include "../config/database.php";

//=================Forgot Password Process=========================


if(isset($_POST["forgot_password"])){

    // var_dump($_POST);

    $email = trim(htmlspecialchars($_POST["email"] ?? ""));

    if(empty($email)){

        echo "Please enter your email.";
        exit;
    }

    $query = " SELECT first_name,last_name, email, password FROM user WHERE email = ?";

    $stmt = mysqli_prepare($conn,$query);
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    //================== Email Not Found========================
       if(mysqli_num_rows($result) == 0){

        echo "Email does not exist.";
        exit;
    }


    //=================== Fetch User Data=======================
   
    $row = mysqli_fetch_assoc($result);

    $user_name = htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]);
    $user_email = htmlspecialchars($row["email"]);
    $user_password = htmlspecialchars($row["password"]);


    //==============Send Email=========================== 
    include "../Email_handling/send_password.php";

    if($mail_sent == true){
        
        echo "success";
    }
    else{
        
        echo "Unable to send email.";
    }

}


?>