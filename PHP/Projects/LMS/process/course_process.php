<?php
require_once "../includes/functions.php";
require_role("admin");

$action = isset($_POST["action"]) ? $_POST["action"] : "";

// ------------------------------------------------------------
// ADD COURSE (normal form submit, supports image upload)
// ------------------------------------------------------------
if ($action == "add_course") {

    $course_title = clean_input($conn, $_POST["course_title"]);
    $course_description = clean_input($conn, $_POST["course_description"]);
    $teacher_id = (int) $_POST["teacher_id"];

    $course_image = null;

    if (isset($_FILES["course_image"]) && $_FILES["course_image"]["error"] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif", "webp");
        $ext = strtolower(pathinfo($_FILES["course_image"]["name"], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "course_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            $destination = "../assets/uploads/materials/" . $new_name;
            if (move_uploaded_file($_FILES["course_image"]["tmp_name"], $destination)) {
                $course_image = "assets/uploads/materials/" . $new_name;
            }
        }
    }

    $query = "INSERT INTO course (course_title, course_description, course_image, teacher_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $course_title, $course_description, $course_image, $teacher_id);
    mysqli_stmt_execute($stmt);

    add_notification($conn, $teacher_id, "You have been assigned to a new course: " . $course_title);

    header("Location: ../admin/courses.php?msg=added");
    exit;
}

// ------------------------------------------------------------
// EDIT COURSE
// ------------------------------------------------------------
if ($action == "edit_course") {

    $course_id = (int) $_POST["course_id"];
    $course_title = clean_input($conn, $_POST["course_title"]);
    $course_description = clean_input($conn, $_POST["course_description"]);
    $teacher_id = (int) $_POST["teacher_id"];
    $status = clean_input($conn, $_POST["status"]);

    if (isset($_FILES["course_image"]) && $_FILES["course_image"]["error"] == 0) {
        $allowed = array("jpg", "jpeg", "png", "gif", "webp");
        $ext = strtolower(pathinfo($_FILES["course_image"]["name"], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_name = "course_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            $destination = "../assets/uploads/materials/" . $new_name;
            if (move_uploaded_file($_FILES["course_image"]["tmp_name"], $destination)) {
                $course_image = "assets/uploads/materials/" . $new_name;
                $query = "UPDATE course SET course_title=?, course_description=?, teacher_id=?, status=?, course_image=? WHERE course_id=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssissi", $course_title, $course_description, $teacher_id, $status, $course_image, $course_id);
                mysqli_stmt_execute($stmt);
                header("Location: ../admin/courses.php?msg=updated");
                exit;
            }
        }
    }

    $query = "UPDATE course SET course_title=?, course_description=?, teacher_id=?, status=? WHERE course_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssisi", $course_title, $course_description, $teacher_id, $status, $course_id);
    mysqli_stmt_execute($stmt);

    header("Location: ../admin/courses.php?msg=updated");
    exit;
}

// ------------------------------------------------------------
// DELETE COURSE
// ------------------------------------------------------------
if ($action == "delete_course") {

    $course_id = (int) $_POST["course_id"];

    $query = "DELETE FROM course WHERE course_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
