<?php
// =============== Include the user session & database =========
include "../includes/user-session.php";
include_once "../config/database.php";

$page_title = "Followed Blogs";
$dash_role = "user";
include "../includes/dash-header.php";
$active = "follows";

// 1. Get current logged-in user ID safely without shorthand
$user_id = 0;
if (isset($_SESSION['user']['user_id'])) {
    $user_id = $_SESSION['user']['user_id'];
} else if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

// 2. Fetch all blogs followed by this user
$followed_blogs = array();
$followed_blog_ids = array();

$follows_sql = "SELECT * FROM following_blog WHERE follower_id = ? AND status = 'Followed'";
$follows_stmt = mysqli_prepare($conn, $follows_sql);
mysqli_stmt_bind_param($follows_stmt, "i", $user_id);
mysqli_stmt_execute($follows_stmt);
$follows_result = mysqli_stmt_get_result($follows_stmt);

if ($follows_result && mysqli_num_rows($follows_result) > 0) {
    while ($follow_row = mysqli_fetch_assoc($follows_result)) {
        
        $b_id = $follow_row['blog_following_id'];
        
        // Fetch blog details for this blog_id
        $blog_sql = "SELECT * FROM blog WHERE blog_id = ? AND blog_status = 'Active'";
        $blog_stmt = mysqli_prepare($conn, $blog_sql);
        mysqli_stmt_bind_param($blog_stmt, "i", $b_id);
        mysqli_stmt_execute($blog_stmt);
        $blog_res = mysqli_stmt_get_result($blog_stmt);
        
        if ($blog_res && mysqli_num_rows($blog_res) > 0) {
            $blog_data = mysqli_fetch_assoc($blog_res);
            
            // Count total active posts in this blog
            $count_sql = "SELECT COUNT(*) AS total FROM post WHERE blog_id = ? AND post_status = 'Active'";
            $count_stmt = mysqli_prepare($conn, $count_sql);
            mysqli_stmt_bind_param($count_stmt, "i", $b_id);
            mysqli_stmt_execute($count_stmt);
            $count_res = mysqli_stmt_get_result($count_stmt);
            $count_row = mysqli_fetch_assoc($count_res);
            
            $blog_data['article_count'] = $count_row['total'];
            
            $followed_blogs[] = $blog_data;
            $followed_blog_ids[] = $b_id;
        }
    }
}

// 3. Fetch feed posts from followed blogs
$feed_posts = array();

if (!empty($followed_blog_ids)) {
    // Build one "?" placeholder for each followed blog id
    $placeholders = implode(",", array_fill(0, count($followed_blog_ids), "?"));
    $types = str_repeat("i", count($followed_blog_ids));

    $posts_sql = "SELECT * FROM post WHERE blog_id IN ($placeholders) AND post_status = 'Active' ORDER BY post_id DESC LIMIT 12";
    $posts_stmt = mysqli_prepare($conn, $posts_sql);
    mysqli_stmt_bind_param($posts_stmt, $types, ...$followed_blog_ids);
    mysqli_stmt_execute($posts_stmt);
    $posts_result = mysqli_stmt_get_result($posts_stmt);

    if ($posts_result && mysqli_num_rows($posts_result) > 0) {
        while ($post_row = mysqli_fetch_assoc($posts_result)) {
            
            // Get blog title for this post
            $p_blog_id = $post_row['blog_id'];
            $b_name_sql = "SELECT blog_title FROM blog WHERE blog_id = ?";
            $b_name_stmt = mysqli_prepare($conn, $b_name_sql);
            mysqli_stmt_bind_param($b_name_stmt, "i", $p_blog_id);
            mysqli_stmt_execute($b_name_stmt);
            $b_name_res = mysqli_stmt_get_result($b_name_stmt);
            $b_name_row = mysqli_fetch_assoc($b_name_res);
            
            $post_row['blog_title'] = isset($b_name_row['blog_title']) ? $b_name_row['blog_title'] : '';

            // Get primary category for this post
            $post_id_val = $post_row['post_id'];
            $post_cat_sql = "SELECT category_id FROM post_category WHERE post_id = ? LIMIT 1";
            $post_cat_stmt = mysqli_prepare($conn, $post_cat_sql);
            mysqli_stmt_bind_param($post_cat_stmt, "i", $post_id_val);
            mysqli_stmt_execute($post_cat_stmt);
            $post_cat_res = mysqli_stmt_get_result($post_cat_stmt);
            
            $post_row['category_name'] = '';
            if ($post_cat_res && mysqli_num_rows($post_cat_res) > 0) {
                $post_cat_row = mysqli_fetch_assoc($post_cat_res);
                $cat_id_val = $post_cat_row['category_id'];
                
                $cat_sql = "SELECT category_title FROM category WHERE category_id = ?";
                $cat_stmt = mysqli_prepare($conn, $cat_sql);
                mysqli_stmt_bind_param($cat_stmt, "i", $cat_id_val);
                mysqli_stmt_execute($cat_stmt);
                $cat_res = mysqli_stmt_get_result($cat_stmt);
                if ($cat_res && mysqli_num_rows($cat_res) > 0) {
                    $cat_row = mysqli_fetch_assoc($cat_res);
                    $post_row['category_name'] = $cat_row['category_title'];
                }
            }

            $feed_posts[] = $post_row;
        }
    }
}

