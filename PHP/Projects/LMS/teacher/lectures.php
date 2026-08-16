<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$page_title = "Lectures";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">Course Lectures</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLectureModal"><i class="fa-solid fa-plus"></i> Add Lecture</button>
    </div>

    <div id="lectureList">
    <?php
    $query = "SELECT * FROM lecture WHERE course_id = ? ORDER BY lecture_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-video"></i>No lectures added yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="stat-card mb-3" id="lecture' . $row["lecture_id"] . '">';
        echo '<div class="d-flex justify-content-between align-items-start">';
        echo '<div><h6 class="mb-1">' . htmlspecialchars($row["lecture_title"]) . '</h6>';
        echo '<p class="small text-muted mb-1">' . format_date($row["created_at"]) . '</p>';
        echo '<p class="small mb-1">' . nl2br(htmlspecialchars($row["lecture_description"])) . '</p>';
        if ($row["lecture_content"]) {
            echo '<p class="small mb-1">' . nl2br(htmlspecialchars($row["lecture_content"])) . '</p>';
        }
        if ($row["video_link"]) {
            echo '<a href="' . htmlspecialchars($row["video_link"]) . '" target="_blank" class="small"><i class="fa-solid fa-link"></i> Video Link</a>';
        }
        echo '</div>';
        echo '<div><button class="btn btn-sm btn-outline-danger" onclick="deleteLecture(' . $row["lecture_id"] . ')"><i class="fa-solid fa-trash"></i></button></div>';
        echo '</div></div>';
    }
    ?>
    </div>
</div>

<div class="modal fade" id="addLectureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add Lecture</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addLectureForm" onsubmit="return addLecture(event)">
      <div class="modal-body">
        <div id="modalMessage"></div>
        <div class="mb-3"><label class="form-label">Lecture Title</label><input type="text" class="form-control" id="lecture_title" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" id="lecture_description" rows="2"></textarea></div>
        <div class="mb-3"><label class="form-label">Content</label><textarea class="form-control" id="lecture_content" rows="3"></textarea></div>
        <div class="mb-3"><label class="form-label">Video Link (optional)</label><input type="text" class="form-control" id="video_link" placeholder="https://..."></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Lecture</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include "../includes/foot.php"; ?>
<script>
var courseId = <?php echo $course_id; ?>;

function addLecture(event) {
    event.preventDefault();
    var title = document.getElementById("lecture_title").value.trim();
    var description = document.getElementById("lecture_description").value.trim();
    var content = document.getElementById("lecture_content").value.trim();
    var videoLink = document.getElementById("video_link").value.trim();

    if (title == "") {
        showMessage("modalMessage", "Lecture title is required.", "error");
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/lecture_process.php", true);
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
    xhr.send("action=add_lecture&course_id=" + courseId +
        "&lecture_title=" + encodeURIComponent(title) +
        "&lecture_description=" + encodeURIComponent(description) +
        "&lecture_content=" + encodeURIComponent(content) +
        "&video_link=" + encodeURIComponent(videoLink));
    return false;
}

function deleteLecture(lectureId) {
    if (!confirmDelete("Delete this lecture and its materials?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/lecture_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                document.getElementById("lecture" + lectureId).remove();
            } else {
                alert("Could not delete lecture.");
            }
        }
    };
    xhr.send("action=delete_lecture&lecture_id=" + lectureId);
}
</script>
