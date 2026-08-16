<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_course_owner($conn, $teacher_id, $course_id)) {
    header("Location: courses.php");
    exit;
}

$page_title = "Learning Materials";
$asset_path = "../";

// lectures for the dropdown
$lq = mysqli_prepare($conn, "SELECT lecture_id, lecture_title FROM lecture WHERE course_id = ?");
mysqli_stmt_bind_param($lq, "i", $course_id);
mysqli_stmt_execute($lq);
$lectures = mysqli_stmt_get_result($lq);

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <?php if (isset($_GET["msg"])) { ?>
        <div class="alert alert-success">Material uploaded successfully.</div>
    <?php } ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">Learning Materials</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal"><i class="fa-solid fa-upload"></i> Upload Material</button>
    </div>

    <div class="stat-card">
        <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>#</th><th>Title</th><th>Lecture</th><th>Uploaded</th><th>Actions</th></tr></thead>
            <tbody>
            <?php
            $query = "SELECT material.*, lecture.lecture_title FROM material LEFT JOIN lecture ON material.lecture_id = lecture.lecture_id WHERE material.course_id = ? ORDER BY material.material_id DESC";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $course_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-file-arrow-down"></i>No materials uploaded yet.</div></td></tr>';
            }
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr id='mat" . $row["material_id"] . "'>";
                echo "<td>" . $i++ . "</td>";
                echo "<td>" . htmlspecialchars($row["material_title"]) . "</td>";
                echo "<td>" . ($row["lecture_title"] ? htmlspecialchars($row["lecture_title"]) : "<span class='text-muted'>General</span>") . "</td>";
                echo "<td>" . format_date($row["uploaded_at"]) . "</td>";
                echo '<td><a href="../' . htmlspecialchars($row["file_path"]) . '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-download"></i></a> ';
                echo '<button class="btn btn-sm btn-outline-danger" onclick="deleteMaterial(' . $row["material_id"] . ')"><i class="fa-solid fa-trash"></i></button></td>';
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../process/material_process.php" method="POST" enctype="multipart/form-data">
      <div class="modal-header"><h5 class="modal-title">Upload Material</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="upload_material">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <div class="mb-3"><label class="form-label">Material Title</label><input type="text" class="form-control" name="material_title" required></div>
        <div class="mb-3">
            <label class="form-label">Related Lecture (optional)</label>
            <select class="form-select" name="lecture_id">
                <option value="">-- General Material --</option>
                <?php while ($l = mysqli_fetch_assoc($lectures)) { ?>
                    <option value="<?php echo $l["lecture_id"]; ?>"><?php echo htmlspecialchars($l["lecture_title"]); ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">File</label><input type="file" class="form-control" name="material_file" required></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include "../includes/foot.php"; ?>
<script>
function deleteMaterial(materialId) {
    if (!confirmDelete("Delete this material?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/material_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                document.getElementById("mat" + materialId).remove();
            } else {
                alert("Could not delete material.");
            }
        }
    };
    xhr.send("action=delete_material&material_id=" + materialId);
}
</script>
