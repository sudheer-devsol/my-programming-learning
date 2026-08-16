<?php
require_once "../includes/functions.php";
require_role("admin");

$page_title = "Projects & Groups";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_admin.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">All Projects</h5>

    <?php
    $query = "SELECT project.*, course.course_title FROM project INNER JOIN course ON project.course_id = course.course_id ORDER BY project.project_id DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-diagram-project"></i>No projects created yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="stat-card mb-3" style="border-left-color:#6a3ee8;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1"><?php echo htmlspecialchars($row["project_title"]); ?></h6>
                    <p class="text-muted small mb-1">Course: <?php echo htmlspecialchars($row["course_title"]); ?></p>
                    <p class="small mb-0"><?php echo htmlspecialchars($row["project_description"]); ?></p>
                </div>
            </div>
            <hr>
            <div class="row g-2">
                <?php
                $gq = mysqli_prepare($conn, "SELECT * FROM project_group WHERE project_id = ?");
                mysqli_stmt_bind_param($gq, "i", $row["project_id"]);
                mysqli_stmt_execute($gq);
                $groups = mysqli_stmt_get_result($gq);

                if (mysqli_num_rows($groups) == 0) {
                    echo '<div class="col-12 text-muted small">No groups created for this project yet.</div>';
                }

                while ($g = mysqli_fetch_assoc($groups)) {
                    echo '<div class="col-md-4"><div class="border rounded p-2"><strong>' . htmlspecialchars($g["group_name"]) . '</strong><ul class="mb-0 mt-1 small">';
                    $mq = mysqli_prepare($conn, "SELECT user.first_name, user.last_name FROM project_group_member INNER JOIN user ON project_group_member.student_id = user.user_id WHERE group_id = ?");
                    mysqli_stmt_bind_param($mq, "i", $g["group_id"]);
                    mysqli_stmt_execute($mq);
                    $members = mysqli_stmt_get_result($mq);
                    if (mysqli_num_rows($members) == 0) {
                        echo "<li class='text-muted'>No members yet</li>";
                    }
                    while ($m = mysqli_fetch_assoc($members)) {
                        echo "<li>" . htmlspecialchars($m["first_name"] . " " . $m["last_name"]) . "</li>";
                    }
                    echo '</ul></div></div>';
                }
                ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<?php include "../includes/foot.php"; ?>
