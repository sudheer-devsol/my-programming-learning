<?php

// ===================User Session===================
include "../includes/user-session.php";

// ===================Database Connection===================
include "../config/database.php";

// ================Update profile ==========================


if(isset($_POST['update_profile'])){

    $user_id = $_SESSION['user_id'];

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $gender = trim($_POST['gender']);
    $date_of_birth = trim($_POST['date_of_birth']);
    $address = trim($_POST['address']);

    // Current Image
    $old_image = "";
    $image_query = "SELECT user_image  FROM user  WHERE user_id = ?";

    $image_stmt = mysqli_prepare($conn, $image_query);

    mysqli_stmt_bind_param($image_stmt, "i", $user_id);

    mysqli_stmt_execute($image_stmt);

    $image_result = mysqli_stmt_get_result($image_stmt);

    if(mysqli_num_rows($image_result)>0){

        $image_data = mysqli_fetch_assoc($image_result);
        $old_image = $image_data['user_image'];

    }

    // =========Image upload==================================
  
    $image_name = $old_image;

    if(isset($_FILES['user_image']) && $_FILES['user_image']['error']==0){

        $allowed = ["jpg", "jpeg", "png", "webp"];

        $file_ext = strtolower(pathinfo($_FILES['user_image']['name'],PATHINFO_EXTENSION));

        if(!in_array($file_ext,$allowed)){

            header("Location: ../user/profile.php?mesg=Invalid image format.");
            exit;
        }

        $upload_folder = "../assets/images/users/";

        if(!is_dir($upload_folder)){

            mkdir($upload_folder, 0777, true);

        }

        $image_name = time(). "_".basename($_FILES['user_image']['name']);

        $image_path = $upload_folder.$image_name;

        if(!move_uploaded_file($_FILES['user_image']['tmp_name'], $image_path)){

            header("Location: ../user/profile.php?mesg=Image upload failed.");
            exit;
        }

        // ============Delete old image=================
        if(!empty($old_image) && file_exists($upload_folder.$old_image)){

            unlink($upload_folder.$old_image);
        }
    }

    
    $update_query = " UPDATE user SET first_name = ?, last_name = ?, gender = ?, date_of_birth = ?,
    user_image = ?, address = ?, updated_at = NOW() WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $update_query);

    mysqli_stmt_bind_param($stmt, "ssssssi", $first_name, $last_name, $gender, $date_of_birth, $image_name, $address, $user_id);


    if(mysqli_stmt_execute($stmt)){

        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name']  = $last_name;
        $_SESSION['gender']     = $gender;
        $_SESSION['date_of_birth'] = $date_of_birth;
        $_SESSION['address']    = $address;
        $_SESSION['user_image'] = $image_name;

        header("Location: ../user/profile.php?mesg=Profile updated successfully.");
    }
    else{

        header("Location: ../user/profile.php?mesg=Unable to update profile.");

    }
    exit;
}
?>