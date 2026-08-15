<?php

include __DIR__ . "/../config/database.php";

$user_id = $_SESSION["user_id"];

//================Total Followed Blogs==================================


$query = "SELECT COUNT(follow_id) AS total_followed_blogs FROM following_blog 
WHERE follower_id = ? AND status='Followed'";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total_followed_blogs = mysqli_fetch_assoc($result);


//===================Total Comments Posted===============================


$query = "SELECT COUNT(post_comment_id) AS total_comments FROM post_comment WHERE user_id = ?";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total_comments = mysqli_fetch_assoc($result);


// =================Posts From Followed Blogs=================================

$query = "SELECT COUNT(post.post_id) AS total_posts FROM following_blog
INNER JOIN blog ON following_blog.blog_following_id = blog.blog_id
INNER JOIN post ON blog.blog_id = post.blog_id WHERE following_blog.follower_id = ? 
AND following_blog.status='Followed'
AND post.post_status='Active'";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$total_posts = mysqli_fetch_assoc($result);


//===================Account Status===============================

$query = "SELECT is_active FROM user WHERE user_id = ?";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$row = mysqli_fetch_assoc($result);

$account_status = $row["is_active"];


//====================Latest Posts From Followed Blogs==============================

$query = "SELECT post.post_id, post.post_title, post.featured_image, post.created_at, blog.blog_title
FROM following_blog INNER JOIN blog ON following_blog.blog_following_id = blog.blog_id
INNER JOIN post ON blog.blog_id = post.blog_id
WHERE following_blog.follower_id = ? AND following_blog.status='Followed' AND post.post_status='Active'
ORDER BY post.created_at DESC LIMIT 5";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$latest_posts_result = mysqli_stmt_get_result($stmt);


// =====================Blogs Followed=============================

$query = "SELECT blog.blog_title, blog.blog_background_image, COUNT(post.post_id) AS total_posts
FROM following_blog INNER JOIN blog ON following_blog.blog_following_id = blog.blog_id
LEFT JOIN post ON blog.blog_id = post.blog_id AND post.post_status='Active'
WHERE following_blog.follower_id = ? AND following_blog.status='Followed'
GROUP BY blog.blog_id ORDER BY blog.blog_title ASC
LIMIT 5";

$stmt = mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);

$followed_blogs_result = mysqli_stmt_get_result($stmt);

?>