<?php
require_once "../includes/functions.php";
require_role("teacher");

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$teacher_id = $_SESSION["user_id"];

// ------------------------------------------------------------
// ADD ASSIGNMENT
// ------------------------------------------------------------
if ($action == "add_assignment") {

    $course_id = (int) $_POST["course_id"];

    if (!is_course_owner($conn, $teacher_id, $course_id)) {
        echo "error";
        exit;
    }

    $title = clean_input($conn, $_POST["title"]);
    $description = clean_input($conn, $_POST["description"]);
    $deadline = clean_input($conn, $_POST["deadline"]);
    $deadline = str_replace("T", " ", $deadline);

    $query = "INSERT INTO assignment (course_id, title, description, deadline) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isss", $course_id, $title, $description, $deadline);

    if (mysqli_stmt_execute($stmt)) {

        $sq = mysqli_prepare($conn, "SELECT student_id FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($sq, "i", $course_id);
        mysqli_stmt_execute($sq);
        $students = mysqli_stmt_get_result($sq);
        while ($s = mysqli_fetch_assoc($students)) {
            add_notification($conn, $s["student_id"], "New assignment posted: " . $title);
        }

        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// DELETE ASSIGNMENT
// ------------------------------------------------------------
if ($action == "delete_assignment") {

    $assignment_id = (int) $_POST["assignment_id"];

    $query = "SELECT assignment.assignment_id FROM assignment INNER JOIN course ON assignment.course_id = course.course_id WHERE assignment.assignment_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $assignment_id, $teacher_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM assignment WHERE assignment_id = ?";
    $stmt2 = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt2, "i", $assignment_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