?>

<div class="dash-shell">
    <?php include "../includes/user-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Followed Blogs</h2>
                <p class="mb-0">Manage the blogs feeding your dashboard.</p>
            </div>
        </div>

        <!-- Followed Blogs Grid -->
        <div class="row g-3 mb-5" id="followedGrid">
            <?php if (!empty($followed_blogs)) { ?>
                <?php foreach ($followed_blogs as $blog_item) { ?>
                    <?php
                        // Check blog image inside assets/images/blogs/
                        $blog_img = "https://picsum.photos/seed/" . urlencode($blog_item['blog_title']) . "/120/120";
                        if (isset($blog_item['blog_background_image']) && !empty($blog_item['blog_background_image'])) {
                            $blog_img = "../assets/images/blogs/" . $blog_item['blog_background_image'];
                        }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="panel-tp p-3 d-flex gap-3 align-items-center" data-blog-id="<?php echo $blog_item['blog_id']; ?>">
                            <img src="<?php echo $blog_img; ?>" style="width:56px;height:56px;border-radius:12px;object-fit:cover;" alt="<?php echo htmlspecialchars($blog_item['blog_title']); ?>">
                            <div class="flex-grow-1">
                                <strong><?php echo htmlspecialchars($blog_item['blog_title']); ?></strong>
                                <div class="post-meta" style="font-size:.8rem;"><?php echo $blog_item['article_count']; ?> articles</div>
                            </div>
                            <button class="btn btn-sm btn-danger-outline btn-unfollow-blog" title="Unfollow Blog" onclick="unfollowBlog(this);"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">You are not following any blogs yet.</div>
                </div>
            <?php } ?>
        </div>

        <!-- Feed Posts Section -->
        <h3 class="mb-3" style="font-size:1.25rem;">Feed From Your Follows</h3>
        <div class="row g-4">
            <?php if (!empty($feed_posts)) { ?>
                <?php foreach ($feed_posts as $post) { ?>
                    <?php
                        // Check post image inside assets/images/posts/
                        $post_img = "https://picsum.photos/seed/post" . $post['post_id'] . "/600/380";
                        if (isset($post['featured_image']) && !empty($post['featured_image'])) {
                            $post_img = "../assets/images/posts/" . $post['featured_image'];
                        }

                        // Format created date safely
                        $post_date = "";
                        if (isset($post['created_at']) && !empty($post['created_at'])) {
                            $post_date = date("M d, Y", strtotime($post['created_at']));
                        }
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="../post-details.php?id=<?php echo $post['post_id']; ?>" class="card-tp post-card d-block text-decoration-none">
                            <div class="img-wrap">
                                <img src="<?php echo $post_img; ?>" alt="<?php echo htmlspecialchars($post['post_title']); ?>">
                            </div>
                            <div class="card-body-tp">
                                <?php if (isset($post['category_name']) && !empty($post['category_name'])) { ?>
                                    <div class="cat-badges">
                                        <span class="badge-stamp"><?php echo htmlspecialchars($post['category_name']); ?></span>
                                    </div>
                                <?php } ?>
                                <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                                <div class="post-meta">
                                    <span><?php echo htmlspecialchars($post['blog_title']); ?></span>
                                    <?php if ($post_date != "") { ?>
                                        <span class="dot"><?php echo $post_date; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <p class="text-muted">No recent posts found from your followed blogs.</p>
                </div>
            <?php } ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/*
==========================================
My Follows Page - Unfollow via process/toggle_follow.php
==========================================
*/

function createXHR(){
    var xhr = null;

    if(window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
    }else{
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

function unfollowBlog(btn){
    var card = btn.closest("[data-blog-id]");
    var blogId = card.getAttribute("data-blog-id");

    var xhr = createXHR();

    xhr.open("POST", "../process/toggle_follow.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            if(xhr.status == 200){
                var response = xhr.responseText;

                if(response == "unfollowed"){
                    var colElement = card.closest(".col-lg-4");
                    if(colElement){
                        colElement.remove();
                    }
                }else{
                    alert(response);
                }
            }else{
                alert("Error HTTP " + xhr.status + ": Could not send request.");
            }
        }
    };

    xhr.send("blog_id=" + encodeURIComponent(blogId));
}
</script>
</body>
</html>