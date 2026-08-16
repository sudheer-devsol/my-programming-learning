<?php
require_once "../includes/functions.php";
require_role("admin");

$page_title = "Course Enrollments";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_admin.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">All Enrollments</h5>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Student</th><th>Course</th><th>Teacher</th><th>Enrolled On</th><th>Action</th></tr></thead>
            <tbody>
            <?php
            $query = "SELECT course_enrollment.enrollment_id, course_enrollment.enrolled_at,
                             student.first_name AS s_first, student.last_name AS s_last,
                             course.course_title, teacher.first_name AS t_first, teacher.last_name AS t_last
                      FROM course_enrollment
                      INNER JOIN user AS student ON course_enrollment.student_id = student.user_id
                      INNER JOIN course ON course_enrollment.course_id = course.course_id
                      INNER JOIN user AS teacher ON course.teacher_id = teacher.user_id
                      ORDER BY course_enrollment.enrollment_id DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-user-plus"></i>No enrollments yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='erow" . $row["enrollment_id"] . "'>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["s_first"] . " " . $row["s_last"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["course_title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["t_first"] . " " . $row["t_last"]) . "</td>";
                echo "<td>" . format_date($row["enrolled_at"]) . "</td>";
                echo '<td><button class="btn btn-sm btn-outline-danger" onclick="removeEnrollment(' . $row["enrollment_id"] . ')"><i class="fa-solid fa-trash"></i></button></td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
<script>
function removeEnrollment(id) {
    if (!confirmDelete("Remove this enrollment?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/course_enroll_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                document.getElementById("erow" + id).remove();
            }
        }
    };
    xhr.send("action=remove_enrollment&enrollment_id=" + id);
}
</script>
