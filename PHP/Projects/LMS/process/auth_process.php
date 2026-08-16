<?php
require_once "../includes/functions.php";

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// ------------------------------------------------------------
// LOGIN
// ------------------------------------------------------------
if ($action == "login") {

    $email = clean_input($conn, $_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $user["password"])) {

            if ($user["status"] == "inactive") {
                echo "inactive";
                exit;
            }

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["last_name"] = $user["last_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["profile_image"] = $user["profile_image"];

            echo "success_" . $user["role"];
            exit;
        }
    }

    echo "invalid";
    exit;
}

// ------------------------------------------------------------
// REGISTER  (students and teachers only, admin is not self-registered)
// ------------------------------------------------------------
if ($action == "register") {

    $first_name = clean_input($conn, $_POST["first_name"]);
    $last_name = clean_input($conn, $_POST["last_name"]);
    $email = clean_input($conn, $_POST["email"]);
    $phone = clean_input($conn, $_POST["phone"]);
    $role = clean_input($conn, $_POST["role"]);
    $password = $_POST["password"];

    if ($role != "student" && $role != "teacher") {
        $role = "student";
    }

    if (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
        echo "error";
        exit;
    }

    // Check duplicate email
    $check = "SELECT user_id FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "duplicate";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (role, first_name, last_name, email, password, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $role, $first_name, $last_name, $email, $hashed_password, $phone);

    if (mysqli_stmt_execute($stmt)) {
        $new_user_id = mysqli_insert_id($conn);

        // Notify all admins about the new registration
        $admin_query = "SELECT user_id FROM user WHERE role = 'admin'";
        $admin_result = mysqli_query($conn, $admin_query);
        while ($admin_row = mysqli_fetch_assoc($admin_result)) {
            add_notification($conn, $admin_row["user_id"], "New " . $role . " registered: " . $first_name . " " . $last_name);
        }

        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// RESET PASSWORD  (verified via email + phone, no mail server needed)
// ------------------------------------------------------------
if ($action == "reset_password") {

    $email = clean_input($conn, $_POST["email"]);
    $phone = clean_input($conn, $_POST["phone"]);
    $password = $_POST["password"];

    $query = "SELECT user_id FROM user WHERE email = ? AND phone = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $update = "UPDATE user SET password = ? WHERE user_id = ?";
        $stmt2 = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt2, "si", $hashed_password, $user["user_id"]);
        mysqli_stmt_execute($stmt2);

        echo "success";
    } else {
        echo "notfound";
    }
    exit;
}

echo "error";
?>
