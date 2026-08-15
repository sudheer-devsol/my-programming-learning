<?php

// Safely start session without triggering auth redirects
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------------
// 1. HANDLE AJAX FOLLOW / UNFOLLOW ACTION
// -------------------------------------------------------------
if (isset($_POST["action"]) && $_POST["action"] == "follow_blog") {

    // Read session data flexibly
    $logged_user = $_SESSION["user"] ?? $_SESSION["admin"] ?? $_SESSION;

    $user_id = $logged_user["user_id"] ?? $logged_user["admin_id"] ?? $_SESSION["user_id"] ?? null;
    $role_id = $logged_user["role_id"] ?? $_SESSION["role_id"] ?? null;

    // 1. Check login state
    if (empty($user_id)) {
        echo "login_required";
        exit;
    }

    // 2. Rule: Role ID = 1 (Admin) cannot follow blogs
    if ($role_id == 1) {
        echo "Admins are not permitted to follow blogs.";
        exit;
    }

    // 3. Validate Blog ID from POST request
    if (empty($_POST["blog_id"])) {
        echo "Invalid blog identifier.";
        exit;
    }

    $post_blog_id = $_POST["blog_id"];

    // Check existing follow status in following_blog table
    $check_query = "SELECT follow_id, status FROM following_blog WHERE follower_id = ? AND blog_following_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $post_blog_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if ($row["status"] == "Followed") {
            $new_status = "Unfollowed";
            $response = "unfollowed";
        } else {
            $new_status = "Followed";
            $response = "followed";
        }

        $update_query = "UPDATE following_blog SET status = ? WHERE follow_id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        $follow_id = $row["follow_id"];
        mysqli_stmt_bind_param($update_stmt, "si", $new_status, $follow_id);

        if (mysqli_stmt_execute($update_stmt)) {
            echo $response;
        } else {
            echo "Error updating follow state.";
        }
    } else {
        $insert_query = "INSERT INTO following_blog (follower_id, blog_following_id, status) VALUES (?, ?, 'Followed')";
        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ii", $user_id, $post_blog_id);

        if (mysqli_stmt_execute($insert_stmt)) {
            echo "followed";
        } else {
            echo "Error following blog.";
        }
    }

    exit;
}

// -------------------------------------------------------------
// 2. REGULAR PAGE LOAD PROCESS
// -------------------------------------------------------------
if (!isset($_GET["blog_id"]) && !isset($_REQUEST["blog_id"])) {
    header("Location: provinces.php");
    exit;
}

$blog_id = $_REQUEST["blog_id"];

//=========== Province Details ===============================
$blog_query = "SELECT blog.blog_id, blog.user_id, blog.blog_title, blog.post_per_page,
blog.blog_background_image, blog.blog_status, blog.created_at, blog.updated_at, user.first_name,
user.last_name, user.user_image FROM blog LEFT JOIN user ON blog.user_id = user.user_id
WHERE blog.blog_id = ? AND blog.blog_status = 'Active' LIMIT 1";

$blog_stmt = mysqli_prepare($conn, $blog_query);
mysqli_stmt_bind_param($blog_stmt, "i", $blog_id);
mysqli_stmt_execute($blog_stmt);
$blog_result = mysqli_stmt_get_result($blog_stmt);

if (mysqli_num_rows($blog_result) == 0) {
    header("Location: provinces.php");
    exit;
}

$blog = mysqli_fetch_assoc($blog_result);

//================ Calculate Hero Badges ==========================

// Total Active Posts
$total_posts_query = "SELECT COUNT(*) AS total_posts FROM post WHERE blog_id = ? AND post_status = 'Active'";
$total_posts_stmt = mysqli_prepare($conn, $total_posts_query);
mysqli_stmt_bind_param($total_posts_stmt, "i", $blog_id);
mysqli_stmt_execute($total_posts_stmt);
$blog["total_posts"] = mysqli_fetch_assoc(mysqli_stmt_get_result($total_posts_stmt))["total_posts"];

// Total Followers
$total_followers_query = "SELECT COUNT(*) AS total_followers FROM following_blog WHERE blog_following_id = ? AND status = 'Followed'";
$total_followers_stmt = mysqli_prepare($conn, $total_followers_query);
mysqli_stmt_bind_param($total_followers_stmt, "i", $blog_id);
mysqli_stmt_execute($total_followers_stmt);
$blog["total_followers"] = mysqli_fetch_assoc(mysqli_stmt_get_result($total_followers_stmt))["total_followers"];

// Total Categories
$total_categories_query = "SELECT COUNT(DISTINCT post_category.category_id) AS total_categories FROM post
INNER JOIN post_category ON post.post_id = post_category.post_id WHERE post.blog_id = ?";
$total_categories_stmt = mysqli_prepare($conn, $total_categories_query);
mysqli_stmt_bind_param($total_categories_stmt, "i", $blog_id);
mysqli_stmt_execute($total_categories_stmt);
$blog["total_categories"] = mysqli_fetch_assoc(mysqli_stmt_get_result($total_categories_stmt))["total_categories"];

// Total Comments
$total_comments_query = "SELECT COUNT(*) AS total_comments FROM post_comment
INNER JOIN post ON post_comment.post_id = post.post_id WHERE post.blog_id = ? AND post_comment.is_active = 'Active'";
$total_comments_stmt = mysqli_prepare($conn, $total_comments_query);
mysqli_stmt_bind_param($total_comments_stmt, "i", $blog_id);
mysqli_stmt_execute($total_comments_stmt);
$blog["total_comments"] = mysqli_fetch_assoc(mysqli_stmt_get_result($total_comments_stmt))["total_comments"];

//============= Check Follow Status for Logged-In User =============
$is_following = false;
$logged_user = $_SESSION["user"] ?? $_SESSION["admin"] ?? $_SESSION;
$current_user_id = $logged_user["user_id"] ?? $logged_user["admin_id"] ?? $_SESSION["user_id"] ?? null;
$role_id = $logged_user["role_id"] ?? $_SESSION["role_id"] ?? 0;

if (!empty($current_user_id) && $role_id != 1) {
    $check_follow_query = "SELECT status FROM following_blog WHERE follower_id = ? AND blog_following_id = ? LIMIT 1";
    $check_follow_stmt = mysqli_prepare($conn, $check_follow_query);
    mysqli_stmt_bind_param($check_follow_stmt, "ii", $current_user_id, $blog_id);
    mysqli_stmt_execute($check_follow_stmt);
    $check_follow_res = mysqli_stmt_get_result($check_follow_stmt);

    if ($check_follow_res && mysqli_num_rows($check_follow_res) > 0) {
        $follow_data = mysqli_fetch_assoc($check_follow_res);
        if ($follow_data["status"] == "Followed") {
            $is_following = true;
        }
    }
}

//============= Province Posts Query =============================
$post_query = "SELECT post.post_id, post.blog_id, post.post_title, post.post_summary,
post.post_description, post.featured_image, post.post_status, post.is_comment_allowed,
post.created_at, user.first_name, user.last_name FROM post INNER JOIN blog
ON post.blog_id = blog.blog_id INNER JOIN user ON blog.user_id = user.user_id
WHERE post.blog_id = ? AND post.post_status = 'Active' ORDER BY post.created_at DESC";

$post_stmt = mysqli_prepare($conn, $post_query);
mysqli_stmt_bind_param($post_stmt, "i", $blog_id);
mysqli_stmt_execute($post_stmt);
$post_result = mysqli_stmt_get_result($post_stmt);
?>
