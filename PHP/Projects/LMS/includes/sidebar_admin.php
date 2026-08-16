<?php
$current = basename($_SERVER["PHP_SELF"]);
function nav_active($file, $current) {
    return $file == $current ? "active" : "";
}
?>
<div class="sidebar" id="sidebar">
    <a href="dashboard.php" class="brand"><i class="fa-solid fa-graduation-cap"></i> LMS Admin</a>
    <nav class="nav flex-column">
        <a class="nav-link <?php echo nav_active('dashboard.php', $current); ?>" href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
        <a class="nav-link <?php echo nav_active('teachers.php', $current); ?>" href="teachers.php"><i class="fa-solid fa-chalkboard-user"></i> Teachers</a>
        <a class="nav-link <?php echo nav_active('students.php', $current); ?>" href="students.php"><i class="fa-solid fa-user-graduate"></i> Students</a>
        <a class="nav-link <?php echo nav_active('courses.php', $current); ?>" href="courses.php"><i class="fa-solid fa-book"></i> Courses</a>
        <a class="nav-link <?php echo nav_active('enrollments.php', $current); ?>" href="enrollments.php"><i class="fa-solid fa-user-plus"></i> Enrollments</a>
        <a class="nav-link <?php echo nav_active('projects.php', $current); ?>" href="projects.php"><i class="fa-solid fa-diagram-project"></i> Projects &amp; Groups</a>
        <a class="nav-link <?php echo nav_active('notifications.php', $current); ?>" href="notifications.php"><i class="fa-solid fa-bell"></i> Notifications</a>
        <a class="nav-link <?php echo nav_active('profile.php', $current); ?>" href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
        <a class="nav-link" href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </nav>
</div>
