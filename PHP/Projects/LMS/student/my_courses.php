<?php
require_once "../includes/functions.php";
require_role("student");

$page_title = "My Courses";
$asset_path = "../";
$student_id = $_SESSION["user_id"];

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="row g-3">
    <?php
    $query = "SELECT course.*, user.first_name, user.last_name FROM course_enrollment
              INNER JOIN course ON course_enrollment.course_id = course.course_id
              INNER JOIN user ON course.teacher_id = user.user_id
              WHERE course_enrollment.student_id = ? ORDER BY course_enrollment.enrollment_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="col-12"><div class="empty-state"><i class="fa-solid fa-book"></i>You are not enrolled in any course yet. <a href="browse_courses.php">Browse courses</a></div></div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="col-md-6 col-lg-4"><div class="course-card">';
        echo '<div class="course-banner">' . htmlspecialchars($row["course_title"]) . '</div>';
        echo '<div class="card-body"><p class="small text-muted mb-1"><i class="fa-solid fa-chalkboard-user"></i> ' . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . '</p>';
        echo '<a href="course_view.php?course_id=' . $row["course_id"] . '" class="btn btn-sm btn-primary">Open Course</a>';
        echo '</div></div></div>';
    }
    ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
