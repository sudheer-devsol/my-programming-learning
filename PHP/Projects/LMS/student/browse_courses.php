<?php
require_once "../includes/functions.php";
require_role("student");

$page_title = "Browse Courses";
$asset_path = "../";
$student_id = $_SESSION["user_id"];

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div id="formMessage"></div>

    <div class="row g-3">
    <?php
    $query = "SELECT course.*, user.first_name, user.last_name FROM course INNER JOIN user ON course.teacher_id = user.user_id WHERE course.status = 'active' ORDER BY course.course_id DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="col-12"><div class="empty-state"><i class="fa-solid fa-book"></i>No courses available right now.</div></div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $enrolled = is_student_enrolled($conn, $student_id, $row["course_id"]);

        $eq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($eq, "i", $row["course_id"]);
        mysqli_stmt_execute($eq);
        $ecount = mysqli_fetch_assoc(mysqli_stmt_get_result($eq))["total"];

        echo '<div class="col-md-6 col-lg-4"><div class="course-card" id="course' . $row["course_id"] . '">';
        echo '<div class="course-banner">' . htmlspecialchars($row["course_title"]) . '</div>';
        echo '<div class="card-body">';
        echo '<p class="small text-muted mb-1"><i class="fa-solid fa-chalkboard-user"></i> ' . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . '</p>';
        echo '<p class="small mb-2">' . htmlspecialchars(mb_strimwidth($row["course_description"], 0, 90, "...")) . '</p>';
        echo '<p class="small mb-2"><i class="fa-solid fa-user-graduate"></i> ' . $ecount . ' students enrolled</p>';

        if ($enrolled) {
            echo '<button class="btn btn-sm btn-success" disabled><i class="fa-solid fa-check"></i> Enrolled</button>';
        } else {
            echo '<button class="btn btn-sm btn-primary" onclick="enrollCourse(' . $row["course_id"] . ')">Enroll Now</button>';
        }
        echo '</div></div></div>';
    }
    ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
<script>
function enrollCourse(courseId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/course_enroll_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                showMessage("formMessage", "Course Enrolled Successfully", "success");
                setTimeout(function () { window.location.reload(); }, 800);
            } else if (response == "duplicate") {
                showMessage("formMessage", "You are already enrolled in this course.", "error");
            } else {
                showMessage("formMessage", "Something went wrong. Please try again.", "error");
            }
        }
    };
    xhr.send("action=enroll&course_id=" + courseId);
}
</script>
