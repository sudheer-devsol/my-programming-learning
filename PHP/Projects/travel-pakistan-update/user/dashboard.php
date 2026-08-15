<?php

// ===========Include User Session================================
include "../includes/user-session.php";

// ===========Page Information================================
$page_title = "Dashboard";
$dash_role  = "user";
$active     = "dashboard";

// =================Header==========================
include "../includes/dash-header.php";

// ==========Dashboard Process================
include "../process/user_dashboard_process.php";

?>

<div class="dash-shell">

    <?php include "../includes/user-sidebar.php"; ?>

    <main class="dash-main">

        <div class="dash-topbar">

            <div>

                <h2 class="mb-1" style="font-size:1.6rem;">
                    Welcome back,
                    <?= htmlspecialchars($_SESSION["first_name"]); ?>
                    <?= htmlspecialchars($_SESSION["last_name"]); ?>
                </h2>

                <p class="mb-0">
                    Here's what's new from the blogs you follow.
                </p>

            </div>

            <a href="../provinces.php" class="btn btn-teal btn-sm">
                <i class="bi bi-plus-lg"></i>
                Follow More Blogs
            </a>

        </div>

        <!-- =====================Dashboard Cards===================== -->

        <div class="row g-3 mb-4">

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--teal-tint);color:var(--teal);">
                        <i class="bi bi-heart"></i>
                    </div>

                    <div class="stat-value">
                        <?= $total_followed_blogs["total_followed_blogs"]; ?>
                    </div>

                    <div class="stat-label">
                        Blogs Followed
                    </div>
                </div>
            </div>

            <!-- Comments Posted -->

            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FBF0DC;color:var(--marigold-dark);">
                        <i class="bi bi-chat-left-text"></i>
                    </div>

                    <div class="stat-value">
                        <?= $total_comments["total_comments"]; ?>
                    </div>

                    <div class="stat-label">
                        Comments Posted
                    </div>
                </div>
            </div>



            <!-- Posts From Follows -->

            <div class="col-lg-3 col-md-6">

                <div class="stat-card">
                    <div class="stat-icon" style="background:var(--brick-tint);color:var(--brick);">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>

                    <div class="stat-value">
                        <?= $total_posts["total_posts"]; ?>
                    </div>

                    <div class="stat-label">
                        Posts From Follows
                    </div>
                </div>
            </div>



            <!-- =====================Account Status===================== -->

            <div class="col-lg-3 col-md-6">

                <div class="stat-card">
                    <div class="stat-icon" style="background:#E7F3EA;color:var(--success);">
                        <i class="bi bi-person-check"></i>
                    </div>

                    <div class="stat-value">
                        <?= htmlspecialchars($account_status); ?>
                    </div>

                    <div class="stat-label">
                        Account Status
                    </div>
                </div>
            </div>
        </div>



        <div class="row g-4">

            <!-- =====================Latest Posts===================== -->

            <div class="col-lg-8">

                <div class="panel-tp">
                    <div class="panel-head">
                        <strong>
                            Latest From Your Follows
                        </strong>

                        <a href="my-follows.php" class="link-all" style="font-size:.85rem;">
                            See All
                        </a>

                    </div>

                    <div class="panel-body d-flex flex-column gap-3">

                        <?php
                        while($row = mysqli_fetch_assoc($latest_posts_result)){
                        ?>
                        <a  href="../post-details.php?post_id=<?= $row["post_id"]; ?>" class="d-flex gap-3 text-decoration-none">
                            <img  src="../assets/images/posts/<?= htmlspecialchars($row["featured_image"]); ?>"
                            style="width:110px;height:80px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0;">

                            <div>
                                <span class="badge-stamp mb-1">
                                    <?= htmlspecialchars($row["blog_title"]); ?>
                                </span>

                                <h4 style="font-size:1rem;margin:4px 0;">
                                    <?= htmlspecialchars($row["post_title"]); ?>
                                </h4>

                                <div class="post-meta" style="font-size:.8rem;">
                                    <span>
                                        <?= date("d M Y",strtotime($row["created_at"])); ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- =====================Blogs Followed =====================-->

            <div class="col-lg-4">

                <div class="panel-tp">
                    <div class="panel-head">
                        <strong> Blogs You Follow </strong>
                    </div>

                    <div class="panel-body d-flex flex-column gap-3">
                        <?php
                        while($row = mysqli_fetch_assoc($followed_blogs_result)){

                        ?>

                        <div class="d-flex justify-content-between align-items-center">

                            <div class="d-flex gap-2 align-items-center">

                                <img  src="../assets/images/blogs/<?= htmlspecialchars($row["blog_background_image"]); ?>"
                                style="width:38px;height:38px;border-radius:50%;object-fit:cover;">

                                <strong style="font-size:.9rem;">
                                    <?= htmlspecialchars($row["blog_title"]); ?>
                                </strong>
                            </div>

                            <span class="post-meta" style="font-size:.78rem;">
                                <?= $row["total_posts"]; ?> Posts
                            </span>
                        </div>
                        <?php

                        }

                        ?>

                       <a href="../provinces.php" class="btn btn-outline-teal btn-sm w-100 mt-2">
                            Follow More Blogs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/user_dashboard_ajax.js"></script>