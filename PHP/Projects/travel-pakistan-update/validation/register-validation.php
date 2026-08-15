<?php

session_start();

include "config/database.php";
//====================Register Validation================================

if(isset($_POST["register"]))
{
    // echo "<pre>";
    // print_r($_POST);
    // echo "<pre>";

    // =====================  Validation Status===============================
    $is_valid = true;

    // ===================Get Form Values=================================
    $first_name    = trim(htmlspecialchars($_POST["first_name"] ?? ""));
    $last_name     = trim(htmlspecialchars($_POST["last_name"] ?? ""));
    $email         = trim(htmlspecialchars($_POST["email"] ?? ""));
    $password      = trim($_POST["password"] ?? "");
    $gender        = trim(htmlspecialchars($_POST["gender"] ?? ""));
    $date_of_birth = trim($_POST["date_of_birth"] ?? "");
    $address       = trim(htmlspecialchars($_POST["address"] ?? ""));


    // ==================Regular Expressions==================================

    $nameRegex     = "/^[A-Za-z]{2,30}$/";
    $emailRegex    = "/^[A-Za-z0-9._-]+@[A-Za-z]+\.(com|net|org)$/i";
    $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$-]).{8,}$/";


    // =====================Error Messages===============================

    $first_name_msg    = "";
    $last_name_msg     = "";
    $email_msg         = "";
    $password_msg      = "";
    $gender_msg        = "";
    $date_of_birth_msg = "";
    $address_msg       = "";
    $user_image_msg    = "";
    $terms_msg         = "";

    //====================Values Validation================================
    
    if(empty($first_name)){
        $is_valid = false;
        $first_name_msg = "First Name is Required.";
    }
    else if(!preg_match($nameRegex,$first_name)){
        $is_valid = false;
        $first_name_msg = "Example: Ali";
    }

    if(empty($last_name)){
        $is_valid = false;
        $last_name_msg = "Last Name is Required.";
    }
    else if(!preg_match($nameRegex,$last_name)){
        $is_valid = false;
        $last_name_msg = "Example: Mangi";
    }


    if(empty($email)){
        $is_valid = false;
        $email_msg = "Email is Required.";
    }
    else if(!preg_match($emailRegex,$email)){
        $is_valid = false;
        $email_msg = "Example: abc@example.com";
    }

    if(empty($password)){
        $is_valid = false;
        $password_msg = "Password is Required.";
    }
    else if(!preg_match($passwordRegex,$password)){
        $is_valid = false;
        $password_msg = "Minimum 8 characters with letters and numbers.";
    }

    if(empty($gender)){
        $is_valid = false;
        $gender_msg = "Please Select Gender.";
    }

    if(empty($date_of_birth)){
        $is_valid = false;
        $date_of_birth_msg = "Date of Birth is Required.";
    }

    if(empty($address)){
        $is_valid = false;
        $address_msg = "Address is Required.";
    }

    if(empty($_FILES["user_image"]["name"])){
        $is_valid = false;
        $user_image_msg = "Please Select Profile Image.";
    }

    if(!isset($_POST["terms"])){
        $is_valid = false;
        $terms_msg = "Please Accept Terms & Conditions.";
    }

    if($is_valid){

    // ====================Check Email Already Exists================================
            
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            $email_msg = "Email Already Exists.";
        }
        else{    
        // ====================== Upload Profile Image==============================
    
            $folder = "assets/images/users";
            if(!is_dir($folder)){
                mkdir($folder,0777,true);
            }

            $file_name = time()."_".$_FILES["user_image"]["name"];
            $tmp_name = $_FILES["user_image"]["tmp_name"];
            $path = $folder."/".$file_name;

            move_uploaded_file($tmp_name,$path);

            $role_id = 2;

            $is_approved = "pending";
            $is_active = "inactive";

            // =====================Insert User===============================
        
            $query = "INSERT INTO user(role_id, first_name, last_name, email, password, gender, date_of_birth, user_image, address, is_approved, is_active)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn,$query);
            mysqli_stmt_bind_param($stmt, "issssssssss", $role_id, $first_name, $last_name, $email, $password, $gender, $date_of_birth, $file_name, $address, $is_approved, $is_active);

           if(mysqli_stmt_execute($stmt)){

                $_SESSION["register_pdf"] = array(

                    "first_name"     => $first_name,
                    "last_name"      => $last_name,
                    "email"          => $email,
                    "password"       => $password,
                    "gender"         => $gender,
                    "date_of_birth"  => $date_of_birth,
                    "address"        => $address,
                    "user_image"     => $path,
                    "is_approved"    => "Pending",
                    "is_active"      => "Inactive"

                );

                header("Location: Generate_pdf/confirm_register.php");
                exit;
            }
            else{
                echo "<script> alert('Registration Failed.'); </script>";
            }
        }
    }
}
?>