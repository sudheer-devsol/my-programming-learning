<?php

//============PHPMailer Files=======================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";
require "PHPMailer/src/SMTP.php";

//===============Create PHPMailer Object===========================

$mail = new PHPMailer(true);

$mail_sent = false;

try{

    // ================== SMTP Settings========================
   

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "Enter your mail";
    $mail->Password = "App pass here";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //===============Sender email===========================
    $mail->setFrom("Enter your mail", "Travel Pakistan");


    //===========Receiver mail==============================    
    $mail->addAddress($user_email, $user_name);

    //==============Email Content============================
    

    $mail->isHTML(true);

    $mail->Subject = "Travel Pakistan - Forgot Password";

    $mail->Body = "<h2>Travel Pakistan</h2>

    <p>Hello <b>$user_name</b>,</p>

    <p>You requested your account details.</p>

    <table border='1' cellpadding='8' cellspacing='0'>

        <tr>
            <th>User Name</th>
            <td>$user_name</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>$user_email</td>
        </tr>
        <tr>
            <th>Password</th>
            <td>$user_password</td>
        </tr>
    </table>
    <br>
    <p>You can now login to your Travel Pakistan account.</p>
    <br>

    <p>Thank You</p>
    <b>Travel Pakistan Team</b>";


    //==============Send Email============================
    
    $mail->send();
    $mail_sent = true;

}
catch(Exception $e){

    $mail_sent = false;

}

?>