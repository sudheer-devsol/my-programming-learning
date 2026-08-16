<?php
require_once "../includes/functions.php";
require_login();

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// ------------------------------------------------------------
// STUDENT SUBMITS ASSIGNMENT (normal form, file upload)
// ------------------------------------------------------------
if ($action == "submit_assignment") {

    require_role("student");

    $student_id = $_SESSION["user_id"];
    $assignment_id = (int) $_POST["assignment_id"];

    // verify the student is enrolled in the assignment's course
    $check = "SELECT course_enrollment.enrollment_id FROM assignment
              INNER JOIN course_enrollment ON assignment.course_id = course_enrollment.course_id
              WHERE assignment.assignment_id = ? AND course_enrollment.student_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $assignment_id, $student_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) == 0) {
        header("Location: ../student/dashboard.php");
        exit;
    }

    if (!isset($_FILES["submission_file"]) || $_FILES["submission_file"]["error"] != 0) {
        header("Location: ../student/assignments.php?error=1");
        exit;
    }

    $allowed = array("pdf", "doc", "docx", "ppt", "pptx", "zip", "jpg", "jpeg", "png", "txt", "xls", "xlsx");
    $ext = strtolower(pathinfo($_FILES["submission_file"]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        header("Location: ../student/assignments.php?error=filetype");
        exit;
    }

    $new_name = "submission_" . $student_id . "_" . $assignment_id . "_" . time() . "." . $ext;
    $destination = "../assets/uploads/submissions/" . $new_name;

    if (move_uploaded_file($_FILES["submission_file"]["tmp_name"], $destination)) {

        $file_path = "assets/uploads/submissions/" . $new_name;

        // check for existing submission (resubmission = update)
        $existing = mysqli_prepare($conn, "SELECT submission_id FROM assignment_submission WHERE assignment_id = ? AND student_id = ?");
        mysqli_stmt_bind_param($existing, "ii", $assignment_id, $student_id);
        mysqli_stmt_execute($existing);
        $existing_result = mysqli_stmt_get_result($existing);

        if ($existing_row = mysqli_fetch_assoc($existing_result)) {
            $update = "UPDATE assignment_submission SET file_path = ?, submitted_at = NOW() WHERE submission_id = ?";
            $stmt2 = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($stmt2, "si", $file_path, $existing_row["submission_id"]);
            mysqli_stmt_execute($stmt2);
        } else {
            $insert = "INSERT INTO assignment_submission (assignment_id, student_id, file_path) VALUES (?, ?, ?)";
            $stmt2 = mysqli_prepare($conn, $insert);
            mysqli_stmt_bind_param($stmt2, "iis", $assignment_id, $student_id, $file_path);
            mysqli_stmt_execute($stmt2);
        }

        // notify teacher
        $tq = mysqli_prepare($conn, "SELECT course.teacher_id, assignment.title FROM assignment INNER JOIN course ON assignment.course_id = course.course_id WHERE assignment.assignment_id = ?");
        mysqli_stmt_bind_param($tq, "i", $assignment_id);
        mysqli_stmt_execute($tq);
        $tRow = mysqli_fetch_assoc(mysqli_stmt_get_result($tq));
        if ($tRow) {
            add_notification($conn, $tRow["teacher_id"], $_SESSION["first_name"] . " " . $_SESSION["last_name"] . " submitted: " . $tRow["title"]);
        }

        header("Location: ../student/assignments.php?msg=submitted");
    } else {
        header("Location: ../student/assignments.php?error=upload");
    }
    exit;
}

// ------------------------------------------------------------
// TEACHER GRADES A SUBMISSION (AJAX)
// ------------------------------------------------------------
if ($action == "grade_submission") {

    require_role("teacher");

    $teacher_id = $_SESSION["user_id"];
    $submission_id = (int) $_POST["submission_id"];
    $marks = (int) $_POST["marks"];
    $feedback = clean_input($conn, $_POST["feedback"]);

    $check = "SELECT assignment_submission.student_id, assignment.title FROM assignment_submission
              INNER JOIN assignment ON assignment_submission.assignment_id = assignment.assignment_id
              INNER JOIN course ON assignment.course_id = course.course_id
              WHERE assignment_submission.submission_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $submission_id, $teacher_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) {
        echo "error";
        exit;
    }

    $update = "UPDATE assignment_submission SET marks = ?, feedback = ? WHERE submission_id = ?";
    $stmt2 = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt2, "isi", $marks, $feedback, $submission_id);

    if (mysqli_stmt_execute($stmt2)) {
        add_notification($conn, $row["student_id"], "Your submission for '" . $row["title"] . "' has been graded.");
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
