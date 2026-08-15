<?php
// =======================Database Connection=============================
include "../config/database.php";

session_start();

// ====================Login Process================================
if(isset($_POST["login"])){

    
    $email = trim(htmlspecialchars($_POST["email"] ?? ""));
    $password = trim($_POST["password"] ?? "");

    // ===============Check Email=====================================

    $query = "SELECT * FROM user WHERE email = ?";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 0){
        echo "Invalid Email or Password.";
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    // =====================Password Verification===============================
    if($password != $row["password"]){
        echo "Invalid Email or Password.";
        exit;
    }

    // =================== Check Approval Status=================================

    if($row["is_approved"] == "Pending"){
        echo "pending";
        exit;
    }

    if($row["is_approved"] == "Rejected"){
        echo "rejected";
        exit;
    }

    // ========================Check Account Status============================
    
    if($row["is_active"] == "InActive"){
        echo "inactive";
        exit;
    }


    // =========================Create Session===========================

    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["role_id"] = $row["role_id"];
    $_SESSION["first_name"] = $row["first_name"];
    $_SESSION["last_name"] = $row["last_name"];
    $_SESSION["email"] = $row["email"];
    $_SESSION["user_image"] = $row["user_image"];


  
    // ========================== Redirect According To Role==========================
  
    if($row["role_id"] == 1){
        echo "admin";
    }
    else if($row["role_id"] == 2){
        echo "success";
    }
    else{
        echo "Invalid User Role.";
    }

}

?>