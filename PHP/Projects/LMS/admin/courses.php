<?php
require_once "../includes/functions.php";
require_role("admin");

$page_title = "Manage Courses";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_admin.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <?php if (isset($_GET["msg"])) { ?>
        <div class="alert alert-success"><?php echo $_GET["msg"] == "added" ? "Course added successfully." : "Course updated successfully."; ?></div>
    <?php } ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="section-title mb-0">All Courses</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal"><i class="fa-solid fa-plus"></i> Add Course</button>
    </div>

    <div class="row g-3">
    <?php
    $result = mysqli_query($conn, "SELECT course.*, user.first_name, user.last_name FROM course INNER JOIN user ON course.teacher_id = user.user_id ORDER BY course.course_id DESC");
    if (mysqli_num_rows($result) == 0) {
        echo '<div class="col-12"><div class="empty-state"><i class="fa-solid fa-book"></i>No courses created yet.</div></div>';
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $eq = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($eq, "i", $row["course_id"]);
        mysqli_stmt_execute($eq);
        $ecount = mysqli_fetch_assoc(mysqli_stmt_get_result($eq))["total"];
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="course-card">
                <div class="course-banner"><?php echo htmlspecialchars($row["course_title"]); ?></div>
                <div class="card-body">
                    <p class="text-muted small mb-2"><i class="fa-solid fa-chalkboard-user"></i> <?php echo htmlspecialchars($row["first_name"] . " " . $row["last_name"]); ?></p>
                    <p class="small mb-2"><i class="fa-solid fa-user-graduate"></i> <?php echo $ecount; ?> students enrolled</p>
                    <span class="badge bg-<?php echo $row["status"] == "active" ? "success" : "secondary"; ?> mb-2"><?php echo $row["status"]; ?></span>
                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-sm btn-outline-primary" onclick='openEditModal(<?php echo json_encode($row); ?>)'><i class="fa-solid fa-pen"></i> Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCourse(<?php echo $row['course_id']; ?>)"><i class="fa-solid fa-trash"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../process/course_process.php" method="POST" enctype="multipart/form-data">
      <div class="modal-header"><h5 class="modal-title">Add Course</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="add_course">
        <div class="mb-3"><label class="form-label">Course Title</label><input type="text" class="form-control" name="course_title" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="course_description" rows="3"></textarea></div>
        <div class="mb-3">
            <label class="form-label">Assign Teacher</label>
            <select class="form-select" name="teacher_id" required>
                <option value="">-- Select Teacher --</option>
                <?php
                $teachers = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM user WHERE role = 'teacher' AND status = 'active'");
                while ($t = mysqli_fetch_assoc($teachers)) {
                    echo '<option value="' . $t["user_id"] . '">' . htmlspecialchars($t["first_name"] . " " . $t["last_name"]) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Course Image (optional)</label><input type="file" class="form-control" name="course_image" accept="image/*"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Course</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="../process/course_process.php" method="POST" enctype="multipart/form-data">
      <div class="modal-header"><h5 class="modal-title">Edit Course</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" name="action" value="edit_course">
        <input type="hidden" name="course_id" id="edit_course_id">
        <div class="mb-3"><label class="form-label">Course Title</label><input type="text" class="form-control" name="course_title" id="edit_course_title" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="course_description" id="edit_course_description" rows="3"></textarea></div>
        <div class="mb-3">
            <label class="form-label">Assign Teacher</label>
            <select class="form-select" name="teacher_id" id="edit_teacher_id" required>
                <?php
                $teachers2 = mysqli_query($conn, "SELECT user_id, first_name, last_name FROM user WHERE role = 'teacher' AND status = 'active'");
                while ($t = mysqli_fetch_assoc($teachers2)) {
                    echo '<option value="' . $t["user_id"] . '">' . htmlspecialchars($t["first_name"] . " " . $t["last_name"]) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="edit_status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Replace Course Image (optional)</label><input type="file" class="form-control" name="course_image" accept="image/*"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include "../includes/foot.php"; ?>
<script>
function openEditModal(course) {
    document.getElementById("edit_course_id").value = course.course_id;
    document.getElementById("edit_course_title").value = course.course_title;
    document.getElementById("edit_course_description").value = course.course_description;
    document.getElementById("edit_teacher_id").value = course.teacher_id;
    document.getElementById("edit_status").value = course.status;

    var modal = new bootstrap.Modal(document.getElementById("editCourseModal"));
    modal.show();
}

function deleteCourse(courseId) {
    if (!confirmDelete("Delete this course? All lectures, materials, assignments and projects under it will also be removed.")) {
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/course_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            window.location.reload();
        }
    };
    xhr.send("action=delete_course&course_id=" + courseId);
}
</script>
