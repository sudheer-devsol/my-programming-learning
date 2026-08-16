<?php
require_once "../includes/functions.php";
require_role("teacher");

$teacher_id = $_SESSION["user_id"];
$project_id = isset($_GET["project_id"]) ? (int) $_GET["project_id"] : 0;

$query = "SELECT project.*, course.course_id, course.course_title FROM project INNER JOIN course ON project.course_id = course.course_id WHERE project.project_id = ? AND course.teacher_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $project_id, $teacher_id);
mysqli_stmt_execute($stmt);
$project = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$project) {
    header("Location: courses.php");
    exit;
}

$course_id = $project["course_id"];

// enrolled students in this course (for the "add to group" dropdown)
$sq = mysqli_prepare($conn, "SELECT user.user_id, user.first_name, user.last_name FROM course_enrollment INNER JOIN user ON course_enrollment.student_id = user.user_id WHERE course_enrollment.course_id = ? ORDER BY user.first_name");
mysqli_stmt_bind_param($sq, "i", $course_id);
mysqli_stmt_execute($sq);
$enrolled_students = mysqli_stmt_get_result($sq);

$page_title = "Project Groups";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_teacher.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="stat-card mb-3">
        <h5 class="mb-1"><?php echo htmlspecialchars($project["project_title"]); ?></h5>
        <p class="text-muted mb-0">Course: <?php echo htmlspecialchars($project["course_title"]); ?></p>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="section-title mb-0">Groups</h6>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal"><i class="fa-solid fa-plus"></i> Create Group</button>
    </div>

    <div class="row g-3" id="groupList">
    <?php
    $gq = mysqli_prepare($conn, "SELECT * FROM project_group WHERE project_id = ? ORDER BY group_id DESC");
    mysqli_stmt_bind_param($gq, "i", $project_id);
    mysqli_stmt_execute($gq);
    $groups = mysqli_stmt_get_result($gq);

    if (mysqli_num_rows($groups) == 0) {
        echo '<div class="col-12"><div class="empty-state"><i class="fa-solid fa-users"></i>No groups created yet.</div></div>';
    }

    while ($g = mysqli_fetch_assoc($groups)) {
        echo '<div class="col-md-6 col-lg-4" id="group' . $g["group_id"] . '"><div class="stat-card">';
        echo '<div class="d-flex justify-content-between align-items-start mb-2">';
        echo '<h6 class="mb-0">' . htmlspecialchars($g["group_name"]) . '</h6>';
        echo '<button class="btn btn-sm btn-outline-danger" onclick="deleteGroup(' . $g["group_id"] . ')"><i class="fa-solid fa-trash"></i></button>';
        echo '</div>';

        echo '<ul class="list-unstyled small mb-2" id="members' . $g["group_id"] . '">';
        $mq = mysqli_prepare($conn, "SELECT project_group_member.member_id, user.user_id, user.first_name, user.last_name FROM project_group_member INNER JOIN user ON project_group_member.student_id = user.user_id WHERE group_id = ?");
        mysqli_stmt_bind_param($mq, "i", $g["group_id"]);
        mysqli_stmt_execute($mq);
        $members = mysqli_stmt_get_result($mq);
        if (mysqli_num_rows($members) == 0) {
            echo '<li class="text-muted">No members yet</li>';
        }
        while ($m = mysqli_fetch_assoc($members)) {
            echo '<li class="d-flex justify-content-between align-items-center border-bottom py-1">';
            echo '<span><i class="fa-solid fa-user"></i> ' . htmlspecialchars($m["first_name"] . " " . $m["last_name"]) . '</span>';
            echo '<button class="btn btn-sm btn-link text-danger p-0" onclick="removeMember(' . $m["member_id"] . ', ' . $g["group_id"] . ')"><i class="fa-solid fa-xmark"></i></button>';
            echo '</li>';
        }
        echo '</ul>';

        echo '<div class="d-flex gap-2">';
        echo '<select class="form-select form-select-sm" id="student_select' . $g["group_id"] . '">';
        mysqli_data_seek($enrolled_students, 0);
        while ($s = mysqli_fetch_assoc($enrolled_students)) {
            echo '<option value="' . $s["user_id"] . '">' . htmlspecialchars($s["first_name"] . " " . $s["last_name"]) . '</option>';
        }
        echo '</select>';
        echo '<button class="btn btn-sm btn-primary" onclick="addMember(' . $g["group_id"] . ')">Add</button>';
        echo '</div>';

        echo '<a href="chat_project.php?group_id=' . $g["group_id"] . '" class="btn btn-sm btn-outline-secondary mt-2 w-100"><i class="fa-solid fa-comments"></i> Group Chat</a>';
        echo '</div></div>';
    }
    ?>
    </div>
</div>

<div class="modal fade" id="addGroupModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Create Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form id="addGroupForm" onsubmit="return addGroup(event)">
      <div class="modal-body">
        <div id="modalMessage"></div>
        <div class="mb-3"><label class="form-label">Group Name</label><input type="text" class="form-control" id="g_name" placeholder="e.g. Group 1" required></div>
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
var projectId = <?php echo $project_id; ?>;

function addGroup(event) {
    event.preventDefault();
    var name = document.getElementById("g_name").value.trim();
    if (name == "") {
        showMessage("modalMessage", "Group name is required.", "error");
        return false;
    }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/group_process.php", true);
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
    xhr.send("action=add_group&project_id=" + projectId + "&group_name=" + encodeURIComponent(name));
    return false;
}

function deleteGroup(groupId) {
    if (!confirmDelete("Delete this group and its chat history?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/group_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                document.getElementById("group" + groupId).remove();
            } else {
                alert("Could not delete group.");
            }
        }
    };
    xhr.send("action=delete_group&group_id=" + groupId);
}

function addMember(groupId) {
    var select = document.getElementById("student_select" + groupId);
    var studentId = select.value;

    if (!studentId) {
        alert("No students available to add.");
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/group_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                window.location.reload();
            } else if (response == "duplicate") {
                alert("This student is already in the group.");
            } else {
                alert("Could not add student.");
            }
        }
    };
    xhr.send("action=add_member&group_id=" + groupId + "&student_id=" + studentId);
}

function removeMember(memberId, groupId) {
    if (!confirmDelete("Remove this student from the group?")) { return; }
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/group_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                window.location.reload();
            } else {
                alert("Could not remove student.");
            }
        }
    };
    xhr.send("action=remove_member&member_id=" + memberId);
}
</script>
