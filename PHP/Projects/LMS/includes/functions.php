<?php
// ------------------------------------------------------------
// Core helper functions
// ------------------------------------------------------------
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/database.php";

// Clean user input
function clean_input($conn, $value) {
    $value = trim($value);
    $value = htmlspecialchars($value);
    $value = mysqli_real_escape_string($conn, $value);
    return $value;
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

// Force login
function require_login() {
    if (!is_logged_in()) {
        header("Location: /login.php");
        exit;
    }
}

// Force a specific role, redirect otherwise
function require_role($role) {
    require_login();
    if ($_SESSION["role"] != $role) {
        header("Location: /login.php");
        exit;
    }
}

// Redirect a user to their dashboard based on role
function redirect_to_dashboard($role) {
    if ($role == "admin") {
        header("Location: /admin/dashboard.php");
    } elseif ($role == "teacher") {
        header("Location: /teacher/dashboard.php");
    } elseif ($role == "student") {
        header("Location: /student/dashboard.php");
    } else {
        header("Location: /login.php");
    }
    exit;
}

// Insert a notification for a user
function add_notification($conn, $user_id, $message) {
    $query = "INSERT INTO notification (user_id, message) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $message);
    mysqli_stmt_execute($stmt);
}

// Get unread notification count for the logged in user
function get_unread_notification_count($conn, $user_id) {
    $query = "SELECT COUNT(*) AS total FROM notification WHERE user_id = ? AND is_read = 0";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row["total"];
}

// Get or create the course chat group for a course
function get_course_chat_group_id($conn, $course_id) {
    $query = "SELECT chat_group_id FROM chat_group WHERE chat_type = 'course' AND course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row["chat_group_id"];
    }

    $insert = "INSERT INTO chat_group (chat_type, course_id) VALUES ('course', ?)";
    $stmt2 = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt2, "i", $course_id);
    mysqli_stmt_execute($stmt2);
    return mysqli_insert_id($conn);
}

// Get or create the private chat group for a project group
function get_project_chat_group_id($conn, $group_id) {
    $query = "SELECT chat_group_id FROM chat_group WHERE chat_type = 'project' AND project_group_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $group_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row["chat_group_id"];
    }

    $insert = "INSERT INTO chat_group (chat_type, project_group_id) VALUES ('project', ?)";
    $stmt2 = mysqli_prepare($conn, $insert);
    mysqli_stmt_bind_param($stmt2, "i", $group_id);
    mysqli_stmt_execute($stmt2);
    return mysqli_insert_id($conn);
}

// Check if a student is enrolled in a course
function is_student_enrolled($conn, $student_id, $course_id) {
    $query = "SELECT enrollment_id FROM course_enrollment WHERE student_id = ? AND course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $student_id, $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// Check if a course belongs to a teacher
function is_course_owner($conn, $teacher_id, $course_id) {
    $query = "SELECT course_id FROM course WHERE course_id = ? AND teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $course_id, $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// Check if a student belongs to a project group
function is_group_member($conn, $student_id, $group_id) {
    $query = "SELECT member_id FROM project_group_member WHERE student_id = ? AND group_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $student_id, $group_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

// Format a date nicely
function format_date($date) {
    return date("d M Y, h:i A", strtotime($date));
}
?>
