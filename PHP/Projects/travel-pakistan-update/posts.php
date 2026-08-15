<?php
$page_title = "Latest Posts";
$active_page = "posts";

// Include processing script
require_once "process/process_posts.php";

include "includes/header.php";
?>

<section class="section-tight" style="background:var(--paper-raised);border-bottom:1px solid var(--line);">
    <div class="container">
        <div class="eyebrow">Every Article</div>
        <h1 style="font-size:2.4rem;">Latest Posts</h1>
        <p class="mb-0" style="max-width:640px;">Search across every category, or narrow down by author and month.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <!-- Search & Filter Form -->
        <div class="panel-tp mb-4">
            <div class="panel-body">
                <form method="GET" action="posts.php">
                    <div class="row g-3 form-tp align-items-end">
                        
                        <!-- Keyword Search -->
                        <div class="col-lg-3 col-md-6">
                            <label>Keyword</label>
                            <input type="text" name="keyword" class="form-control" placeholder="Search title..." value="<?php echo htmlspecialchars($keyword); ?>">
                        </div>

                        <!-- Category Filter -->
                        <div class="col-lg-3 col-md-6">
                            <label>Category</label>
                            <select name="category" class="form-select">
                                <option value="all">All Categories</option>
                                <?php 
                                if ($categories_result) {
                                    while ($cat_row = mysqli_fetch_assoc($categories_result)) {
                                        $selected = ($category == $cat_row['category_id']) ? 'selected' : '';
                                        echo "<option value='" . $cat_row['category_id'] . "' " . $selected . ">" . htmlspecialchars($cat_row['category_title']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div class="col-lg-2 col-md-6">
                            <label>Month</label>
                            <select name="month" class="form-select">
                                <option value="all">All Months</option>
                                <?php 
                                if ($months_result) {
                                    $seen_months = array();
                                    while ($month_row = mysqli_fetch_assoc($months_result)) {
                                        $month_val = date("Y-m", strtotime($month_row['created_at']));
                                        $month_label = date("F Y", strtotime($month_row['created_at']));
                                        
                                        if (!in_array($month_val, $seen_months)) {
                                            $seen_months[] = $month_val;
                                            $selected = ($month == $month_val) ? 'selected' : '';
                                            echo "<option value='" . $month_val . "' " . $selected . ">" . $month_label . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Author Filter -->
                        <div class="col-lg-3 col-md-6">
                            <label>Author</label>
                            <select name="author" class="form-select">
                                <option value="all">All Authors</option>
                                <?php 
                                if ($authors_result) {
                                    while ($author_row = mysqli_fetch_assoc($authors_result)) {
                                        $selected = ($author == $author_row['user_id']) ? 'selected' : '';
                                        $full_name = $author_row['first_name'] . " " . $author_row['last_name'];
                                        echo "<option value='" . $author_row['user_id'] . "' " . $selected . ">" . htmlspecialchars($full_name) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Filter Submit Button -->
                        <div class="col-lg-1 col-md-6 d-grid">
                            <button type="submit" class="btn btn-teal"><i class="bi bi-funnel"></i></button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Dynamic Posts Grid -->
        <div class="row g-4" id="postsGrid">
            <?php 
            if ($posts_result && mysqli_num_rows($posts_result) > 0) {
                while ($post = mysqli_fetch_assoc($posts_result)) {
                    
                    // Format image path correctly
                    $image_name = $post['featured_image'];
                    if (!empty($image_name)) {
                        if (strpos($image_name, 'http') === 0 || strpos($image_name, 'assets/') === 0) {
                            $image_src = $image_name;
                        } else {
                            $image_src = "assets/images/posts/" . $image_name;
                        }
                    } else {
                        $image_src = "assets/images/posts/default.jpg";
                    }

                    $formatted_date = date("M d, Y", strtotime($post['created_at']));
                    $author_name = $post['first_name'] . " " . $post['last_name'];
                    $category_name = !empty($post['category_title']) ? $post['category_title'] : 'General';
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="post-details.php?post_id=<?php echo $post['post_id']; ?>" class="card-tp post-card d-block text-decoration-none">
                            <div class="img-wrap">
                                <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($post['post_title']); ?>">
                            </div>
                            <div class="card-body-tp">
                                <div class="cat-badges">
                                    <span class="badge-stamp"><?php echo htmlspecialchars($category_name); ?></span>
                                </div>
                                <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                                <div class="post-meta">
                                    <span><i class="bi bi-person"></i> <?php echo htmlspecialchars($author_name); ?></span>
                                    <span class="dot"><i class="bi bi-calendar3"></i> <?php echo $formatted_date; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12"><p class="text-center mt-4" style="color:var(--ink-soft);">No posts match your filters.</p></div>';
            }
            ?>
        </div>

        <!-- Dynamic Pagination Links -->
        <?php if (isset($total_pages) && $total_pages > 1): ?>
            <?php $query_params = $_GET; ?>
            <nav class="pagination-tp mt-4">
                <?php if ($page > 1): ?>
                    <?php $query_params['page'] = $page - 1; ?>
                    <a href="?<?php echo http_build_query($query_params); ?>"><i class="bi bi-chevron-left"></i></a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php 
                    $query_params['page'] = $i; 
                    $active_class = ($i == $page) ? 'active' : '';
                    ?>
                    <a href="?<?php echo http_build_query($query_params); ?>" class="<?php echo $active_class; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <?php $query_params['page'] = $page + 1; ?>
                    <a href="?<?php echo http_build_query($query_params); ?>"><i class="bi bi-chevron-right"></i></a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

    </div>
</section>

<?php include "includes/footer.php"; ?>
</body>
</html>