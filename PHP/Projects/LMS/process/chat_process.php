<?php
require_once "../includes/functions.php";
require_login();

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

// ------------------------------------------------------------
// Verify the current user is allowed in a course's chat
// ------------------------------------------------------------
function can_access_course_chat($conn, $user_id, $role, $course_id) {
    if ($role == "teacher") {
        return is_course_owner($conn, $user_id, $course_id);
    }
    if ($role == "student") {
        return is_student_enrolled($conn, $user_id, $course_id);
    }
    return false;
}

// ------------------------------------------------------------
// Verify the current user is allowed in a project group's chat
// ------------------------------------------------------------
function can_access_group_chat($conn, $user_id, $role, $group_id) {
    if ($role == "teacher") {
        $query = "SELECT project_group.group_id FROM project_group
                  INNER JOIN project ON project_group.project_id = project.project_id
                  INNER JOIN course ON project.course_id = course.course_id
                  WHERE project_group.group_id = ? AND course.teacher_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $group_id, $user_id);
        mysqli_stmt_execute($stmt);
        return mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
    }
    if ($role == "student") {
        return is_group_member($conn, $user_id, $group_id);
    }
    return false;
}

// ------------------------------------------------------------
// SEND COURSE CHAT MESSAGE
// ------------------------------------------------------------
if ($action == "send_course_message") {

    $course_id = (int) $_POST["course_id"];
    $message = clean_input($conn, $_POST["message"]);

    if ($message == "" || !can_access_course_chat($conn, $user_id, $role, $course_id)) {
        echo "error";
        exit;
    }

    $chat_group_id = get_course_chat_group_id($conn, $course_id);

    $query = "INSERT INTO chat_message (chat_group_id, sender_id, message) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $chat_group_id, $user_id, $message);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// LOAD COURSE CHAT MESSAGES (returns simple HTML, not JSON)
// ------------------------------------------------------------
if ($action == "load_course_messages") {

    $course_id = (int) $_POST["course_id"];

    if (!can_access_course_chat($conn, $user_id, $role, $course_id)) {
        echo "";
        exit;
    }

    $chat_group_id = get_course_chat_group_id($conn, $course_id);

    $query = "SELECT chat_message.*, user.first_name, user.last_name, user.role AS sender_role
              FROM chat_message INNER JOIN user ON chat_message.sender_id = user.user_id
              WHERE chat_group_id = ? ORDER BY chat_message.message_id ASC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $chat_group_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $bubble_class = "other";
        if ($row["sender_id"] == $user_id) {
            $bubble_class = "me";
        } elseif ($row["sender_role"] == "teacher") {
            $bubble_class = "other teacher-msg";
        }
        echo '<div class="chat-bubble ' . $bubble_class . '">';
        echo '<span class="sender">' . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . ($row["sender_role"] == "teacher" ? " (Teacher)" : "") . '</span>';
        echo htmlspecialchars($row["message"]);
        echo '<span class="time">' . format_date($row["created_at"]) . '</span>';
        echo '</div>';
    }
    exit;
}

// ------------------------------------------------------------
// SEND PROJECT GROUP CHAT MESSAGE
// ------------------------------------------------------------
if ($action == "send_group_message") {

    $group_id = (int) $_POST["group_id"];
    $message = clean_input($conn, $_POST["message"]);

    if ($message == "" || !can_access_group_chat($conn, $user_id, $role, $group_id)) {
        echo "error";
        exit;
    }

    $chat_group_id = get_project_chat_group_id($conn, $group_id);

    $query = "INSERT INTO chat_message (chat_group_id, sender_id, message) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $chat_group_id, $user_id, $message);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// LOAD PROJECT GROUP CHAT MESSAGES
// ------------------------------------------------------------
if ($action == "load_group_messages") {

    $group_id = (int) $_POST["group_id"];

    if (!can_access_group_chat($conn, $user_id, $role, $group_id)) {
        echo "";
        exit;
    }

    $chat_group_id = get_project_chat_group_id($conn, $group_id);

    $query = "SELECT chat_message.*, user.first_name, user.last_name, user.role AS sender_role
              FROM chat_message INNER JOIN user ON chat_message.sender_id = user.user_id
              WHERE chat_group_id = ? ORDER BY chat_message.message_id ASC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $chat_group_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $bubble_class = "other";
        if ($row["sender_id"] == $user_id) {
            $bubble_class = "me";
        } elseif ($row["sender_role"] == "teacher") {
            $bubble_class = "other teacher-msg";
        }
        echo '<div class="chat-bubble ' . $bubble_class . '">';
        echo '<span class="sender">' . htmlspecialchars($row["first_name"] . " " . $row["last_name"]) . ($row["sender_role"] == "teacher" ? " (Teacher)" : "") . '</span>';
        echo htmlspecialchars($row["message"]);
        echo '<span class="time">' . format_date($row["created_at"]) . '</span>';
        echo '</div>';
    }
    exit;
}

// ------------------------------------------------------------
// STUDENT POSTS A PROJECT PROGRESS UPDATE
// ------------------------------------------------------------
if ($action == "add_project_update") {

    require_role("student");

    $group_id = (int) $_POST["group_id"];
    $update_text = clean_input($conn, $_POST["update_text"]);

    if ($update_text == "" || !is_group_member($conn, $user_id, $group_id)) {
        echo "error";
        exit;
    }

    $query = "INSERT INTO project_update (group_id, student_id, update_text) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iis", $group_id, $user_id, $update_text);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
