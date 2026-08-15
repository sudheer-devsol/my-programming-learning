<?php
$page_title = "Provinces";
$active_page = "provinces";
include "includes/header.php";


// ===========================================
// Database Connection
// ===========================================

include "config/database.php";

// ===========================================
// Home Page Process
// ===========================================

include "process/provinces_process.php";

?>

<section class="section-tight" style="background:var(--paper-raised);border-bottom:1px solid var(--line);">
    <div class="container">
        <div class="eyebrow">All Provinces</div>
        <h1 style="font-size:2.4rem;">Pick a province, start walking.</h1>
        <p class="mb-0" style="max-width:640px;">Every province here runs as its own blog — its own contributors, its own publishing pace, its own personality. Follow the ones you're planning to visit.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="input-group" style="max-width:280px;">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" id="provinceSearch" placeholder="Search provinces..." onkeyup="filterProvinces();">
            </div>
        </div>

        <div class="row g-4" id="provinceGrid">

            <?php while($province = mysqli_fetch_assoc($province_result)){ ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="card-tp province-card">

                            <a
                            href="blog-details.php?blog_id=<?=$province["blog_id"];?>"
                            class="text-decoration-none d-block">

                                <div class="img-wrap">

                                    <img
                                    src="<?=
                                    !empty($province["blog_background_image"])
                                    ?
                                    "assets/images/blogs/".$province["blog_background_image"]
                                    :
                                    "assets/images/blogs/default.jpg";
                                    ?>">

                                    <div class="img-caption">

                                        <h3>

                                            <?=htmlspecialchars($province["blog_title"]);?>

                                        </h3>

                                        <div class="meta">

                                            <?=$province["total_posts"];?> Articles

                                            ·

                                            <?=$province["total_followers"];?> Followers

                                        </div>

                                    </div>

                                </div>

                            </a>

                            <div class="card-body-tp">

                                <a
                                href="blog-details.php?blog_id=<?=$province["blog_id"];?>"
                                class="btn btn-sm btn-outline-teal">

                                    Explore

                                </a>

                                <?php $is_following = in_array($province["blog_id"], $followed_blog_ids); ?>

                                <button
                                class="btn btn-sm <?= $is_following ? "btn-outline-teal" : "btn-ghost"; ?> btn-follow"
                                data-blog-id="<?=$province["blog_id"];?>"
                                onclick="followBlog(this);">

                                    <i class="bi <?= $is_following ? "bi-check-lg" : "bi-plus-lg"; ?>"></i>

                                    <?= $is_following ? "Following" : "Follow"; ?>

                                </button>

                            </div>

                        </div>

                    </div>

            <?php } ?>

        </div>

        <p class="text-center mt-4" id="noResultsMsg" style="display:none;color:var(--ink-soft);">No provinces match your search.</p>
    </div>
</section>

<?php include "includes/footer.php"; ?>

<script src="ajax/provinces_ajax.js"></script>

</body>
</html>

