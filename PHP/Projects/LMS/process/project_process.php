<?php
require_once "../includes/functions.php";
require_role("teacher");

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$teacher_id = $_SESSION["user_id"];

// ------------------------------------------------------------
// ADD PROJECT
// ------------------------------------------------------------
if ($action == "add_project") {

    $course_id = (int) $_POST["course_id"];

    if (!is_course_owner($conn, $teacher_id, $course_id)) {
        echo "error";
        exit;
    }

    $project_title = clean_input($conn, $_POST["project_title"]);
    $project_description = clean_input($conn, $_POST["project_description"]);

    $query = "INSERT INTO project (course_id, project_title, project_description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iss", $course_id, $project_title, $project_description);

    if (mysqli_stmt_execute($stmt)) {

        $sq = mysqli_prepare($conn, "SELECT student_id FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($sq, "i", $course_id);
        mysqli_stmt_execute($sq);
        $students = mysqli_stmt_get_result($sq);
        while ($s = mysqli_fetch_assoc($students)) {
            add_notification($conn, $s["student_id"], "New project posted: " . $project_title);
        }

        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// DELETE PROJECT
// ------------------------------------------------------------
if ($action == "delete_project") {

    $project_id = (int) $_POST["project_id"];

    $query = "SELECT project.project_id FROM project INNER JOIN course ON project.course_id = course.course_id WHERE project.project_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $project_id, $teacher_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM project WHERE project_id = ?";
    $stmt2 = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt2, "i", $project_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
