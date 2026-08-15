<?php
if(!isset($active)){ $active = ""; }
?>
<aside class="dash-sidebar">
    <div class="side-user">
         <img src="../assets/images/users/<?= $_SESSION["user_image"]; ?>" alt="Profile Image">
        <div>
            <div class="name">
                <?= $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>
            </div>
            <div class="role">Administrator</div>
        </div>
    </div>
    <nav class="dash-nav">
        <div class="nav-section-label">Overview</div>
        <a href="dashboard.php" class="<?= $active=='dashboard'?'active':'' ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a>

        <div class="nav-section-label">Content</div>
        <a href="blogs.php" class="<?= $active=='blogs'?'active':'' ?>"><i class="bi bi-signpost-split"></i> Blogs (Provinces)</a>
        <a href="posts.php" class="<?= $active=='posts'?'active':'' ?>"><i class="bi bi-file-earmark-text"></i> Posts</a>
        <a href="categories.php" class="<?= $active=='categories'?'active':'' ?>"><i class="bi bi-tags"></i> Categories</a>

        <div class="nav-section-label">Community</div>
        <a href="users.php" class="<?= $active=='users'?'active':'' ?>"><i class="bi bi-people"></i> Users</a>
        <a href="comments.php" class="<?= $active=='comments'?'active':'' ?>"><i class="bi bi-chat-left-text"></i> Comments</a>
        <a href="feedback.php" class="<?= $active=='feedback'?'active':'' ?>"><i class="bi bi-envelope-open"></i> Feedback</a>

        <div class="nav-section-label">Settings</div>
        <a href="theme.php" class="<?= $active=='theme'?'active':'' ?>"><i class="bi bi-palette"></i> Theme Management</a>
        <a href="../logout.php" id="sidebarLogout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</aside>
