<?php
require_once "../includes/functions.php";
require_role("student");

$page_title = "Notifications";
$asset_path = "../";

// mark all as read when the page is opened
$mark = "UPDATE notification SET is_read = 1 WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $mark);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">Your Notifications</h5>

    <div class="stat-card">
    <?php
    $query = "SELECT * FROM notification WHERE user_id = ? ORDER BY notification_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-bell"></i>No notifications yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="d-flex justify-content-between border-bottom py-2">';
        echo '<div><i class="fa-solid fa-circle-info text-primary me-2"></i>' . htmlspecialchars($row["message"]) . '</div>';
        echo '<small class="text-muted">' . format_date($row["created_at"]) . '</small>';
        echo '</div>';
    }
    ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
