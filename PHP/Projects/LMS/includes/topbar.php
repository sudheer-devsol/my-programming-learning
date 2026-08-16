<?php
$unread_count = get_unread_notification_count($conn, $_SESSION["user_id"]);
?>
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light sidebar-toggle" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
        <h5 class="mb-0"><?php echo isset($page_title) ? $page_title : "Dashboard"; ?></h5>
    </div>
    <div class="d-flex align-items-center gap-3">
        <a href="notifications.php" class="text-dark position-relative text-decoration-none">
            <i class="fa-solid fa-bell fs-5"></i>
            <?php if ($unread_count > 0) { ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" style="font-size:.6rem;"><?php echo $unread_count; ?></span>
            <?php } ?>
        </a>
        <div class="d-flex align-items-center gap-2">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;">
                <?php echo strtoupper(substr($_SESSION["first_name"], 0, 1)); ?>
            </div>
            <div class="d-none d-sm-block">
                <div class="fw-semibold" style="font-size:.9rem;"><?php echo htmlspecialchars($_SESSION["first_name"] . " " . $_SESSION["last_name"]); ?></div>
                <div class="text-muted badge-role" style="font-size:.75rem;"><?php echo $_SESSION["role"]; ?></div>
            </div>
        </div>
    </div>
</div>
