<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once "../config/database.php";

// Verify session without triggering header() location redirects
$user_id = 0;
$role_id = 0;

if (isset($_SESSION['user'])) {
    $user_id = isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0;
    $role_id = isset($_SESSION['user']['role_id']) ? $_SESSION['user']['role_id'] : 0;
} elseif (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $role_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 0;
}

// If user is not logged in, return simple AJAX code instead of HTML
if ($user_id <= 0) {
    echo "login_required";
    exit();
}

// Retrieve values directly from $_POST
$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

// Validate inputs
if ($post_id <= 0 || empty($comment)) {
    echo "Please write a review before submitting.";
    exit();
}

// Check if commenting is allowed on this post AND fetch the post author's user_id
$stmt_check = mysqli_prepare($conn, "SELECT post.is_comment_allowed, blog.user_id AS post_author_id 
                                     FROM post 
                                     INNER JOIN blog ON blog.blog_id = post.blog_id 
                                     WHERE post.post_id = ? AND post.post_status = 'Active'");
mysqli_stmt_bind_param($stmt_check, "i", $post_id);
mysqli_stmt_execute($stmt_check);
$res_check = mysqli_stmt_get_result($stmt_check);

if ($row_check = mysqli_fetch_assoc($res_check)) {
    if ($row_check['is_comment_allowed'] != 1) {
        echo "Comments are disabled for this post.";
        exit();
    }

    $post_author_id = $row_check['post_author_id'];

    // Rule: Admin (role_id = 1) cannot comment on their own posts, but CAN comment on posts by other admins/users
    if ($role_id == 1 && $user_id == $post_author_id) {
        echo "Admins cannot comment on their own posts.";
        exit();
    }
} else {
    echo "Post not found.";
    exit();
}

// Insert comment into post_comment table
$stmt_insert = mysqli_prepare($conn, "INSERT INTO post_comment (post_id, user_id, comment, is_active) VALUES (?, ?, ?, 'Active')");
mysqli_stmt_bind_param($stmt_insert, "iis", $post_id, $user_id, $comment);

if (mysqli_stmt_execute($stmt_insert)) {
    echo "success";
} else {
    echo "Failed to post review. Please try again.";
}
?>