<?php
require_once "../includes/functions.php";
require_login();

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// ------------------------------------------------------------
// STUDENT ENROLLS IN A COURSE
// ------------------------------------------------------------
if ($action == "enroll") {

    if ($_SESSION["role"] != "student") {
        echo "error";
        exit;
    }

    $student_id = $_SESSION["user_id"];
    $course_id = (int) $_POST["course_id"];

    if (is_student_enrolled($conn, $student_id, $course_id)) {
        echo "duplicate";
        exit;
    }

    $query = "INSERT INTO course_enrollment (course_id, student_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $course_id, $student_id);

    if (mysqli_stmt_execute($stmt)) {

        // add student to the course's common chat group automatically (chat_group already implicit by membership check)
        get_course_chat_group_id($conn, $course_id);

        // notify the course teacher
        $cq = mysqli_prepare($conn, "SELECT teacher_id, course_title FROM course WHERE course_id = ?");
        mysqli_stmt_bind_param($cq, "i", $course_id);
        mysqli_stmt_execute($cq);
        $course_row = mysqli_fetch_assoc(mysqli_stmt_get_result($cq));

        if ($course_row) {
            add_notification($conn, $course_row["teacher_id"], $_SESSION["first_name"] . " " . $_SESSION["last_name"] . " enrolled in " . $course_row["course_title"]);
        }

        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// ADMIN REMOVES AN ENROLLMENT
// ------------------------------------------------------------
if ($action == "remove_enrollment") {

    require_role("admin");

    $enrollment_id = (int) $_POST["enrollment_id"];

    $query = "DELETE FROM course_enrollment WHERE enrollment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $enrollment_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
