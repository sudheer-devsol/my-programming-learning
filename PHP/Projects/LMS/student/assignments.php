<?php
require_once "../includes/functions.php";
require_role("student");

$student_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if ($course_id > 0 && !is_student_enrolled($conn, $student_id, $course_id)) {
    header("Location: browse_courses.php");
    exit;
}

$page_title = "Assignments";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <?php if (isset($_GET["msg"]) && $_GET["msg"] == "submitted") { ?>
        <div class="alert alert-success">Assignment Submitted Successfully</div>
    <?php } elseif (isset($_GET["error"])) { ?>
        <div class="alert alert-danger">Could not submit assignment. Please check the file and try again.</div>
    <?php } ?>

    <h5 class="section-title">Assignments</h5>

    <?php
    // Show assignments across all enrolled courses, or a single course if course_id is given
    if ($course_id > 0) {
        $query = "SELECT assignment.*, course.course_title FROM assignment INNER JOIN course ON assignment.course_id = course.course_id WHERE assignment.course_id = ? ORDER BY assignment.deadline ASC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $course_id);
    } else {
        $query = "SELECT assignment.*, course.course_title FROM assignment
                  INNER JOIN course ON assignment.course_id = course.course_id
                  INNER JOIN course_enrollment ON course.course_id = course_enrollment.course_id
                  WHERE course_enrollment.student_id = ? ORDER BY assignment.deadline ASC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $student_id);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-file-pen"></i>No assignments yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {

        $subq = mysqli_prepare($conn, "SELECT * FROM assignment_submission WHERE assignment_id = ? AND student_id = ?");
        mysqli_stmt_bind_param($subq, "ii", $row["assignment_id"], $student_id);
        mysqli_stmt_execute($subq);
        $submission = mysqli_fetch_assoc(mysqli_stmt_get_result($subq));

        $is_past_due = strtotime($row["deadline"]) < time();

        echo '<div class="stat-card mb-3">';
        echo '<div class="d-flex justify-content-between align-items-start">';
        echo '<div><h6 class="mb-1">' . htmlspecialchars($row["title"]) . '</h6>';
        echo '<p class="small text-muted mb-1">Course: ' . htmlspecialchars($row["course_title"]) . '</p>';
        echo '<p class="small mb-1">' . nl2br(htmlspecialchars($row["description"])) . '</p>';
        echo '<p class="small mb-2"><i class="fa-solid fa-clock"></i> Deadline: ' . format_date($row["deadline"]) . ($is_past_due ? ' <span class="badge bg-danger">Past Due</span>' : '') . '</p>';

        if ($submission) {
            echo '<span class="badge bg-success mb-2"><i class="fa-solid fa-check"></i> Submitted on ' . format_date($submission["submitted_at"]) . '</span><br>';
            echo '<a href="../' . htmlspecialchars($submission["file_path"]) . '" target="_blank" class="small">View your submission</a><br>';
            if ($submission["marks"] !== null) {
                echo '<p class="small mt-2 mb-0"><strong>Marks:</strong> ' . htmlspecialchars($submission["marks"]) . '/100</p>';
            }
            if ($submission["feedback"]) {
                echo '<p class="small mb-0"><strong>Feedback:</strong> ' . htmlspecialchars($submission["feedback"]) . '</p>';
            }
        }
        echo '</div></div>';

        echo '<form action="../process/submission_process.php" method="POST" enctype="multipart/form-data" class="mt-2 d-flex gap-2">';
        echo '<input type="hidden" name="action" value="submit_assignment">';
        echo '<input type="hidden" name="assignment_id" value="' . $row["assignment_id"] . '">';
        echo '<input type="file" class="form-control form-control-sm" name="submission_file" required>';
        echo '<button type="submit" class="btn btn-sm btn-primary">' . ($submission ? "Resubmit" : "Submit") . '</button>';
        echo '</form>';

        echo '</div>';
    }
    ?>
</div>
<?php include "../includes/foot.php"; ?>
