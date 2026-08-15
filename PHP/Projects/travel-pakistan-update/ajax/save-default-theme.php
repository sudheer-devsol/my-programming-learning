<?php
// ==========================================================
// Save Site-Wide Default Theme (admin only). This is stored
// as one row in the `setting` table with user_id left blank,
// meaning it applies to the whole site rather than one person.
// ==========================================================

session_start();

include "../config/database.php";

// ================Check Admin Login=================================

if(!isset($_SESSION["user_id"]) || $_SESSION["role_id"] != 1){

    echo "Only admins can set the default theme.";
    exit;
}

if(isset($_POST["save_default_theme"]) && isset($_POST["default_theme"])){

    $default_theme = htmlspecialchars($_POST["default_theme"]);

    $allowed_themes = array("light", "dark", "warm");

    if(!in_array($default_theme, $allowed_themes)){

        echo "Invalid theme value.";
        exit;
    }

    // Check if a site default has already been saved
    $check_query = "SELECT setting_id FROM setting WHERE user_id IS NULL AND setting_key = 'default_theme'";

    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0){

        // ==========Update Existing Site Default====================

        $existing_row = mysqli_fetch_assoc($check_result);
        $setting_id = $existing_row["setting_id"];

        $update_query = "UPDATE setting SET setting_value = ?, setting_status = 'Active', updated_at = NOW() WHERE setting_id = ?";

        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $default_theme, $setting_id);
        mysqli_stmt_execute($update_stmt);

    }
    else{

        // ==========Insert New Site Default====================

        $insert_query = "INSERT INTO setting(user_id, setting_key, setting_value, setting_status, created_at)
        VALUES(NULL, 'default_theme', ?, 'Active', NOW())";

        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "s", $default_theme);
        mysqli_stmt_execute($insert_stmt);
    }

    echo "success";
    exit;
}

echo "Invalid Request.";
?>
