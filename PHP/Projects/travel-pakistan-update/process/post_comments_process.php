<?php
include "../config/database.php";

date_default_timezone_set("Asia/Karachi");

// Use $_REQUEST so it works whether sent via POST or GET
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "";

if ($action == "update_status") {
    $comment_id = isset($_REQUEST["comment_id"]) ? $_REQUEST["comment_id"] : "";
    $status_action = isset($_REQUEST["status_action"]) ? $_REQUEST["status_action"] : "";

    $status = ($status_action == "activate") ? "Active" : "InActive";

    $query = "UPDATE post_comment SET is_active = ? WHERE post_comment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $comment_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Unable to update comment status.";
    }
}
else if ($action == "delete_comment") {
    $comment_id = isset($_REQUEST["comment_id"]) ? $_REQUEST["comment_id"] : "";

    $query = "DELETE FROM post_comment WHERE post_comment_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $comment_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "Unable to delete comment.";
    }
}
else {
    echo "Invalid Request.";
}
?>
