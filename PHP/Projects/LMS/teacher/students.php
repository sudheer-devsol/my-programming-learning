<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$page_title = "Course Students";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">Enrolled Students</h5>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Enrolled On</th></tr></thead>
            <tbody>
            <?php
            $query = "SELECT user.first_name, user.last_name, user.email, user.phone, course_enrollment.enrolled_at
                      FROM course_enrollment INNER JOIN user ON course_enrollment.student_id = user.user_id
                      WHERE course_enrollment.course_id = ? ORDER BY course_enrollment.enrollment_id DESC";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $course_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-user-graduate"></i>No students enrolled yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
                echo "<td>" . format_date($row["enrolled_at"]) . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
