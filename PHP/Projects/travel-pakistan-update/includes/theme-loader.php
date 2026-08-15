<?php
// ==========================================================
// Site & Personal Theme Loader
// Reads the theme settings saved by admin/theme.php and
// user/theme.php from the `setting` table and prints CSS
// variable overrides so the chosen theme is actually applied
// across every page — not just shown on the settings page.
//
// This file always lives in includes/. It uses __DIR__ (not a
// plain relative path) when including config/database.php, so
// the path stays correct no matter which page originally
// included header.php / dash-header.php — PHP resolves plain
// "../" paths against the directly-requested page's own folder,
// not against this file's folder, which is what caused this to
// break before.
// ==========================================================

if(!isset($conn)){
    include __DIR__ . "/../config/database.php";
}

if(session_status() === PHP_SESSION_NONE){
    session_start();
}


// -----------------------------------------------------------
// 1. Site-wide default theme preset (light / dark / warm)
//    Each preset is a full, readable color set — not just a
//    background swap — so text stays legible on every option.
// -----------------------------------------------------------

$site_theme_presets = array(

    "light" => array(
        "paper" => "#F2F4EF", "card" => "#FFFFFF", "accent" => "#0E5C56",
        "ink" => "#17242B", "ink_soft" => "#4A5A61", "line" => "#DCE1D9"
    ),

    "dark" => array(
        "paper" => "#101820", "card" => "#1B2430", "accent" => "#E7A33E",
        "ink" => "#F2F4EF", "ink_soft" => "#B7C0C6", "line" => "#2B3947"
    ),

    "warm" => array(
        "paper" => "#F4EDE1", "card" => "#FFFFFF", "accent" => "#A84B34",
        "ink" => "#17242B", "ink_soft" => "#4A5A61", "line" => "#E4D9C6"
    )
);

$site_default_theme = "light";

$site_theme_query = "SELECT setting_value FROM setting WHERE user_id IS NULL AND setting_key = 'default_theme'";
$site_theme_result = mysqli_query($conn, $site_theme_query);

if($site_theme_result && mysqli_num_rows($site_theme_result) > 0){

    $site_theme_row = mysqli_fetch_assoc($site_theme_result);

    if(isset($site_theme_presets[$site_theme_row["setting_value"]])){

        $site_default_theme = $site_theme_row["setting_value"];
    }
}

// This array holds whatever theme values will actually be printed below.
// It starts as the site default, then personal settings (if any) override it.
$active_theme = $site_theme_presets[$site_default_theme];


// -----------------------------------------------------------
// 2. Personal theme overrides (logged-in users only)
// -----------------------------------------------------------

if(isset($_SESSION["user_id"])){

    $theme_user_id = $_SESSION["user_id"];

    $personal_theme_query = "SELECT setting_key, setting_value FROM setting
    WHERE user_id = ? AND setting_key IN ('background_color', 'card_color', 'accent_color', 'font_family', 'font_size')";

    $personal_theme_stmt = mysqli_prepare($conn, $personal_theme_query);
    mysqli_stmt_bind_param($personal_theme_stmt, "i", $theme_user_id);
    mysqli_stmt_execute($personal_theme_stmt);
    $personal_theme_result = mysqli_stmt_get_result($personal_theme_stmt);

    while($personal_theme_row = mysqli_fetch_assoc($personal_theme_result)){

        $setting_key = $personal_theme_row["setting_key"];
        $setting_value = $personal_theme_row["setting_value"];

        // Re-validate here too (not just at save time) since this
        // value is about to be printed straight into a <style> block.
        $is_valid_color = preg_match("/^#[0-9A-Fa-f]{6}$/", $setting_value);
        $is_valid_font = in_array($setting_value, array("Work Sans", "Georgia", "Verdana", "Trebuchet MS"));
        $is_valid_size = in_array($setting_value, array("14", "16", "18"));

        if($setting_key == "background_color" && $is_valid_color){

            $active_theme["paper"] = $setting_value;

            // The one dark background swatch offered in the theme
            // settings page needs light text to stay readable.
            if($setting_value == "#101820"){

                $active_theme["ink"] = "#F2F4EF";
                $active_theme["ink_soft"] = "#B7C0C6";
                $active_theme["line"] = "#2B3947";
            }
        }
        else if($setting_key == "card_color" && $is_valid_color){

            $active_theme["card"] = $setting_value;
        }
        else if($setting_key == "accent_color" && $is_valid_color){

            $active_theme["accent"] = $setting_value;
        }
        else if($setting_key == "font_family" && $is_valid_font){

            $active_theme["font_family"] = $setting_value;
        }
        else if($setting_key == "font_size" && $is_valid_size){

            $active_theme["font_size"] = $setting_value;
        }
    }
}
?>
<style>
:root{
    --paper: <?= htmlspecialchars($active_theme["paper"]); ?>;
    --paper-raised: <?= htmlspecialchars($active_theme["card"]); ?>;
    --teal: <?= htmlspecialchars($active_theme["accent"]); ?>;
    --ink: <?= htmlspecialchars($active_theme["ink"]); ?>;
    --ink-soft: <?= htmlspecialchars($active_theme["ink_soft"]); ?>;
    --line: <?= htmlspecialchars($active_theme["line"]); ?>;
    <?php if(isset($active_theme["font_family"])){ ?>
    --font-body: "<?= htmlspecialchars($active_theme["font_family"]); ?>", -apple-system, Segoe UI, sans-serif;
    <?php } ?>
}
<?php if(isset($active_theme["font_size"])){ ?>
html{
    font-size: <?= htmlspecialchars($active_theme["font_size"]); ?>px;
}
<?php } ?>
</style>
