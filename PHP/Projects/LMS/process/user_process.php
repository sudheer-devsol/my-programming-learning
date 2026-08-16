<?php
require_once "../includes/functions.php";
require_role("admin");

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// ------------------------------------------------------------
// ADD USER (teacher or student, created directly by admin)
// ------------------------------------------------------------
if ($action == "add_user") {

    $role = clean_input($conn, $_POST["role"]);
    $first_name = clean_input($conn, $_POST["first_name"]);
    $last_name = clean_input($conn, $_POST["last_name"]);
    $email = clean_input($conn, $_POST["email"]);
    $phone = clean_input($conn, $_POST["phone"]);
    $password = $_POST["password"];

    if ($role != "teacher" && $role != "student") {
        echo "error";
        exit;
    }

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
        $new_id = mysqli_insert_id($conn);
        add_notification($conn, $new_id, "Welcome! Your account was created by the admin.");
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// TOGGLE STATUS (active / inactive)
// ------------------------------------------------------------
if ($action == "toggle_status") {

    $user_id = (int) $_POST["user_id"];
    $current_status = clean_input($conn, $_POST["current_status"]);
    $new_status = $current_status == "active" ? "inactive" : "active";

    $query = "UPDATE user SET status = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// DELETE USER
// ------------------------------------------------------------
if ($action == "delete_user") {

    $user_id = (int) $_POST["user_id"];

    $query = "DELETE FROM user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
