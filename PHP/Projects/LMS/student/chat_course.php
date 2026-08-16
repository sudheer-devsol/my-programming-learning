<?php
require_once "../includes/functions.php";
require_login();

if ($_SESSION["role"] != "teacher" && $_SESSION["role"] != "student") {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

$allowed = false;
if ($role == "teacher") {
    $allowed = is_course_owner($conn, $user_id, $course_id);
} else {
    $allowed = is_student_enrolled($conn, $user_id, $course_id);
}

if (!$allowed) {
    header("Location: dashboard.php");
    exit;
}

$cq = mysqli_prepare($conn, "SELECT course_title FROM course WHERE course_id = ?");
mysqli_stmt_bind_param($cq, "i", $course_id);
mysqli_stmt_execute($cq);
$course = mysqli_fetch_assoc(mysqli_stmt_get_result($cq));

$page_title = "Course Chat";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_" . $role . ".php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title"><?php echo htmlspecialchars($course["course_title"]); ?> — Course Chat</h5>

    <div class="chat-box">
        <div class="chat-messages" id="chatMessages">
            <div class="text-center text-muted small">Loading messages...</div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="form-control" id="messageInput" placeholder="Type a message..." onkeyup="if(event.keyCode==13){sendMessage();}">
            <button class="btn btn-primary" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
<script>
var courseId = <?php echo $course_id; ?>;

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
    xhr.send("action=load_course_messages&course_id=" + courseId);
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
    xhr.send("action=send_course_message&course_id=" + courseId + "&message=" + encodeURIComponent(message));
}

loadMessages();
setInterval(loadMessages, 3000);
</script>
