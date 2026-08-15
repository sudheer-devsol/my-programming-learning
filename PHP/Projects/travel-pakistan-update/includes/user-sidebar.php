<?php
/* Expects $active (string) set before include to highlight current item. */
if(!isset($active)){ $active = ""; }
?>
<aside class="dash-sidebar">
    <div class="side-user">
        <!-- <img src="https://i.pravatar.cc/80?img=32" alt=""> -->
         <img src="../assets/images/users/<?= !empty($_SESSION["user_image"]) ? $_SESSION["user_image"] : "default-user.png"; ?>" alt="Profile Image">
        <div>
            <div class="name">
                <?= $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>
            </div>
            <div class="role">Traveler</div>
        </div>
    </div>
    <nav class="dash-nav">
        <div class="nav-section-label">Overview</div>
        <a href="dashboard.php" class="<?= $active=='dashboard'?'active':'' ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a href="my-follows.php" class="<?= $active=='follows'?'active':'' ?>"><i class="bi bi-heart"></i> Followed Provinces</a>

        <div class="nav-section-label">Browse</div>
        <a href="../provinces.php"><i class="bi bi-signpost-split"></i> Provinces</a>
        <a href="../posts.php"><i class="bi bi-file-earmark-text"></i> All Posts</a>
        <a href="../categories.php"><i class="bi bi-tags"></i> Categories</a>

        <div class="nav-section-label">Account</div>
        <a href="profile.php" class="<?= $active=='profile'?'active':'' ?>"><i class="bi bi-person"></i> My Profile</a>
        <a href="theme.php" class="<?= $active=='theme'?'active':'' ?>"><i class="bi bi-palette"></i> Theme Settings</a>
        <a href="../contact.php"><i class="bi bi-chat-square-text"></i> Submit Feedback</a>
        <a href="../logout.php" id="sidebarLogout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </nav>
</aside>
