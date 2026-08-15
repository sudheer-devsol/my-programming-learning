<?php
// ==========================================================
// Save Personal Theme (used by both admin/theme.php and
// user/theme.php). Settings are stored per user in the
// `setting` table as simple key/value rows.
// ==========================================================

session_start();

include "../config/database.php";

// ================Check Login=================================

if(!isset($_SESSION["user_id"])){

    echo "login_required";
    exit;
}

$user_id = $_SESSION["user_id"];

// The theme fields we allow saving. Admin only sends 3 of
// these (background_color, accent_color, font_size), the
// traveler theme page sends all 5 — either way we only
// save the ones that were actually posted.

$theme_keys = array("background_color", "card_color", "accent_color", "font_family", "font_size");


// ================Reset Theme (delete personal settings)=======

if(isset($_POST["reset_theme"])){

    foreach($theme_keys as $key){

        $delete_query = "DELETE FROM setting WHERE user_id = ? AND setting_key = ?";

        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "is", $user_id, $key);
        mysqli_stmt_execute($delete_stmt);
    }

    echo "success";
    exit;
}


// ================Save Theme====================================

if(isset($_POST["save_theme"])){

    // Allowed font choices offered by the theme settings pages
    $allowed_fonts = array("Work Sans", "Georgia", "Verdana", "Trebuchet MS");

    // Allowed font sizes offered by the theme settings pages
    $allowed_font_sizes = array("14", "16", "18");

    foreach($theme_keys as $key){

        if(!isset($_POST[$key]) || $_POST[$key] == ""){
            continue;
        }

        $value = trim($_POST[$key]);

        // Each field is validated against the exact format the
        // theme picker UI can actually produce. This matters because
        // these values get printed straight into a <style> block on
        // every page, so anything unexpected must be rejected rather
        // than saved.

        if($key == "background_color" || $key == "card_color" || $key == "accent_color"){

            if(!preg_match("/^#[0-9A-Fa-f]{6}$/", $value)){
                continue;
            }
        }
        else if($key == "font_family"){

            if(!in_array($value, $allowed_fonts)){
                continue;
            }
        }
        else if($key == "font_size"){

            if(!in_array($value, $allowed_font_sizes)){
                continue;
            }
        }

        // Check if this user already has a saved value for this setting
        $check_query = "SELECT setting_id FROM setting WHERE user_id = ? AND setting_key = ?";

        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "is", $user_id, $key);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if(mysqli_num_rows($check_result) > 0){

            // ==========Update Existing Setting====================

            $existing_row = mysqli_fetch_assoc($check_result);
            $setting_id = $existing_row["setting_id"];

            $update_query = "UPDATE setting SET setting_value = ?, setting_status = 'Active', updated_at = NOW() WHERE setting_id = ?";

            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "si", $value, $setting_id);
            mysqli_stmt_execute($update_stmt);

        }
        else{

            // ==========Insert New Setting====================

            $insert_query = "INSERT INTO setting(user_id, setting_key, setting_value, setting_status, created_at)
            VALUES(?, ?, ?, 'Active', NOW())";

            $insert_stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "iss", $user_id, $key, $value);
            mysqli_stmt_execute($insert_stmt);
        }
    }

    echo "success";
    exit;
}

echo "Invalid Request.";
?>
