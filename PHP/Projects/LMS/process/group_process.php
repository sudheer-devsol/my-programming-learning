<?php
require_once "../includes/functions.php";
require_role("teacher");

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$teacher_id = $_SESSION["user_id"];

// helper: verify this teacher owns the project behind a group_id
function teacher_owns_group($conn, $teacher_id, $group_id) {
    $query = "SELECT project_group.group_id FROM project_group
              INNER JOIN project ON project_group.project_id = project.project_id
              INNER JOIN course ON project.course_id = course.course_id
              WHERE project_group.group_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $group_id, $teacher_id);
    mysqli_stmt_execute($stmt);
    return mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0;
}

// ------------------------------------------------------------
// ADD GROUP
// ------------------------------------------------------------
if ($action == "add_group") {

    $project_id = (int) $_POST["project_id"];

    $check = "SELECT project.project_id FROM project INNER JOIN course ON project.course_id = course.course_id WHERE project.project_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $project_id, $teacher_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        echo "error";
        exit;
    }

    $group_name = clean_input($conn, $_POST["group_name"]);

    $query = "INSERT INTO project_group (project_id, group_name) VALUES (?, ?)";
    $stmt2 = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt2, "is", $project_id, $group_name);

    if (mysqli_stmt_execute($stmt2)) {
        $new_group_id = mysqli_insert_id($conn);
        // automatically create the private chat for this group
        get_project_chat_group_id($conn, $new_group_id);
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// DELETE GROUP
// ------------------------------------------------------------
if ($action == "delete_group") {

    $group_id = (int) $_POST["group_id"];

    if (!teacher_owns_group($conn, $teacher_id, $group_id)) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM project_group WHERE group_id = ?";
    $stmt = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt, "i", $group_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// ADD MEMBER TO GROUP
// ------------------------------------------------------------
if ($action == "add_member") {

    $group_id = (int) $_POST["group_id"];
    $student_id = (int) $_POST["student_id"];

    if (!teacher_owns_group($conn, $teacher_id, $group_id)) {
        echo "error";
        exit;
    }

    $check = "SELECT member_id FROM project_group_member WHERE group_id = ? AND student_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $group_id, $student_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        echo "duplicate";
        exit;
    }

    $query = "INSERT INTO project_group_member (group_id, student_id) VALUES (?, ?)";
    $stmt2 = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt2, "ii", $group_id, $student_id);

    if (mysqli_stmt_execute($stmt2)) {
        add_notification($conn, $student_id, "You have been added to a project group.");
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// REMOVE MEMBER FROM GROUP
// ------------------------------------------------------------
if ($action == "remove_member") {

    $member_id = (int) $_POST["member_id"];

    $check = "SELECT project_group_member.group_id FROM project_group_member
              INNER JOIN project_group ON project_group_member.group_id = project_group.group_id
              INNER JOIN project ON project_group.project_id = project.project_id
              INNER JOIN course ON project.course_id = course.course_id
              WHERE project_group_member.member_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $member_id, $teacher_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM project_group_member WHERE member_id = ?";
    $stmt2 = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt2, "i", $member_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
