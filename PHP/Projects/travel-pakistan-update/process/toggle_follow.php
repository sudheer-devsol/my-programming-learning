<?php
// =============== Separate Handler for Follow / Unfollow =========
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include_once "../config/database.php";

// 1. Get logged-in user ID safely
$logged_user = $_SESSION['user'] ?? $_SESSION['admin'] ?? $_SESSION;
$user_id = $logged_user['user_id'] ?? $logged_user['admin_id'] ?? $_SESSION['user_id'] ?? null;
$role_id = $logged_user['role_id'] ?? $_SESSION['role_id'] ?? null;

if (empty($user_id)) {
    echo "login_required";
    exit;
}

if ($role_id == 1) {
    echo "Admins are not permitted to follow blogs.";
    exit;
}

// 2. Validate input
if (!isset($_POST['blog_id']) || empty($_POST['blog_id'])) {
    echo "Invalid blog identifier.";
    exit;
}

$blog_id = $_POST['blog_id'];

// 3. Check follow status
$check_sql = "SELECT follow_id, status FROM following_blog WHERE follower_id = ? AND blog_following_id = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $blog_id);
mysqli_stmt_execute($check_stmt);
$check_res = mysqli_stmt_get_result($check_stmt);

if ($check_res && mysqli_num_rows($check_res) > 0) {
    $row = mysqli_fetch_assoc($check_res);
    $follow_id = $row['follow_id'];

    if ($row['status'] == 'Followed') {
        $update_sql = "UPDATE following_blog SET status = 'Unfollowed' WHERE follow_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $follow_id);

        if (mysqli_stmt_execute($update_stmt)) {
            echo "unfollowed";
        } else {
            echo "Error updating follow state.";
        }
    } else {
        $update_sql = "UPDATE following_blog SET status = 'Followed' WHERE follow_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $follow_id);

        if (mysqli_stmt_execute($update_stmt)) {
            echo "followed";
        } else {
            echo "Error updating follow state.";
        }
    }
} else {
    // Insert new follow record
    $insert_sql = "INSERT INTO following_blog (follower_id, blog_following_id, status) VALUES (?, ?, 'Followed')";
    $insert_stmt = mysqli_prepare($conn, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "ii", $user_id, $blog_id);

    if (mysqli_stmt_execute($insert_stmt)) {
        echo "followed";
    } else {
        echo "Error following blog.";
    }
}
exit;
?>
