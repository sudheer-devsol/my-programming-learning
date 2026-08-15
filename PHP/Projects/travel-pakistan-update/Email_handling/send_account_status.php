<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/PHPMailer/src/Exception.php";
require __DIR__ . "/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/PHPMailer/src/SMTP.php";



// ===============Create Mail Object===========================


$mail = new PHPMailer(true);

try{


    // ================= SMTP Settings=========================
   

    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;

    $mail->Username = "Enter your mail";

    $mail->Password = "Enter App Password";

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;
    
    
    //==================Sender & Receiver========================

    $mail->setFrom("Enter your mail","Travel Pakistan");

    $mail->addAddress($user["email"], $user["first_name"] . " " . $user["last_name"]);


    //==========Email Subject==============================
    

    $mail->isHTML(true);

    $mail->Subject = "Travel Pakistan - Account Status Updated";


  
    //==================Status Message========================

    $message = "";

    if(strtolower($user["is_approved"]) == "approved" && strtolower($user["is_active"]) == "active"){
        
        $message = "<p style='color:green;'>
        Congratulations! Your account has been approved and activated.
        You can now login to Travel Pakistan.
        </p>";
    }
    else if(strtolower($user["is_approved"]) == "rejected"){

        $message = "<p style='color:red;'>
        Unfortunately, your registration request has been rejected by the administrator.
        </p>";
    }
    else if(strtolower($user["is_active"]) == "inactive"){

        $message = "<p style='color:orange;'>
        Your account has been deactivated by the administrator.
        You cannot login until it is activated again.
        </p>";
    }
    else if(strtolower($user["is_active"]) == "active"){

        $message = " <p style='color:green;'>
        Your account has been activated successfully.
        You can now login.
        </p>";
    }


    //============Email Body==============================

    $mail->Body = " <h2>Travel Pakistan</h2>

        <p>Hello <b>".$user["first_name"]." ".$user["last_name"]."</b>,</p>

        <p>Your account status has been updated by the administrator.</p>

        <table border='1' cellpadding='8' cellspacing='0'>

            <tr>
                <th>Approval Status</th>
                <td>".$user["is_approved"]."</td>
            </tr>

            <tr>
                <th>Activation Status</th>
                <td>".$user["is_active"]."</td>
            </tr>

        </table>

        <br>

            ".$message."

        <br>

        <p>Thank you for using Travel Pakistan.</p>

        <b>Travel Pakistan Team</b>";


    //===============Send Email=========================
    

    $mail->send();

}
catch (Exception $e){
    
    echo $mail->ErrorInfo;
    exit;
}