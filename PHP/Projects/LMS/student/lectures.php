<?php
require_once "../includes/functions.php";
require_role("student");

$student_id = $_SESSION["user_id"];
$course_id = isset($_GET["course_id"]) ? (int) $_GET["course_id"] : 0;

if (!is_student_enrolled($conn, $student_id, $course_id)) {
    header("Location: browse_courses.php");
    exit;
}

$page_title = "Lectures";
$asset_path = "../";

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <h5 class="section-title">Course Lectures</h5>

    <?php
    $query = "SELECT * FROM lecture WHERE course_id = ? ORDER BY lecture_id DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="empty-state"><i class="fa-solid fa-video"></i>No lectures posted yet.</div>';
    }

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<div class="stat-card mb-3">';
        echo '<h6 class="mb-1">' . htmlspecialchars($row["lecture_title"]) . '</h6>';
        echo '<p class="small text-muted mb-2">' . format_date($row["created_at"]) . '</p>';
        echo '<p class="small mb-1">' . nl2br(htmlspecialchars($row["lecture_description"])) . '</p>';
        if ($row["lecture_content"]) {
            echo '<p class="small mb-2">' . nl2br(htmlspecialchars($row["lecture_content"])) . '</p>';
        }
        if ($row["video_link"]) {
            echo '<a href="' . htmlspecialchars($row["video_link"]) . '" target="_blank" class="btn btn-sm btn-outline-primary mb-2"><i class="fa-solid fa-play"></i> Watch Video</a>';
        }

        // materials linked to this lecture
        $mq = mysqli_prepare($conn, "SELECT * FROM material WHERE lecture_id = ?");
        mysqli_stmt_bind_param($mq, "i", $row["lecture_id"]);
        mysqli_stmt_execute($mq);
        $materials = mysqli_stmt_get_result($mq);

        if (mysqli_num_rows($materials) > 0) {
            echo '<div class="mt-2"><strong class="small">Materials:</strong><ul class="small mb-0">';
            while ($m = mysqli_fetch_assoc($materials)) {
                echo '<li><a href="../' . htmlspecialchars($m["file_path"]) . '" target="_blank"><i class="fa-solid fa-download"></i> ' . htmlspecialchars($m["material_title"]) . '</a></li>';
            }
            echo '</ul></div>';
        }
        echo '</div>';
    }
    ?>

    <h5 class="section-title mt-4">General Materials</h5>
    <div class="stat-card">
        <?php
        $gq = mysqli_prepare($conn, "SELECT * FROM material WHERE course_id = ? AND lecture_id IS NULL ORDER BY material_id DESC");
        mysqli_stmt_bind_param($gq, "i", $course_id);
        mysqli_stmt_execute($gq);
        $general = mysqli_stmt_get_result($gq);

        if (mysqli_num_rows($general) == 0) {
            echo '<p class="text-muted small mb-0">No general materials uploaded yet.</p>';
        }
        while ($g = mysqli_fetch_assoc($general)) {
            echo '<div class="d-flex justify-content-between border-bottom py-2 small">';
            echo '<span>' . htmlspecialchars($g["material_title"]) . '</span>';
            echo '<a href="../' . htmlspecialchars($g["file_path"]) . '" target="_blank"><i class="fa-solid fa-download"></i> Download</a>';
            echo '</div>';
        }
        ?>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
