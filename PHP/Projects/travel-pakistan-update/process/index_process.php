<?php

// ===========================================
// Database Connection
// ===========================================

include "config/database.php";


// ===========================================
// Hero Provinces (Passport)
// ===========================================
$hero_query = "SELECT blog_id, blog_title FROM blog WHERE blog_status = 'Active'
ORDER BY blog_id ASC LIMIT 6";

$hero_result = mysqli_query($conn, $hero_query);

// ===========Featured Provinces================================
$featured_query = "SELECT blog.blog_id, blog.blog_title, blog.blog_background_image,
COUNT(DISTINCT post.post_id) AS total_posts,
COUNT(DISTINCT following_blog.follow_id) AS total_followers
FROM blog
LEFT JOIN post ON blog.blog_id = post.blog_id AND post.post_status = 'Active'
LEFT JOIN following_blog ON blog.blog_id = following_blog.blog_following_id AND following_blog.status = 'Followed'
WHERE blog.blog_status = 'Active'
GROUP BY blog.blog_id
ORDER BY total_followers DESC LIMIT 3";

$featured_result = mysqli_query($conn, $featured_query);


// ============Latest Posts===============================
$latest_posts_query = "SELECT post.post_id, post.post_title, post.post_summary, post.featured_image, post.created_at,
user.first_name, user.last_name, category.category_title
FROM post INNER JOIN blog ON post.blog_id = blog.blog_id INNER JOIN user ON blog.user_id = user.user_id
LEFT JOIN post_category ON post.post_id = post_category.post_id LEFT JOIN category ON post_category.category_id = category.category_id
WHERE post.post_status = 'Active' AND blog.blog_status = 'Active' GROUP BY post.post_id ORDER BY post.created_at DESC LIMIT 3";

$latest_posts = mysqli_query($conn, $latest_posts_query);


// ================Categories===========================
$categories_query = "SELECT category.category_id, category.category_title, COUNT(post_category.post_id) AS total_posts
FROM category LEFT JOIN post_category ON category.category_id = post_category.category_id
WHERE category.category_status = 'Active' GROUP BY category.category_id ORDER BY total_posts DESC LIMIT 4";

$categories_result = mysqli_query($conn, $categories_query);
?>
