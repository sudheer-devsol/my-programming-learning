<?php
require_once "../includes/functions.php";
require_role("admin");

$page_title = "Dashboard";
$asset_path = "../";

function count_rows($conn, $query) {
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row["total"];
}

$total_students = count_rows($conn, "SELECT COUNT(*) AS total FROM user WHERE role = 'student'");
$total_teachers = count_rows($conn, "SELECT COUNT(*) AS total FROM user WHERE role = 'teacher'");
$total_courses = count_rows($conn, "SELECT COUNT(*) AS total FROM course");
$total_enrollments = count_rows($conn, "SELECT COUNT(*) AS total FROM course_enrollment");
$total_projects = count_rows($conn, "SELECT COUNT(*) AS total FROM project");
$total_assignments = count_rows($conn, "SELECT COUNT(*) AS total FROM assignment");

include "../includes/head.php";
include "../includes/sidebar_admin.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_students; ?></h3><p>Students</p></div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_teachers; ?></h3><p>Teachers</p></div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_courses; ?></h3><p>Courses</p></div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_enrollments; ?></h3><p>Enrollments</p></div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_projects; ?></h3><p>Projects</p></div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="stat-card"><h3><?php echo $total_assignments; ?></h3><p>Assignments</p></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="stat-card" style="border-left-color:#6a3ee8;">
                <h6 class="section-title">Recently Registered Users</h6>
                <table class="table table-sm">
                    <thead><tr><th>Name</th><th>Role</th><th>Joined</th></tr></thead>
                    <tbody>
                    <?php
                    $recent = mysqli_query($conn, "SELECT first_name, last_name, role, created_at FROM user WHERE role != 'admin' ORDER BY user_id DESC LIMIT 6");
                    if (mysqli_num_rows($recent) == 0) {
                        echo '<tr><td colspan="3" class="text-muted">No users yet.</td></tr>';
                    }
                    while ($row = mysqli_fetch_assoc($recent)) {
                        echo "<tr><td>" . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . "</td>";
                        echo '<td><span class="badge bg-secondary badge-role">' . $row["role"] . "</span></td>";
                        echo "<td>" . format_date($row["created_at"]) . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stat-card" style="border-left-color:#2f5fed;">
                <h6 class="section-title">Recently Added Courses</h6>
                <table class="table table-sm">
                    <thead><tr><th>Course</th><th>Teacher</th><th>Created</th></tr></thead>
                    <tbody>
                    <?php
                    $recent_courses = mysqli_query($conn, "SELECT course.course_title, course.created_at, user.first_name, user.last_name FROM course INNER JOIN user ON course.teacher_id = user.user_id ORDER BY course.course_id DESC LIMIT 6");
                    if (mysqli_num_rows($recent_courses) == 0) {
                        echo '<tr><td colspan="3" class="text-muted">No courses yet.</td></tr>';
                    }
                    while ($row = mysqli_fetch_assoc($recent_courses)) {
                        echo "<tr><td>" . htmlspecialchars($row["course_title"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . "</td>";
                        echo "<td>" . format_date($row["created_at"]) . "</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
