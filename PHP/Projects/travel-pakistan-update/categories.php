<?php
$page_title = "Categories";
$active_page = "categories";
include "includes/header.php";

// ===========================================
// Database Connection
// ===========================================

include "config/database.php";

$category_query = "

SELECT

    category.category_id,
    category.category_title,

    COUNT(post_category.post_id) AS total_posts

FROM category

LEFT JOIN post_category
ON category.category_id = post_category.category_id

LEFT JOIN post
ON post_category.post_id = post.post_id
AND post.post_status = 'Active'

WHERE category.category_status = 'Active'

GROUP BY category.category_id

ORDER BY category.category_title ASC

";

$category_result = mysqli_query(
    $conn,
    $category_query
);
?>

<section class="section-tight" style="background:var(--paper-raised);border-bottom:1px solid var(--line);">
    <div class="container">
        <div class="eyebrow">Travel by Type</div>
        <h1 style="font-size:2.4rem;">Categories</h1>
        <p class="mb-0" style="max-width:640px;">Every destination is tagged by the kind of trip it makes, not just where it sits. A single place can belong to more than one — Hunza is both a valley and an adventure.</p>
    </div>
</section>

<section class="section">
    <div class="container">
       <div class="row g-3">

<?php

while($category = mysqli_fetch_assoc($category_result))
{

?>

<div class="col-lg-3 col-md-4 col-6">

    <a href="category-details.php?category_id=<?= $category["category_id"]; ?>" class="category-pill">

        <span class="cat-icon">

            <i class="bi bi-tag"></i>

        </span>

        <div>

            <strong>

                <?= htmlspecialchars($category["category_title"]); ?>

            </strong>

            <div class="post-meta">

                <?= $category["total_posts"]; ?> Posts

            </div>

        </div>

    </a>

</div>

<?php

}

?>

</div>
    </div>
</section>

<?php include "includes/footer.php"; ?>
</body>
</html>
