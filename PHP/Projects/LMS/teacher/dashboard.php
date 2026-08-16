<?php
require_once "../includes/functions.php";
require_role("teacher");

$page_title = "Dashboard";
$asset_path = "../";
$teacher_id = $_SESSION["user_id"];

$cq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM course WHERE teacher_id = ?");
mysqli_stmt_bind_param($cq, "i", $teacher_id);
mysqli_stmt_execute($cq);
$total_courses = mysqli_fetch_assoc(mysqli_stmt_get_result($cq))["total"];

$sq = mysqli_prepare($conn, "SELECT COUNT(DISTINCT course_enrollment.student_id) AS total FROM course_enrollment INNER JOIN course ON course_enrollment.course_id = course.course_id WHERE course.teacher_id = ?");
mysqli_stmt_bind_param($sq, "i", $teacher_id);
mysqli_stmt_execute($sq);
$total_students = mysqli_fetch_assoc(mysqli_stmt_get_result($sq))["total"];

$aq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM assignment INNER JOIN course ON assignment.course_id = course.course_id WHERE course.teacher_id = ?");
mysqli_stmt_bind_param($aq, "i", $teacher_id);
mysqli_stmt_execute($aq);
$total_assignments = mysqli_fetch_assoc(mysqli_stmt_get_result($aq))["total"];

$pq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM project INNER JOIN course ON project.course_id = course.course_id WHERE course.teacher_id = ?");
mysqli_stmt_bind_param($pq, "i", $teacher_id);
mysqli_stmt_execute($pq);
$total_projects = mysqli_fetch_assoc(mysqli_stmt_get_result($pq))["total"];

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="stat-card"><h3><?php echo $total_courses; ?></h3><p>My Courses</p></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><h3><?php echo $total_students; ?></h3><p>Students</p></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><h3><?php echo $total_assignments; ?></h3><p>Assignments</p></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><h3><?php echo $total_projects; ?></h3><p>Projects</p></div></div>
    </div>

    <h6 class="section-title">My Courses</h6>
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
        echo '<div class="col-md-6 col-lg-4"><div class="course-card">';
        echo '<div class="course-banner">' . htmlspecialchars($row["course_title"]) . '</div>';
        echo '<div class="card-body"><p class="small text-muted">' . htmlspecialchars(mb_strimwidth($row["course_description"], 0, 80, "...")) . '</p>';
        echo '<a href="course_view.php?course_id=' . $row["course_id"] . '" class="btn btn-sm btn-primary">Manage Course</a>';
        echo '</div></div></div>';
    }
    ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
