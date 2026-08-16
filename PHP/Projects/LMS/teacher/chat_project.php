<?php
require_once "../includes/functions.php";
require_login();

if ($_SESSION["role"] != "teacher" && $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];
$group_id = isset($_GET["group_id"]) ? (int) $_GET["group_id"] : 0;

$allowed = false;
if ($role == "teacher") {
    $query = "SELECT project_group.group_id FROM project_group
              INNER JOIN project ON project_group.project_id = project.project_id
              INNER JOIN course ON project.course_id = course.course_id
              WHERE project_group.group_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $group_id, $user_id);
    mysqli_stmt_execute($stmt);
    $allowed = mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
} else {
    $allowed = is_group_member($conn, $user_id, $group_id);
}

if (!$allowed) {
    header("Location: dashboard.php");
    exit;
}

$gq = mysqli_prepare($conn, "SELECT project_group.group_name, project.project_title FROM project_group INNER JOIN project ON project_group.project_id = project.project_id WHERE group_id = ?");
mysqli_stmt_bind_param($gq, "i", $group_id);
mysqli_stmt_execute($gq);
$group = mysqli_fetch_assoc(mysqli_stmt_get_result($gq));

$page_title = "Project Group Chat";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_" . $role . ".php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title"><?php echo htmlspecialchars($group["project_title"]); ?> — <?php echo htmlspecialchars($group["group_name"]); ?> (Private)</h5>

    <div class="chat-box mb-4">
        <div class="chat-messages" id="chatMessages">
            <div class="text-center text-muted small">Loading messages...</div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="form-control" id="messageInput" placeholder="Type a message..." onkeyup="if(event.keyCode==13){sendMessage();}">
            <button class="btn btn-primary" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>

    <?php if ($role == "student") { ?>
    <div class="stat-card">
        <h6 class="section-title">Share Project Progress</h6>
        <div id="updateMessage"></div>
        <div class="d-flex gap-2 mb-3">
            <input type="text" class="form-control" id="updateInput" placeholder="e.g. I completed the login page">
            <button class="btn btn-outline-primary" onclick="postUpdate()">Post</button>
        </div>
        <div id="updatesList">
        <?php
        $uq = mysqli_prepare($conn, "SELECT project_update.*, user.first_name, user.last_name FROM project_update INNER JOIN user ON project_update.student_id = user.user_id WHERE group_id = ? ORDER BY update_id DESC");
        mysqli_stmt_bind_param($uq, "i", $group_id);
        mysqli_stmt_execute($uq);
        $updates = mysqli_stmt_get_result($uq);
        if (mysqli_num_rows($updates) == 0) {
            echo '<p class="text-muted small">No progress updates yet.</p>';
        }
        while ($u = mysqli_fetch_assoc($updates)) {
            echo '<div class="border-bottom py-2 small"><strong>' . htmlspecialchars($u["first_name"] . " " . $u["last_name"]) . ':</strong> ' . htmlspecialchars($u["update_text"]) . '<br><span class="text-muted">' . format_date($u["created_at"]) . '</span></div>';
        }
        ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php include "../includes/foot.php"; ?>
<script>
var groupId = <?php echo $group_id; ?>;

function loadMessages() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/chat_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var box = document.getElementById("chatMessages");
            var wasNearBottom = (box.scrollHeight - box.scrollTop - box.clientHeight) < 60;
            box.innerHTML = xhr.responseText;
            if (wasNearBottom) {
                box.scrollTop = box.scrollHeight;
            }
        }
    };
    xhr.send("action=load_group_messages&group_id=" + groupId);
}

function sendMessage() {
    var input = document.getElementById("messageInput");
    var message = input.value.trim();
    if (message == "") { return; }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/chat_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                input.value = "";
                loadMessages();
            }
        }
    };
    xhr.send("action=send_group_message&group_id=" + groupId + "&message=" + encodeURIComponent(message));
}

function postUpdate() {
    var input = document.getElementById("updateInput");
    var text = input.value.trim();
    if (text == "") { return; }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/chat_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText.trim() == "success") {
                window.location.reload();
            } else {
                showMessage("updateMessage", "Could not post update.", "error");
            }
        }
    };
    xhr.send("action=add_project_update&group_id=" + groupId + "&update_text=" + encodeURIComponent(text));
}

loadMessages();
setInterval(loadMessages, 3000);
</script>
