<?php

// ===============Include the admin session=========
include "../includes/admin-session.php";

$page_title = "Admin Dashboard";
$dash_role = "admin";
include "../includes/dash-header.php";
$active = "dashboard";

// ===============Database Connection=========
include "../config/database.php";

//==============Total Users============================
$query = " SELECT COUNT(user_id) AS total_users FROM user where user_id != 1";

$result = mysqli_query($conn,$query);
$total_users = mysqli_fetch_assoc($result);


//==================Active Users========================
$query = " SELECT COUNT(user_id) AS active_users FROM user WHERE is_active = 'active'";

$result = mysqli_query($conn,$query);
$active_users = mysqli_fetch_assoc($result);


//==================Pending Users========================
$query = "SELECT COUNT(user_id) AS pending_users FROM user WHERE is_approved = 'pending'";

$result = mysqli_query($conn,$query);
$pending_users = mysqli_fetch_assoc($result);


//================Rejected Users==========================
$query = "SELECT COUNT(user_id) AS rejected_users FROM user WHERE is_approved = 'rejected'";

$result = mysqli_query($conn,$query);
$rejected_users = mysqli_fetch_assoc($result);


//============Total Blogs==============================
$query = "SELECT COUNT(blog_id) AS total_blogs FROM blog";

$result = mysqli_query($conn,$query);
$total_blogs = mysqli_fetch_assoc($result);


//===============Total Posts===========================
$query = "SELECT COUNT(post_id) AS total_posts FROM post";

$result = mysqli_query($conn,$query);
$total_posts = mysqli_fetch_assoc($result);


//====================Total Categories=====================
$query = "SELECT COUNT(category_id) AS total_categories FROM category";

$result = mysqli_query($conn,$query);
$total_categories = mysqli_fetch_assoc($result);


// =============Total Comments=============================
$query = "SELECT COUNT(post_comment_id) AS total_comments FROM post_comment";

$result = mysqli_query($conn,$query);
$total_comments = mysqli_fetch_assoc($result);


// =================Total Feedback=========================
$query = "SELECT COUNT(feedback_id) AS total_feedback FROM user_feedback";

$result = mysqli_query($conn,$query);
$total_feedback = mysqli_fetch_assoc($result);


// =============Pending Users List======================
$query = "SELECT user.user_id, user.first_name, user.last_name, user.email, user.user_image, user.created_at FROM user
WHERE user.is_approved = 'pending'
ORDER BY user.created_at DESC
LIMIT 5";

$pending_result = mysqli_query($conn,$query);


// ===================Recent Feedback=======================
$query = " SELECT feedback_id, user_name, feedback, created_at
FROM user_feedback
ORDER BY created_at DESC
LIMIT 5";

$feedback_result = mysqli_query($conn,$query);

?>

<div class="dash-shell">
<?php include "../includes/admin-sidebar.php"; ?>

<main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Admin Dashboard</h2>
                <p class="mb-0">A snapshot of everything happening on Travel Pakistan.</p>
            </div>
        </div>

    <div class="row g-3 mb-4">
    <!-- ===============Total Users=============== -->
        <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--teal-tint);color:var(--teal);">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-value"><?= $total_users["total_users"]; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <!-- ===============Active Users============ -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#E7F3EA;color:var(--success);">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-value"><?= $active_users["active_users"]; ?></div>
            <div class="stat-label">Active Users</div>
        </div>
    </div>

    <!--=============== Pending Users=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FBF0DC;color:var(--warning);">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="stat-value"><?= $pending_users["pending_users"]; ?></div>
            <div class="stat-label">Pending Users</div>
        </div>
    </div>

    <!-- ===============Rejected Users=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#F8E8E8;color:var(--danger);">
                <i class="bi bi-person-x"></i>
            </div>
            <div class="stat-value"><?= $rejected_users["rejected_users"]; ?></div>
            <div class="stat-label">Rejected Users</div>
        </div>
    </div>

    <!-- ===============Total Blogs=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--brick-tint);color:var(--brick);">
                <i class="bi bi-signpost-split"></i>
            </div>
            <div class="stat-value"><?= $total_blogs["total_blogs"]; ?></div>
            <div class="stat-label">Total Blogs</div>
        </div>
    </div>

    <!-- ===============Total Posts=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--teal-tint);color:var(--teal);">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-value"><?= $total_posts["total_posts"]; ?></div>
            <div class="stat-label">Total Posts</div>
        </div>
    </div>

    <!-- ===============Total Categories=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FBF0DC;color:var(--marigold-dark);">
                <i class="bi bi-tags"></i>
            </div>
            <div class="stat-value"><?= $total_categories["total_categories"]; ?></div>
            <div class="stat-label">Total Categories</div>
        </div>
    </div>

    <!-- ===============Total Comments=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--brick-tint);color:var(--brick);">
                <i class="bi bi-chat-left-text"></i>
            </div>
            <div class="stat-value"><?= $total_comments["total_comments"]; ?></div>
            <div class="stat-label">Total Comments</div>
        </div>
    </div>

    <!-- ===============Total Feedback=============== -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#E7F3EA;color:var(--success);">
                <i class="bi bi-envelope-open"></i>
            </div>
            <div class="stat-value"><?= $total_feedback["total_feedback"]; ?></div>
            <div class="stat-label">Total Feedback</div>
        </div>
    </div>

</div>

<div class="row g-4">   
    <!--=============== Pending Users=============== -->
    <div class="col-lg-7">
        <div class="panel-tp">
            <div class="panel-head">
                <strong>Pending Approvals</strong>
                <a href="users.php" class="link-all" style="font-size:.85rem;">Manage Users</a>
            </div>
            <div class="table-responsive">
                <table class="table table-tp mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    while($row = mysqli_fetch_assoc($pending_result)){
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="../assets/images/users/<?= htmlspecialchars($row["user_image"]); ?>"
                                style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                <?= htmlspecialchars($row["first_name"]); ?>
                                <?= htmlspecialchars($row["last_name"]); ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row["email"]); ?></td>
                        <td><?= date("d M Y",strtotime($row["created_at"])); ?></td>
                        <td class="text-end">
                            <a href="users.php" class="btn btn-sm btn-teal">Manage</a>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===============Recent Feedback=============== -->
    <div class="col-lg-5">
        <div class="panel-tp">
            <div class="panel-head">
                <strong>Recent Feedback</strong>
            </div>
            <div class="panel-body d-flex flex-column gap-3">
                <?php
                while($row = mysqli_fetch_assoc($feedback_result)){
                ?>
                <div>
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:.9rem;">
                            <?= htmlspecialchars($row["user_name"]); ?>
                        </strong>
                        <span class="post-meta" style="font-size:.78rem;">
                            <?= date("d M",strtotime($row["created_at"])); ?>
                        </span>
                    </div>
                    <p class="mb-0" style="font-size:.88rem;"> <?= htmlspecialchars($row["feedback"]); ?> </p>
                </div>
                <?php
                }
                ?>
                <a href="feedback.php" class="link-all mt-2" style="font-size:.85rem;">
                    View All Feedback
                </a>
            </div>
        </div>
    </div>
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/logout_ajax.js"></script>

</body>
</html>
