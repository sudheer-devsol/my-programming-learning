<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$query = "SELECT * FROM course WHERE course_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$course = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$page_title = $course["course_title"];
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="stat-card mb-4" style="border-left-color:#6a3ee8;">
        <h5 class="mb-1"><?php echo htmlspecialchars($course["course_title"]); ?></h5>
        <p class="text-muted mb-0"><?php echo htmlspecialchars($course["course_description"]); ?></p>
    </div>

    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="lectures.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-video fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Lectures</p></div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="materials.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-file-arrow-down fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Materials</p></div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="assignments.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-file-pen fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Assignments</p></div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="projects.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-diagram-project fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Projects</p></div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="students.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-user-graduate fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Students</p></div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="chat_course.php?course_id=<?php echo $course_id; ?>" class="text-decoration-none">
                <div class="stat-card text-center"><i class="fa-solid fa-comments fs-3 text-primary mb-2"></i><p class="mb-0 fw-semibold">Course Chat</p></div>
            </a>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
