<?php
// Include database connection
include "config/database.php";

include "process/category_details_process.php";

$posts_stmt = mysqli_prepare($conn, $posts_query);
mysqli_stmt_bind_param($posts_stmt, "i", $category_id);
mysqli_stmt_execute($posts_stmt);
$posts_result = mysqli_stmt_get_result($posts_stmt);
$total_posts = mysqli_num_rows($posts_result);
?>

<section class="section-tight" style="background:var(--paper-raised);border-bottom:1px solid var(--line);">
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="eyebrow">Category</div>
            <h1 style="font-size:2.2rem;">
                <i class="bi bi-tag" style="color:var(--brick);"></i> 
                <?= htmlspecialchars($category_info['category_title']); ?>
            </h1>
            <p class="mb-0">
                <?= !empty($category_info['category_description']) ? htmlspecialchars($category_info['category_description']) : $total_posts . ' destination(s) tagged under this category.'; ?>
            </p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4" id="catPostGrid">
            <?php 
            if ($total_posts > 0) {
                while ($post = mysqli_fetch_assoc($posts_result)) {
                    
                    // Image fallback handling
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
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="post-details.php?post_id=<?= $post['post_id']; ?>" class="card-tp post-card d-block text-decoration-none">
                            <div class="img-wrap">
                                <img src="<?= htmlspecialchars($image_src); ?>" alt="<?= htmlspecialchars($post['post_title']); ?>">
                            </div>
                            <div class="card-body-tp">
                                <div class="cat-badges">
                                    <span class="badge-stamp"><?= htmlspecialchars($category_info['category_title']); ?></span>
                                </div>
                                <h3><?= htmlspecialchars($post['post_title']); ?></h3>
                                <div class="post-meta">
                                    <span><i class="bi bi-person"></i> <?= htmlspecialchars($author_name); ?></span>
                                    <span class="dot"><i class="bi bi-calendar3"></i> <?= $formatted_date; ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="col-12"><p class="text-center mt-4" style="color:var(--ink-soft);">No active posts found in this category.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>
</body>
</html>