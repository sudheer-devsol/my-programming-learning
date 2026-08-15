<?php

// ===========================================
// Provinces (Blogs)
// ===========================================
$province_query = "SELECT blog.blog_id, blog.blog_title, blog.blog_background_image,
COUNT(DISTINCT post.post_id) AS total_posts, COUNT(DISTINCT following_blog.follow_id) AS total_followers
FROM blog LEFT JOIN post ON blog.blog_id = post.blog_id AND post.post_status = 'Active'
LEFT JOIN following_blog ON blog.blog_id = following_blog.blog_following_id AND following_blog.status = 'Followed'
WHERE blog.blog_status = 'Active' GROUP BY blog.blog_id ORDER BY blog.blog_title ASC";

$province_result = mysqli_query(
    $conn,
    $province_query
);

// ===========================================
// Find which blogs the current user already follows
// (so the button can show "Following" instead of "Follow")
// ===========================================
$followed_blog_ids = array();

$logged_user = $_SESSION['user'] ?? $_SESSION['admin'] ?? $_SESSION;
$current_user_id = $logged_user['user_id'] ?? $logged_user['admin_id'] ?? $_SESSION['user_id'] ?? null;

if (!empty($current_user_id)) {

    $followed_query = "SELECT blog_following_id FROM following_blog WHERE follower_id = ? AND status = 'Followed'";
    $followed_stmt = mysqli_prepare($conn, $followed_query);
    mysqli_stmt_bind_param($followed_stmt, "i", $current_user_id);
    mysqli_stmt_execute($followed_stmt);
    $followed_result = mysqli_stmt_get_result($followed_stmt);

    while ($followed_row = mysqli_fetch_assoc($followed_result)) {
        $followed_blog_ids[] = $followed_row['blog_following_id'];
    }
}
?>