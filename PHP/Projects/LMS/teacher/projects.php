<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$page_title = "Projects";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">Group Projects</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal"><i class="fa-solid fa-plus"></i> Create Project</button>
    </div>

    <div id="projectList">
    <?php
    $query = "SELECT * FROM project WHERE course_id = ? ORDER BY project_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-diagram-project"></i>No projects created yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $gq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM project_group WHERE project_id = ?");
        mysqli_stmt_bind_param($gq, "i", $row["project_id"]);
        mysqli_stmt_execute($gq);
        $gcount = mysqli_fetch_assoc(mysqli_stmt_get_result($gq))["total"];

        echo '<div class="stat-card mb-3" id="proj' . $row["project_id"] . '">';
        echo '<div class="d-flex justify-content-between align-items-start">';
        echo '<div><h6 class="mb-1">' . htmlspecialchars($row["project_title"]) . '</h6>';
        echo '<p class="small text-muted mb-2">' . htmlspecialchars($row["project_description"]) . '</p>';
        echo '<span class="badge bg-info">' . $gcount . ' group(s)</span></div>';
        echo '<div><a href="groups.php?project_id=' . $row["project_id"] . '" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-users"></i> Manage Groups</a> ';
        echo '<button class="btn btn-sm btn-outline-danger" onclick="deleteProject(' . $row["project_id"] . ')"><i class="fa-solid fa-trash"></i></button></div>';
        echo '</div></div>';
    }
    ?>
    </div>
</div>

<div class="modal fade" id="addProjectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Create Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addProjectForm" onsubmit="return addProject(event)">
      <div class="modal-body">
        <div id="modalMessage"></div>
        <div class="mb-3"><label class="form-label">Project Title</label><input type="text" class="form-control" id="p_title" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" id="p_description" rows="3"></textarea></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include "../includes/foot.php"; ?>
<script>
var courseId = <?php echo $course_id; ?>;

function addProject(event) {
    event.preventDefault();
    var title = document.getElementById("p_title").value.trim();
    var description = document.getElementById("p_description").value.trim();

    if (title == "") {
        showMessage("modalMessage", "Project title is required.", "error");
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/project_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                window.location.reload();
            } else {
                showMessage("modalMessage", "Something went wrong.", "error");
            }
        }
    };
    xhr.send("action=add_project&course_id=" + courseId + "&project_title=" + encodeURIComponent(title) + "&project_description=" + encodeURIComponent(description));
    return false;
}

function deleteProject(projectId) {
    if (!confirmDelete("Delete this project and all its groups?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/project_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                document.getElementById("proj" + projectId).remove();
            } else {
                alert("Could not delete project.");
            }
        }
    };
    xhr.send("action=delete_project&project_id=" + projectId);
}
</script>
