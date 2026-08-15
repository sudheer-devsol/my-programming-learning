<?php
require_once "config/database.php";

// ================Fetch Categories for Dropdown================

$categories_query = "SELECT category.category_id, category.category_title
                     FROM category
                     WHERE category.category_status = 'Active'";

$categories_result = mysqli_query($conn, $categories_query);

//========================Fetch Authors for Dropdown========================

$authors_query = "SELECT user.user_id, user.first_name, user.last_name
                  FROM user
                  INNER JOIN role ON user.role_id = role.role_id
                  WHERE user.is_active = 'Active'
                  AND role.role_id = 1";

$authors_result = mysqli_query($conn, $authors_query);

//========Fetch Distinct Post Months for Dropdown================

$months_query = "SELECT DISTINCT DATE_FORMAT(post.created_at, '%Y-%m') AS month_val,
DATE_FORMAT(post.created_at, '%M %Y') AS month_label FROM post
WHERE post.post_status = 'Active' ORDER BY post.created_at DESC";

$months_result = mysqli_query($conn, $months_query);

//================Get Filter & Pagination Inputs================

$keyword  = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : 'all';
$month    = isset($_GET['month']) ? trim($_GET['month']) : 'all';
$author   = isset($_GET['author']) ? trim($_GET['author']) : 'all';

//================Pagination setup================
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$limit = 6;
$offset = ($page - 1) * $limit;

// ================Build Filter Conditions (WHERE Clause) Using Prepared Statement Placeholders

$where_clause = " WHERE post.post_status = 'Active'";
$where_params = array();
$where_types = "";

if (!empty($keyword)) {
    $where_clause .= " AND post.post_title LIKE ?";
    $where_params[] = "%" . $keyword . "%";
    $where_types .= "s";
}

if ($category != 'all' && !empty($category)) {
    $where_clause .= " AND category.category_id = ?";
    $where_params[] = $category;
    $where_types .= "i";
}

if ($month != 'all' && !empty($month)) {
    $where_clause .= " AND DATE_FORMAT(post.created_at, '%Y-%m') = ?";
    $where_params[] = $month;
    $where_types .= "s";
}

if ($author != 'all' && !empty($author)) {
    $where_clause .= " AND user.user_id = ?";
    $where_params[] = $author;
    $where_types .= "i";
}

// ==============Count Total Matching Posts (For Pagination)==========

$count_query = "SELECT COUNT(DISTINCT post.post_id) AS total_records FROM post
INNER JOIN blog ON post.blog_id = blog.blog_id
INNER JOIN user ON blog.user_id = user.user_id
LEFT JOIN post_category ON post.post_id = post_category.post_id
LEFT JOIN category ON post_category.category_id = category.category_id"
. $where_clause;

$count_stmt = mysqli_prepare($conn, $count_query);

if (!empty($where_types)) {
    mysqli_stmt_bind_param($count_stmt, $where_types, ...$where_params);
}

mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = isset($count_row['total_records']) ? $count_row['total_records'] : 0;
$total_pages = ceil($total_records / $limit);


// =================== Posts ===================

$posts_query = "SELECT post.post_id, post.post_title, post.featured_image, post.created_at,
user.first_name, user.last_name, category.category_title FROM post
INNER JOIN blog ON post.blog_id = blog.blog_id
INNER JOIN user ON blog.user_id = user.user_id
LEFT JOIN post_category ON post.post_id = post_category.post_id
LEFT JOIN category ON post_category.category_id = category.category_id"
. $where_clause . " GROUP BY post.post_id ORDER BY post.post_id DESC LIMIT ?, ?";

$posts_types = $where_types . "ii";
$posts_params = $where_params;
$posts_params[] = $offset;
$posts_params[] = $limit;

$posts_stmt = mysqli_prepare($conn, $posts_query);
mysqli_stmt_bind_param($posts_stmt, $posts_types, ...$posts_params);
mysqli_stmt_execute($posts_stmt);
$posts_result = mysqli_stmt_get_result($posts_stmt);
?>
