<?php
require_once "../includes/functions.php";
require_role("teacher");

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$teacher_id = $_SESSION["user_id"];

// ------------------------------------------------------------
// ADD LECTURE
// ------------------------------------------------------------
if ($action == "add_lecture") {

    $course_id = (int) $_POST["course_id"];

    if (!is_course_owner($conn, $teacher_id, $course_id)) {
        echo "error";
        exit;
    }

    $lecture_title = clean_input($conn, $_POST["lecture_title"]);
    $lecture_description = clean_input($conn, $_POST["lecture_description"]);
    $lecture_content = clean_input($conn, $_POST["lecture_content"]);
    $video_link = clean_input($conn, $_POST["video_link"]);

    $query = "INSERT INTO lecture (course_id, lecture_title, lecture_description, lecture_content, video_link) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "issss", $course_id, $lecture_title, $lecture_description, $lecture_content, $video_link);

    if (mysqli_stmt_execute($stmt)) {

        // notify enrolled students
        $sq = mysqli_prepare($conn, "SELECT student_id FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($sq, "i", $course_id);
        mysqli_stmt_execute($sq);
        $students = mysqli_stmt_get_result($sq);
        while ($s = mysqli_fetch_assoc($students)) {
            add_notification($conn, $s["student_id"], "New lecture added: " . $lecture_title);
        }

        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// DELETE LECTURE
// ------------------------------------------------------------
if ($action == "delete_lecture") {

    $lecture_id = (int) $_POST["lecture_id"];

    $query = "SELECT lecture.lecture_id FROM lecture INNER JOIN course ON lecture.course_id = course.course_id WHERE lecture.lecture_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $lecture_id, $teacher_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM lecture WHERE lecture_id = ?";
    $stmt2 = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt2, "i", $lecture_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
