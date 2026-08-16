<?php
require_once "../includes/functions.php";
require_role("teacher");

$page_title = "My Courses";
$asset_path = "../";
$teacher_id = $_SESSION["user_id"];

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="row g-3">
    <?php
    $query = "SELECT * FROM course WHERE teacher_id = ? ORDER BY course_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="col-12"><div class="empty-state"><i class="fa-solid fa-book"></i>No courses assigned to you yet. Contact the admin.</div></div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $eq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($eq, "i", $row["course_id"]);
        mysqli_stmt_execute($eq);
        $ecount = mysqli_fetch_assoc(mysqli_stmt_get_result($eq))["total"];

        echo '<div class="col-md-6 col-lg-4"><div class="course-card">';
        echo '<div class="course-banner">' . htmlspecialchars($row["course_title"]) . '</div>';
        echo '<div class="card-body">';
        echo '<p class="small text-muted mb-1">' . htmlspecialchars(mb_strimwidth($row["course_description"], 0, 90, "...")) . '</p>';
        echo '<p class="small mb-2"><i class="fa-solid fa-user-graduate"></i> ' . $ecount . ' enrolled</p>';
        echo '<span class="badge bg-' . ($row["status"] == "active" ? "success" : "secondary") . ' mb-2">' . $row["status"] . '</span><br>';
        echo '<a href="course_view.php?course_id=' . $row["course_id"] . '" class="btn btn-sm btn-primary mt-1">Manage Course</a>';
        echo '</div></div></div>';
    }
    ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
