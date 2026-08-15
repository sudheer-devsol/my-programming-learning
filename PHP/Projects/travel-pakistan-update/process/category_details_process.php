<?php

// 1. Get Category ID from URL
$category_id = $_GET["category_id"] ?? 0;

if (empty($category_id)) {
    header("Location: categories.php");
    exit();
}

// 2. Fetch Category Info
$cat_query = "SELECT category_title, category_description FROM category WHERE category_id = ? AND category_status = 'Active'";
$cat_stmt = mysqli_prepare($conn, $cat_query);
mysqli_stmt_bind_param($cat_stmt, "i", $category_id);
mysqli_stmt_execute($cat_stmt);
$cat_result = mysqli_stmt_get_result($cat_stmt);

if (mysqli_num_rows($cat_result) == 0) {
    // Redirect if category doesn't exist or isn't active
    header("Location: categories.php");
    exit();
}

$category_info = mysqli_fetch_assoc($cat_result);
$page_title = $category_info["category_title"];
$active_page = "categories";

include "includes/header.php";

// 3. Fetch Active Posts belonging to this Category
$posts_query = "
    SELECT
        post.post_id,
        post.post_title,
        post.post_summary,
        post.featured_image,
        post.created_at,
        user.first_name,
        user.last_name,
        category.category_title
    FROM category
    INNER JOIN post_category
        ON category.category_id = post_category.category_id
    INNER JOIN post
        ON post_category.post_id = post.post_id
    INNER JOIN blog
        ON post.blog_id = blog.blog_id
    INNER JOIN user
        ON blog.user_id = user.user_id
    WHERE category.category_id = ?
      AND category.category_status = 'Active'
      AND post.post_status = 'Active'
      AND blog.blog_status = 'Active'
    ORDER BY post.created_at DESC
";
?>
