<?php
require_once "../includes/functions.php";
require_role("teacher");

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$teacher_id = $_SESSION["user_id"];

// ------------------------------------------------------------
// UPLOAD MATERIAL (normal form submit with file)
// ------------------------------------------------------------
if ($action == "upload_material") {

    $course_id = (int) $_POST["course_id"];

    if (!is_course_owner($conn, $teacher_id, $course_id)) {
        header("Location: ../teacher/courses.php");
        exit;
    }

    $material_title = clean_input($conn, $_POST["material_title"]);
    $lecture_id = !empty($_POST["lecture_id"]) ? (int) $_POST["lecture_id"] : null;

    if (!isset($_FILES["material_file"]) || $_FILES["material_file"]["error"] != 0) {
        header("Location: ../teacher/materials.php?course_id=" . $course_id . "&error=1");
        exit;
    }

    $allowed = array("pdf", "doc", "docx", "ppt", "pptx", "zip", "jpg", "jpeg", "png", "gif", "xls", "xlsx", "txt");
    $ext = strtolower(pathinfo($_FILES["material_file"]["name"], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        header("Location: ../teacher/materials.php?course_id=" . $course_id . "&error=filetype");
        exit;
    }

    // limit file size to 20MB
    if ($_FILES["material_file"]["size"] > 20 * 1024 * 1024) {
        header("Location: ../teacher/materials.php?course_id=" . $course_id . "&error=filesize");
        exit;
    }

    $safe_name = preg_replace("/[^A-Za-z0-9_\-]/", "_", pathinfo($_FILES["material_file"]["name"], PATHINFO_FILENAME));
    $new_name = $safe_name . "_" . time() . "." . $ext;
    $destination = "../assets/uploads/materials/" . $new_name;

    if (move_uploaded_file($_FILES["material_file"]["tmp_name"], $destination)) {

        $file_path = "assets/uploads/materials/" . $new_name;

        $query = "INSERT INTO material (course_id, lecture_id, material_title, file_name, file_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iisss", $course_id, $lecture_id, $material_title, $new_name, $file_path);
        mysqli_stmt_execute($stmt);

        // notify enrolled students
        $sq = mysqli_prepare($conn, "SELECT student_id FROM course_enrollment WHERE course_id = ?");
        mysqli_stmt_bind_param($sq, "i", $course_id);
        mysqli_stmt_execute($sq);
        $students = mysqli_stmt_get_result($sq);
        while ($s = mysqli_fetch_assoc($students)) {
            add_notification($conn, $s["student_id"], "New material uploaded: " . $material_title);
        }

        header("Location: ../teacher/materials.php?course_id=" . $course_id . "&msg=uploaded");
    } else {
        header("Location: ../teacher/materials.php?course_id=" . $course_id . "&error=upload");
    }
    exit;
}

// ------------------------------------------------------------
// DELETE MATERIAL
// ------------------------------------------------------------
if ($action == "delete_material") {

    $material_id = (int) $_POST["material_id"];

    $query = "SELECT material.file_path FROM material INNER JOIN course ON material.course_id = course.course_id WHERE material.material_id = ? AND course.teacher_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $material_id, $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "error";
        exit;
    }

    $delete = "DELETE FROM material WHERE material_id = ?";
    $stmt2 = mysqli_prepare($conn, $delete);
    mysqli_stmt_bind_param($stmt2, "i", $material_id);

    if (mysqli_stmt_execute($stmt2)) {
        $file_on_disk = "../" . $row["file_path"];
        if (file_exists($file_on_disk)) {
            unlink($file_on_disk);
        }
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
