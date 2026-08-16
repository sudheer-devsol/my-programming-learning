<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$page_title = "Assignments";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">Assignments</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal"><i class="fa-solid fa-plus"></i> Create Assignment</button>
    </div>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Title</th><th>Deadline</th><th>Submissions</th><th>Actions</th></tr></thead>
            <tbody>
            <?php
            $query = "SELECT * FROM assignment WHERE course_id = ? ORDER BY assignment_id DESC";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $course_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-file-pen"></i>No assignments created yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $subq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM assignment_submission WHERE assignment_id = ?");
                mysqli_stmt_bind_param($subq, "i", $row["assignment_id"]);
                mysqli_stmt_execute($subq);
                $subcount = mysqli_fetch_assoc(mysqli_stmt_get_result($subq))["total"];

                echo "<tr id='assign" . $row["assignment_id"] . "'>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . format_date($row["deadline"]) . "</td>";
                echo "<td><span class='badge bg-info'>" . $subcount . "</span></td>";
                echo '<td><a href="submissions.php?assignment_id=' . $row["assignment_id"] . '" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i> View</a> ';
                echo '<button class="btn btn-sm btn-outline-danger" onclick="deleteAssignment(' . $row["assignment_id"] . ')"><i class="fa-solid fa-trash"></i></button></td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addAssignmentModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Create Assignment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addAssignmentForm" onsubmit="return addAssignment(event)">
      <div class="modal-body">
        <div id="modalMessage"></div>
        <div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control" id="a_title" required></div>
        <div class="mb-3"><label class="form-label">Description / Instructions</label><textarea class="form-control" id="a_description" rows="3"></textarea></div>
        <div class="mb-3"><label class="form-label">Deadline</label><input type="datetime-local" class="form-control" id="a_deadline" required></div>
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

function addAssignment(event) {
    event.preventDefault();
    var title = document.getElementById("a_title").value.trim();
    var description = document.getElementById("a_description").value.trim();
    var deadline = document.getElementById("a_deadline").value;

    if (title == "" || deadline == "") {
        showMessage("modalMessage", "Title and deadline are required.", "error");
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/assignment_process.php", true);
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
    xhr.send("action=add_assignment&course_id=" + courseId +
        "&title=" + encodeURIComponent(title) +
        "&description=" + encodeURIComponent(description) +
        "&deadline=" + encodeURIComponent(deadline));
    return false;
}

function deleteAssignment(assignmentId) {
    if (!confirmDelete("Delete this assignment and all its submissions?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/assignment_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                document.getElementById("assign" + assignmentId).remove();
            } else {
                alert("Could not delete assignment.");
            }
        }
    };
    xhr.send("action=delete_assignment&assignment_id=" + assignmentId);
}
</script>
