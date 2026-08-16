<?php
require_once "../includes/functions.php";
require_role("student");

$student_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_student_enrolled($conn, $student_id, $course_id)) {
    header("Location: browse_courses.php");
    exit;
}

$page_title = "Projects";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">Course Projects</h5>

    <?php
    $query = "SELECT * FROM project WHERE course_id = ? ORDER BY project_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-diagram-project"></i>No projects posted for this course yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="stat-card mb-3">';
        echo '<h6 class="mb-1">' . htmlspecialchars($row["project_title"]) . '</h6>';
        echo '<p class="small mb-2">' . nl2br(htmlspecialchars($row["project_description"])) . '</p>';

        // find the student's group for this project, if any
        $gq = mysqli_prepare($conn, "SELECT project_group.group_id, project_group.group_name FROM project_group_member
                                      INNER JOIN project_group ON project_group_member.group_id = project_group.group_id
                                      WHERE project_group.project_id = ? AND project_group_member.student_id = ?");
        mysqli_stmt_bind_param($gq, "ii", $row["project_id"], $student_id);
        mysqli_stmt_execute($gq);
        $group = mysqli_fetch_assoc(mysqli_stmt_get_result($gq));

        if ($group) {
            echo '<span class="badge bg-primary mb-2">Your Group: ' . htmlspecialchars($group["group_name"]) . '</span><br>';

            // list group members
            $mq = mysqli_prepare($conn, "SELECT user.first_name, user.last_name FROM project_group_member INNER JOIN user ON project_group_member.student_id = user.user_id WHERE group_id = ?");
            mysqli_stmt_bind_param($mq, "i", $group["group_id"]);
            mysqli_stmt_execute($mq);
            $members = mysqli_stmt_get_result($mq);
            echo '<p class="small mb-2"><strong>Members:</strong> ';
            $names = array();
            while ($m = mysqli_fetch_assoc($members)) {
                $names[] = htmlspecialchars($m["first_name"] . " " . $m["last_name"]);
            }
            echo implode(", ", $names) . '</p>';

            echo '<a href="chat_project.php?group_id=' . $group["group_id"] . '" class="btn btn-sm btn-primary"><i class="fa-solid fa-comments"></i> Open Group Chat</a>';
        } else {
            echo '<p class="text-muted small mb-0">You have not been assigned to a group for this project yet.</p>';
        }
        echo '</div>';
    }
    ?>
</div>
<?php include "../includes/foot.php"; ?>
