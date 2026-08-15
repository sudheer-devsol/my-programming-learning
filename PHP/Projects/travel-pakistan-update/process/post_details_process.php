<?php
if (!isset($_SESSION)) {
    session_start();
}

// ============ Get Post ID =============
if (!isset($_GET["post_id"])) {
    header("Location: posts.php");
    exit();
}

$post_id = $_GET["post_id"];

// ================= Get Post Details ==========================
$post_query = "SELECT post.*, blog.blog_title, blog.blog_id, user.first_name, user.last_name
FROM post
INNER JOIN blog ON blog.blog_id = post.blog_id
INNER JOIN user ON user.user_id = blog.user_id
WHERE post.post_id = ? AND post.post_status = 'Active'";

$stmt = mysqli_prepare($conn, $post_query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$post_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($post_result) == 0) {
    header("Location: posts.php");
    exit();
}

$post = mysqli_fetch_assoc($post_result);

// ================ Get Categories ===========================
$category_query = "SELECT category.category_id, category.category_title
FROM post_category
INNER JOIN category ON category.category_id = post_category.category_id
WHERE post_category.post_id = ?";

$stmt_cat = mysqli_prepare($conn, $category_query);
mysqli_stmt_bind_param($stmt_cat, "i", $post_id);
mysqli_stmt_execute($stmt_cat);
$category_result = mysqli_stmt_get_result($stmt_cat);

// ================= Get Gallery Images ==========================
$gallery_query = "SELECT * FROM post_atachment WHERE post_id = ? AND is_active = 'Active'";

$stmt_gal = mysqli_prepare($conn, $gallery_query);
mysqli_stmt_bind_param($stmt_gal, "i", $post_id);
mysqli_stmt_execute($stmt_gal);
$gallery_result = mysqli_stmt_get_result($stmt_gal);

// ===================== Related Posts ======================
$related_query = "SELECT * FROM post WHERE blog_id = ? AND post_status = 'Active' AND post_id != ? ORDER BY created_at DESC LIMIT 4";

$stmt_rel = mysqli_prepare($conn, $related_query);
mysqli_stmt_bind_param($stmt_rel, "ii", $post["blog_id"], $post["post_id"]);
mysqli_stmt_execute($stmt_rel);
$related_post_result = mysqli_stmt_get_result($stmt_rel);

// ===================== Get Comments ======================
$comments_query = "SELECT post_comment.*, user.first_name, user.last_name, user.user_image
FROM post_comment
INNER JOIN user ON user.user_id = post_comment.user_id
WHERE post_comment.post_id = ? AND post_comment.is_active = 'Active'
ORDER BY post_comment.created_at DESC";

$stmt_comments = mysqli_prepare($conn, $comments_query);
mysqli_stmt_bind_param($stmt_comments, "i", $post_id);
mysqli_stmt_execute($stmt_comments);
$comments_result = mysqli_stmt_get_result($stmt_comments);
$comments_count = mysqli_num_rows($comments_result);
?>
