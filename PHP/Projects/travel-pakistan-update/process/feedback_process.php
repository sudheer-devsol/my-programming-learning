<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../config/database.php";

date_default_timezone_set("Asia/Karachi");

// =================== 1. SUBMIT FEEDBACK =======================
if (isset($_POST["submit_feedback"])) {

    $user_name  = trim($_POST["user_name"] ?? "");
    $user_email = trim($_POST["user_email"] ?? "");
    $feedback   = trim($_POST["feedback"] ?? "");
    $created_at = date("Y-m-d H:i:s");

    // Find logged in user from session
    $logged_user = array();
    if (isset($_SESSION["user"])) {
        $logged_user = $_SESSION["user"];
    } else if (isset($_SESSION["admin"])) {
        $logged_user = $_SESSION["admin"];
    } else {
        $logged_user = $_SESSION;
    }

    // Find user_id
    $user_id = null;
    if (isset($logged_user["user_id"])) {
        $user_id = $logged_user["user_id"];
    } else if (isset($logged_user["admin_id"])) {
        $user_id = $logged_user["admin_id"];
    } else if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
    }

    // Find role_id
    $role_id = null;
    if (isset($logged_user["role_id"])) {
        $role_id = $logged_user["role_id"];
    } else if (isset($_SESSION["role_id"])) {
        $role_id = $_SESSION["role_id"];
    }

    // Rule: Admin (role_id = 1) cannot submit feedback
    if ($role_id == 1) {
        echo "Admins are not permitted to submit feedback.";
        exit;
    }

    if (empty($user_id)) {
        $user_id = null;
    }

    // Basic empty check
    if (empty($user_name) || empty($user_email) || empty($feedback)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Insert into user_feedback using a prepared statement
    $query = "INSERT INTO user_feedback (user_id, user_name, user_email, feedback, created_at) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $user_id, $user_name, $user_email, $feedback, $created_at);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Unable to submit feedback. Please try again.";
    }

    exit;
}

// =================== 2. DELETE FEEDBACK =======================
if (isset($_POST["action"]) && $_POST["action"] == "delete_feedback") {

    $feedback_id = $_POST["feedback_id"] ?? 0;

    if (empty($feedback_id)) {
        echo "Invalid feedback ID.";
        exit;
    }

    $query = "DELETE FROM user_feedback WHERE feedback_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $feedback_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Unable to delete feedback.";
    }

    exit;
}

// =================== INVALID REQUEST ==========================
echo "Invalid Request.";
?>
