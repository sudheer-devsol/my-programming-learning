<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$assignment_id = isset($_GET["assignment_id"]) ? (int) $_GET["assignment_id"] : 0;

$query = "SELECT assignment.*, course.course_id, course.course_title FROM assignment INNER JOIN course ON assignment.course_id = course.course_id WHERE assignment.assignment_id = ? AND course.teacher_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $assignment_id, $teacher_id);
mysqli_stmt_execute($stmt);
$assignment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$assignment) {
    header("Location: courses.php");
    exit;
}

$page_title = "Submissions";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="stat-card mb-3">
        <h5 class="mb-1"><?php echo htmlspecialchars($assignment["title"]); ?></h5>
        <p class="text-muted mb-0">Course: <?php echo htmlspecialchars($assignment["course_title"]); ?> &bull; Deadline: <?php echo format_date($assignment["deadline"]); ?></p>
    </div>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Student</th><th>Submitted</th><th>File</th><th>Marks</th><th>Feedback</th><th>Action</th></tr></thead>
            <tbody>
            <?php
            $sq = "SELECT assignment_submission.*, user.first_name, user.last_name FROM assignment_submission INNER JOIN user ON assignment_submission.student_id = user.user_id WHERE assignment_id = ? ORDER BY submitted_at DESC";
            $stmt2 = mysqli_prepare($conn, $sq);
            mysqli_stmt_bind_param($stmt2, "i", $assignment_id);
            mysqli_stmt_execute($stmt2);
            $result = mysqli_stmt_get_result($stmt2);

            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-inbox"></i>No submissions yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='sub" . $row["submission_id"] . "'>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . "</td>";
                echo "<td>" . format_date($row["submitted_at"]) . "</td>";
                echo '<td><a href="../' . htmlspecialchars($row["file_path"]) . '" target="_blank"><i class="fa-solid fa-download"></i> Download</a></td>';
                echo '<td><input type="number" min="0" max="100" class="form-control form-control-sm" style="width:80px" id="marks' . $row["submission_id"] . '" value="' . htmlspecialchars($row["marks"]) . '"></td>';
                echo '<td><input type="text" class="form-control form-control-sm" id="feedback' . $row["submission_id"] . '" value="' . htmlspecialchars($row["feedback"]) . '"></td>';
                echo '<td><button class="btn btn-sm btn-primary" onclick="saveGrade(' . $row["submission_id"] . ')">Save</button></td>';
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
function saveGrade(submissionId) {
    var marks = document.getElementById("marks" + submissionId).value;
    var feedback = document.getElementById("feedback" + submissionId).value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/submission_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                alert("Grade saved successfully.");
            } else {
                alert("Could not save grade.");
            }
        }
    };
    xhr.send("action=grade_submission&submission_id=" + submissionId + "&marks=" + encodeURIComponent(marks) + "&feedback=" + encodeURIComponent(feedback));
}
</script>
