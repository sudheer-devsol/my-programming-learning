<?php
// Include Database connection and process file at top
include_once "config/database.php";
include_once "process/blog_details_process.php";

$page_title = "blogs";
$active_page = "provinces";
include "includes/header.php";

// Get role ID dynamically regardless of session structure
$logged_user = $_SESSION['user'] ?? $_SESSION['admin'] ?? $_SESSION;
$user_role_id = $logged_user['role_id'] ?? null;
?>

<!-- =============== Province Hero =========================== -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="eyebrow">Province Blog</div>
                <h1><?= htmlspecialchars($blog["blog_title"]); ?></h1>
                <p class="lead">
                    <?php
                    if(!empty($blog["blog_description"])){
                        echo nl2br(htmlspecialchars($blog["blog_description"]));
                    }else{
                        echo "Discover amazing places, travel guides and stories from this province.";
                    }
                    ?>
                </p>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <div class="badge bg-light text-dark p-3">
                        <strong><?= $blog["total_posts"]; ?></strong><br>Posts
                    </div>
                    <div class="badge bg-light text-dark p-3">
                        <strong id="followersCount"><?= $blog["total_followers"]; ?></strong><br>Followers
                    </div>
                    <div class="badge bg-light text-dark p-3">
                        <strong><?= $blog["total_categories"]; ?></strong><br>Categories
                    </div>
                    <div class="badge bg-light text-dark p-3">
                        <strong><?= $blog["total_comments"]; ?></strong><br>Comments
                    </div>
                </div>
                
                <!-- Follow / Unfollow Button area -->
                <div class="mt-4">
                    <?php if ($user_role_id == 1): ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="bi bi-slash-circle"></i> Admins cannot follow
                        </button>
                    <?php else: ?>
                        <button class="btn <?= $is_following ? 'btn-danger' : 'btn-marigold'; ?> btn-follow" data-blog-id="<?=$blog['blog_id'];?>" onclick="followBlog(this);">
                            <i class="bi <?= $is_following ? 'bi-dash-lg' : 'bi-plus-lg'; ?>"></i> 
                            <?= $is_following ? 'Unfollow' : 'Follow'; ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="<?= !empty($blog["blog_background_image"]) ? "assets/images/blogs/".$blog["blog_background_image"] : "assets/images/blogs/default.jpg"; ?>" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- =============== Province Articles =========================== -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Province Articles</div>
                <h2>Latest Posts</h2>
            </div>
        </div>
       <div class="row g-4" id="postGrid">
           <?php
            if(mysqli_num_rows($post_result) > 0){
                while($post = mysqli_fetch_assoc($post_result)){
                    // Fetch active comments count for this specific post
                    $p_id = $post['post_id'];
                    $c_count_query = "SELECT COUNT(*) AS total FROM post_comment WHERE post_id = ? AND is_active = 'Active'";
                    $c_count_stmt = mysqli_prepare($conn, $c_count_query);
                    mysqli_stmt_bind_param($c_count_stmt, "i", $p_id);
                    mysqli_stmt_execute($c_count_stmt);
                    $c_count_res = mysqli_stmt_get_result($c_count_stmt);
                    $post_comments_count = $c_count_res ? mysqli_fetch_assoc($c_count_res)['total'] : 0;
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card-tp post-card">
                    <a href="post-details.php?post_id=<?=$post['post_id'];?>">
                        <div class="img-wrap">
                            <img src="<?= !empty($post['featured_image']) ? 'assets/images/posts/'.$post['featured_image'] : 'assets/images/posts/default.jpg'; ?>">
                        </div>
                    </a>
                    <div class="card-body-tp">
                        <h3>
                            <a href="post-details.php?post_id=<?=$post['post_id'];?>" class="text-decoration-none text-dark">
                                <?=$post['post_title'];?>
                            </a>
                        </h3>
                        <p><?=substr($post['post_summary'], 0, 120);?>...</p>
                        <div class="post-meta">
                            <span>
                                <i class="bi bi-person"></i>
                                <?=$post['first_name'] . ' ' . $post['last_name'];?>
                            </span>
                            <span>
                                <i class="bi bi-chat"></i>
                                <?=$post_comments_count;?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            }else{
            ?>
            <div class="col-12 text-center">
                <h5>No Posts Available</h5>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>

<script>
/*
==========================================
Blog Details Page - Category Filter + Follow (AJAX)
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

function filterPosts(){
    var categoryFilter = document.getElementById("categoryFilter");
    var postCards = document.querySelectorAll("#postGrid > div");

    if(!categoryFilter){
        return;
    }

    var value = categoryFilter.value;

    for(var i = 0; i < postCards.length; i++){
        var col = postCards[i];
        var card = col.querySelector("[data-cat]");

        if(card){
            var cat = card.getAttribute("data-cat");

            if(value == "all" || cat == value){
                col.style.display = "";
            }else{
                col.style.display = "none";
            }
        }
    }
}

function followBlog(btn){
    var blogId = btn.getAttribute("data-blog-id");

    var xhr = createXHR();

    xhr.open("POST", window.location.href, true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            var res = xhr.responseText;
            var followersElem = document.getElementById("followersCount");
            var currentFollowers = followersElem ? (parseInt(followersElem.innerText) || 0) : 0;

            if(res == "followed"){
                btn.className = "btn btn-danger btn-follow";
                btn.innerHTML = '<i class="bi bi-dash-lg"></i> Unfollow';
                if(followersElem){
                    followersElem.innerText = currentFollowers + 1;
                }
            }else if(res == "unfollowed"){
                btn.className = "btn btn-marigold btn-follow";
                btn.innerHTML = '<i class="bi bi-plus-lg"></i> Follow';
                if(followersElem){
                    followersElem.innerText = Math.max(0, currentFollowers - 1);
                }
            }else if(res == "login_required"){
                window.location.href = "login.php";
            }else{
                alert(res);
            }
        }
    };

    xhr.send("blog_id=" + encodeURIComponent(blogId) + "&action=follow_blog");
}
</script>
</body>
</html>