<?php

// ===========================================
// Page Information
// ===========================================

$page_title  = "Home";
$active_page = "home";


// ===========================================
// Database Connection
// ===========================================

include "config/database.php";


// ===========================================
// Home Page Process
// ===========================================

include "process/index_process.php";


// ===========================================
// Header
// ===========================================

include "includes/header.php";

// ===========================================
// Check Login Status Safely
// ===========================================
$is_logged_in = false;
if (isset($_SESSION['user']) || isset($_SESSION['admin']) || isset($_SESSION['user_id'])) {
    $is_logged_in = true;
}

?>

<!-- ==========================================================================
     HERO
     ========================================================================== -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="eyebrow on-dark">6 Provinces · One Trail</div>
                <h1>Travel Pakistan, province by province.</h1>
                <p class="lead">From the salt hills of Punjab to the glacial lakes of Gilgit-Baltistan — real routes, real elevation gains, written by travelers who made the trip.</p>
                <div class="d-flex gap-3 mt-4 flex-wrap">

                    <a href="provinces.php" class="btn btn-marigold">
                        <i class="bi bi-signpost"></i>
                        Explore Provinces
                    </a>

                    <?php if ($is_logged_in): ?>
                        <a href="posts.php"
                           class="btn border border-dark"
                           style="background:transparent; color:#000;">
                            <i class="bi bi-newspaper"></i>
                            See Latest Posts
                        </a>
                    <?php else: ?>
                        <a href="register.php"
                           class="btn border border-dark"
                           style="background:transparent; color:#000;">
                            <i class="bi bi-person-plus"></i>
                            Create an Account
                        </a>
                    <?php endif; ?>

                </div>
               
            </div>
            
            <div class="col-lg-5">

                <div class="province-passport">

                    <?php

                    while($hero = mysqli_fetch_assoc($hero_result))
                    {

                        $class = "";

                        switch(strtolower($hero["blog_title"]))
                        {

                            case "punjab":
                                $class = "passport-punjab";
                            break;

                            case "sindh":
                                $class = "passport-sindh";
                            break;

                            case "khyber pakhtunkhwa":
                                $class = "passport-kp";
                            break;

                            case "balochistan":
                                $class = "passport-balochistan";
                            break;

                            case "gilgit-baltistan":
                                $class = "passport-gb";
                            break;

                            case "azad jammu & kashmir":
                            case "azad jammu and kashmir":
                                $class = "passport-ajk";
                            break;

                            default:
                                $class = "passport-punjab";

                        }

                    ?>

    <a
    href="blog-details.php?blog_id=<?= $hero["blog_id"]; ?>"
    class="passport-card text-decoration-none">

        <div class="passport-circle <?= $class; ?>">

            <div class="passport-inner">

                <i class="bi bi-signpost"></i>

                <h6>
                    <?= htmlspecialchars($hero["blog_title"]); ?>
                </h6>

            </div>

        </div>

    </a>

<?php

}

?>

</div>

            </div>
     
        </div>
    </div>
</section>

<!-- ==========================================================================
     FEATURED PROVINCES
     ========================================================================== -->
<section class="section">

    <div class="container">

        <div class="section-head">

            <div>

                <div class="eyebrow">
                    Provinces
                </div>

                <h2>
                    Pick where to start.
                </h2>

            </div>

            <a href="provinces.php" class="link-all">

                View all provinces

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        <div class="row g-4">

            <?php

            while($province = mysqli_fetch_assoc($featured_result))
            {

                // Default image

                if(!empty($province["blog_background_image"]))
                {
                    $image =
                    "assets/images/blogs/" .
                    $province["blog_background_image"];
                }
                else
                {
                    $image =
                    "assets/images/blogs/default.jpg";
                }

            ?>

            <div class="col-lg-4 col-md-6">

                <a
                href="blog-details.php?blog_id=<?= $province["blog_id"]; ?>"
                class="card-tp province-card d-block text-decoration-none">

                    <div class="img-wrap">

                        <img
                        src="<?= $image; ?>"
                        alt="<?= htmlspecialchars($province["blog_title"]); ?>">

                        <div class="img-caption">

                            <h3>

                                <?= htmlspecialchars($province["blog_title"]); ?>

                            </h3>

                            <div class="meta">

                                <?= $province["total_posts"]; ?>

                                Articles

                                ·

                                <?= $province["total_followers"]; ?>

                                Followers

                            </div>

                        </div>

                    </div>

                    <div class="card-body-tp">

                        <span class="badge-stamp">

                            Province

                        </span>

                        <span class="btn btn-sm btn-outline-teal">

                            Explore

                        </span>

                    </div>

                </a>

            </div>

            <?php

            }

            ?>

        </div>

    </div>

