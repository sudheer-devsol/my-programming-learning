<?php
require_once "../includes/functions.php";
require_login();

$action = isset($_POST["action"]) ? $_POST["action"] : "";
$user_id = $_SESSION["user_id"];

// ------------------------------------------------------------
// UPDATE PROFILE
// ------------------------------------------------------------
if ($action == "update_profile") {

    $first_name = clean_input($conn, $_POST["first_name"]);
    $last_name = clean_input($conn, $_POST["last_name"]);
    $phone = clean_input($conn, $_POST["phone"]);

    if ($first_name == "" || $last_name == "") {
        echo "error";
        exit;
    }

    $query = "UPDATE user SET first_name = ?, last_name = ?, phone = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $first_name, $last_name, $phone, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["first_name"] = $first_name;
        $_SESSION["last_name"] = $last_name;
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

// ------------------------------------------------------------
// CHANGE PASSWORD
// ------------------------------------------------------------
if ($action == "change_password") {

    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];

    $query = "SELECT password FROM user WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if (!password_verify($current_password, $row["password"])) {
        echo "wrong_password";
        exit;
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update = "UPDATE user SET password = ? WHERE user_id = ?";
    $stmt2 = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt2, "si", $hashed_password, $user_id);

    if (mysqli_stmt_execute($stmt2)) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}

echo "error";
?>
