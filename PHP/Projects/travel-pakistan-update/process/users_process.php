<?php
// =================Database Connection===================
include "../config/database.php";

$action = $_POST["action"] ?? "";

// =====================Add User=======================

if($action == "add_user"){

    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";
    // ===============Get Form Data===========================

    $first_name  = trim($_POST["first_name"]);
    $last_name   = trim($_POST["last_name"]);
    $email       = trim($_POST["email"]);
    $password    = trim($_POST["password"]);   
    $gender      = $_POST["gender"];
    $dob         = $_POST["dob"];
    $address     = trim($_POST["address"]);
    $role_id     = $_POST["role_id"];
    $is_approved = $_POST["is_approved"];
    $is_active   = $_POST["is_active"];

    // ===================Validation=======================

    if($first_name == "" || $last_name == "" || $email == "" || $password == ""){
        header("Location: ../admin/users.php?mesg=Please fill all required fields.");
        exit;
    }

    //==================Check Duplicate Email========================
    
    $query = "SELECT user_id FROM user WHERE email = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        header("Location: ../admin/users.php?mesg=Email already exists.");
        exit;
    }


    // ================Upload Profile Image==========================

    $image_name = "default.png";

    if(isset($_FILES["user_image"]) && $_FILES["user_image"]["error"] == 0){
        
        $allowed_extensions = array("jpg", "jpeg", "png", "webp");

        $extension = strtolower( pathinfo( $_FILES["user_image"]["name"], PATHINFO_EXTENSION ));

        if(!in_array($extension, $allowed_extensions)){
            header("Location: ../admin/users.php?mesg=Only JPG, JPEG, PNG and WEBP images are allowed.");
            exit;
        }

        // 2MB Maximum
        if($_FILES["user_image"]["size"] > (2 * 1024 * 1024)){
            header("Location: ../admin/users.php?mesg=Image size must not exceed 2MB.");
            exit;
        }

        $image_name = time() . "_" . rand(1000,9999) . "." . $extension;
        move_uploaded_file($_FILES["user_image"]["tmp_name"], "../assets/images/users/" . $image_name);
    
    }

    // ============== Insert User============================
   
    $query = "INSERT INTO user( role_id,first_name, last_name, email, password, gender, date_of_birth, address, user_image, is_approved, is_active, created_at)
    VALUES( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param( $stmt, "issssssssss", $role_id, $first_name, $last_name, $email, $password, $gender, $dob,  $address, $image_name, $is_approved, $is_active);

    if(mysqli_stmt_execute($stmt)){
        header("Location: ../admin/users.php?mesg=User added successfully.");
    }
    else{
        header("Location: ../admin/users.php?mesg=Unable to create user.");
    }
    exit;

}

//========================Get User========================

else if($action == "get_user"){

    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";
    
    $user_id = $_POST["user_id"];

    $query = "SELECT * FROM user WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param( $stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){   

        $user = mysqli_fetch_assoc($result);

        // Return simple pipe-separated plain text instead of JSON
        echo $user["user_id"] . "|" . $user["first_name"] . "|" . $user["last_name"] . "|" . $user["email"] . "|" . $user["role_id"] . "|" . $user["is_approved"] . "|" . $user["is_active"];
    }
    else
    {
        echo "User not found.";
    }

}

//=====================Update User===============================


else if($action == "update_user"){
    
    // echo "<pre>";
    // print_r($_GET);
    // echo "</pre>";
    

   // ===================Get Form Values=======================
    
    $user_id = $_POST["user_id"];
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $role_id = $_POST["role_id"];
    $is_approved = $_POST["is_approved"];
    $is_active = $_POST["is_active"];

    //==================== Validate Required Fields======================
   
    if($first_name == "" || $last_name == "" || $email == ""){
        echo "Please fill all required fields.";
        exit;
    }

    //====================Check Duplicate Email======================
    
    $query = "SELECT user_id FROM user WHERE email = ? AND user_id != ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param( $stmt, "si", $email,  $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        echo "Email already exists.";
        exit;
    }

    //==============Update User==========================
    
    $query = "UPDATE user SET role_id = ?, first_name = ?, last_name = ?, email = ?, is_approved = ?, is_active = ?, updated_at = NOW()
    WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param( $stmt, "isssssi", $role_id, $first_name, $last_name, $email, $is_approved, $is_active, $user_id);

    if(mysqli_stmt_execute($stmt)){
        echo "success";
    }
    else{
        echo "Unable to update user.";
    }
}

//======================Update User Status==============================

else if($action == "update_status"){

    $user_id = $_POST["user_id"];
    $status_action = $_POST["status_action"];
    
    //===================Check Which Status to Update=======================
    

    if($status_action == "approve"){
        $query = "UPDATE user SET is_approved = 'Approved', updated_at = NOW()
        WHERE user_id = ?";
    }
    else if($status_action == "reject"){
        $query = "UPDATE user SET is_approved = 'Rejected', updated_at = NOW()
        WHERE user_id = ?";
    }
    else if($status_action == "activate"){
        $query = "UPDATE user  SET  is_active = 'Active', updated_at = NOW()
        WHERE user_id = ?";
    }
    else if($status_action == "deactivate"){
        $query = "UPDATE user SET is_active = 'InActive', updated_at = NOW()
        WHERE user_id = ?";
    }
    else{
        echo "Invalid status action.";
        exit;
    }

    //============== Execute Query==========================
   
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){

    //===========Get Updated User Details=============================
        

        $query = " SELECT first_name, last_name, email, is_approved, is_active FROM user
        WHERE user_id = ?";

        $stmt2 = mysqli_prepare($conn,$query);
        mysqli_stmt_bind_param($stmt2, "i", $user_id);
        mysqli_stmt_execute($stmt2);
        $result = mysqli_stmt_get_result($stmt2);

        $user = mysqli_fetch_assoc($result);

        //===============Send Status Email=========================
        
        include "../Email_handling/send_account_status.php";
        echo "success";
    }
    else{
        echo "Unable to update user status.";
    }

}

//=======================Delete User=============================

else if($action == "delete_user"){
  
    $user_id = $_POST["user_id"];

    //===============Get User Image===========================
    
    $query = "SELECT user_image FROM user WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param( $stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 0){

        echo "User not found.";
        exit;
    }

    $user = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    //==============  Delete User==========================
  
    $query = "DELETE FROM user WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param(  $stmt, "i", $user_id);

    if(mysqli_stmt_execute($stmt)){
        
    //===============Delete Profile Image===========================
      
    if( $user["user_image"] != "default.png" && file_exists("../assets/images/users/" . $user["user_image"]) ){
        unlink("../assets/images/users/" . $user["user_image"]);
    }
        echo "success";
    }else{
        echo "Unable to delete user.";
    }
}
else{
    echo "Invalid request.";
}

?>