</section>


<!-- ==========================================================================
     LATEST POSTS
     ========================================================================== -->
<section class="section section-tight" style="background:var(--paper-raised);border-top:1px solid var(--line);border-bottom:1px solid var(--line);">
    <div class="container">

        <div class="section-head">
            <div>
                <div class="eyebrow">
                    Fresh From The Trail
                </div>

                <h2>
                    Latest Posts
                </h2>
            </div>

            <a href="posts.php" class="link-all">
                View all posts
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">

            <?php

            if(mysqli_num_rows($latest_posts) > 0)
            {

                while($post = mysqli_fetch_assoc($latest_posts))
                {

                    if(empty($post['featured_image']))
                    {
                        $image =
                        "assets/images/posts/default.png";
                    }
                    else
                    {
                        $image =
                        "assets/images/posts/" .
                        $post['featured_image'];
                    }

            ?>

            <div class="col-lg-4 col-md-6">

                <a href="post-details.php?id=<?php echo $post['post_id']; ?>"
                   class="card-tp post-card d-block text-decoration-none">

                    <div class="img-wrap">

                        <img
                            src="<?php echo $image; ?>"
                            alt="<?php echo htmlspecialchars($post['post_title']); ?>">

                    </div>

                    <div class="card-body-tp">

                        <div class="cat-badges">

                            <?php
                            if(!empty($post['category_title']))
                            {
                            ?>
                                <span class="badge-stamp">
                                    <?php echo htmlspecialchars($post['category_title']); ?>
                                </span>
                            <?php
                            }
                            ?>

                        </div>

                        <h3>
                            <?php echo htmlspecialchars($post['post_title']); ?>
                        </h3>

                        <p class="mb-3">

                            <?php
                            echo substr(
                                strip_tags($post['post_summary']),
                                0,
                                120
                            );

                            if(strlen($post['post_summary']) > 120)
                            {
                                echo "...";
                            }
                            ?>

                        </p>

                        <div class="post-meta">

                            <span>

                                <i class="bi bi-person"></i>

                                <?php
                                echo $post['first_name'] .
                                " " .
                                $post['last_name'];
                                ?>

                            </span>

                            <span class="dot">

                                <i class="bi bi-calendar3"></i>

                                <?php
                                echo date(
                                    "M d, Y",
                                    strtotime($post['created_at'])
                                );
                                ?>

                            </span>

                        </div>

                    </div>

                </a>

            </div>

            <?php
                }
            }
            else
            {
            ?>

            <div class="col-12">

                <div class="alert alert-info text-center">

                    No posts found.

                </div>

            </div>

            <?php
            }
            ?>

        </div>

    </div>
</section>

<!-- ==========================================================================
     CATEGORIES
     ========================================================================== -->
<section class="section">

    <div class="container">

        <div class="section-head">

            <div>

                <div class="eyebrow">

                    Travel by Type

                </div>

                <h2>

                    Whatever kind of trip you're planning

                </h2>

            </div>

            <a href="categories.php" class="link-all">

                All Categories

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        <div class="row g-3">

            <?php

            if(mysqli_num_rows($categories_result) > 0)
            {

                while($category = mysqli_fetch_assoc($categories_result))
                {

                    // Default icon
                    $icon = "bi-bookmark";

                    switch(strtolower($category["category_title"]))
                    {

                        case "historical":
                            $icon = "bi-bank2";
                            break;

                        case "mountains":
                            $icon = "bi-triangle-fill";
                            break;

                        case "lakes":
                            $icon = "bi-water";
                            break;

                        case "beaches":
                            $icon = "bi-tsunami";
                            break;

                        case "culture":
                            $icon = "bi-globe";
                            break;

                        case "adventure":
                            $icon = "bi-compass";
                            break;

                        case "nature":
                            $icon = "bi-tree-fill";
                            break;

                    }

            ?>

            <div class="col-lg-3 col-md-4 col-6">

                <a
                href="category-details.php?category_id=<?= $category["category_id"]; ?>"
                class="category-pill">

                    <span class="cat-icon">

                        <i class="bi <?= $icon; ?>"></i>

                    </span>

                    <div>

                        <strong>

                            <?= htmlspecialchars($category["category_title"]); ?>

                        </strong>

                        <div class="post-meta">

                            <?= $category["total_posts"]; ?>

                            Posts

                        </div>

                    </div>

                </a>

            </div>

            <?php

                }

            }
            else
            {

            ?>

            <div class="col-12">

                <div class="alert alert-info text-center">

                    No categories found.

                </div>

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