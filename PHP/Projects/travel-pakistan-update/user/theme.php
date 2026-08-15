<?php
// ===============Include the user session=========

include "../includes/user-session.php";

include "../config/database.php";

// ================Load User's Saved Theme==========================

$my_id = $_SESSION["user_id"];

$my_theme = array(
    "background_color" => "#F2F4EF",
    "card_color" => "#FFFFFF",
    "accent_color" => "#0E5C56",
    "font_family" => "Work Sans",
    "font_size" => "16"
);

$my_theme_query = "SELECT setting_key, setting_value FROM setting WHERE user_id = ? AND setting_key IN ('background_color', 'card_color', 'accent_color', 'font_family', 'font_size')";

$my_theme_stmt = mysqli_prepare($conn, $my_theme_query);
mysqli_stmt_bind_param($my_theme_stmt, "i", $my_id);
mysqli_stmt_execute($my_theme_stmt);
$my_theme_result = mysqli_stmt_get_result($my_theme_stmt);

while($my_theme_row = mysqli_fetch_assoc($my_theme_result)){

    $my_theme[$my_theme_row["setting_key"]] = $my_theme_row["setting_value"];
}

$page_title = "Theme Settings";
$dash_role = "user";
include "../includes/dash-header.php";
$active = "theme";
?>

<div class="dash-shell">
    <?php include "../includes/user-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Theme Settings</h2>
                <p class="mb-0">Personalize how the site looks for you. Saved per-account in the <code>setting</code> table.</p>
            </div>
            <button class="btn btn-ghost btn-sm" id="resetThemeBtn" onclick="resetTheme();"><i class="bi bi-arrow-counterclockwise"></i> Reset to Default</button>
        </div>

        <div id="themeAlert" class="form-alert"></div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="panel-tp">
                    <div class="panel-head"><strong>Colors</strong></div>
                    <div class="panel-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="d-block mb-2" style="font-family:var(--font-label);text-transform:uppercase;font-size:.8rem;color:var(--ink-soft);">Background</label>
                                <div class="d-flex gap-2" id="bgSwatches">
                                    <div class="theme-swatch <?= ($my_theme["background_color"]=="#F2F4EF") ? "selected" : ""; ?>" data-value="#F2F4EF" style="background:#F2F4EF;" onclick="handleSwatchClick(this, 'bgSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["background_color"]=="#FFFFFF") ? "selected" : ""; ?>" data-value="#FFFFFF" style="background:#FFFFFF;" onclick="handleSwatchClick(this, 'bgSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["background_color"]=="#101820") ? "selected" : ""; ?>" data-value="#101820" style="background:#101820;" onclick="handleSwatchClick(this, 'bgSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["background_color"]=="#F4EDE1") ? "selected" : ""; ?>" data-value="#F4EDE1" style="background:#F4EDE1;" onclick="handleSwatchClick(this, 'bgSwatches');"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block mb-2" style="font-family:var(--font-label);text-transform:uppercase;font-size:.8rem;color:var(--ink-soft);">Card Color</label>
                                <div class="d-flex gap-2" id="cardSwatches">
                                    <div class="theme-swatch <?= ($my_theme["card_color"]=="#FFFFFF") ? "selected" : ""; ?>" data-value="#FFFFFF" style="background:#FFFFFF;" onclick="handleSwatchClick(this, 'cardSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["card_color"]=="#F7F7F2") ? "selected" : ""; ?>" data-value="#F7F7F2" style="background:#F7F7F2;" onclick="handleSwatchClick(this, 'cardSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["card_color"]=="#1B2430") ? "selected" : ""; ?>" data-value="#1B2430" style="background:#1B2430;" onclick="handleSwatchClick(this, 'cardSwatches');"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block mb-2" style="font-family:var(--font-label);text-transform:uppercase;font-size:.8rem;color:var(--ink-soft);">Accent</label>
                                <div class="d-flex gap-2" id="accentSwatches">
                                    <div class="theme-swatch <?= ($my_theme["accent_color"]=="#0E5C56") ? "selected" : ""; ?>" data-value="#0E5C56" style="background:#0E5C56;" onclick="handleSwatchClick(this, 'accentSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["accent_color"]=="#E7A33E") ? "selected" : ""; ?>" data-value="#E7A33E" style="background:#E7A33E;" onclick="handleSwatchClick(this, 'accentSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["accent_color"]=="#A84B34") ? "selected" : ""; ?>" data-value="#A84B34" style="background:#A84B34;" onclick="handleSwatchClick(this, 'accentSwatches');"></div>
                                    <div class="theme-swatch <?= ($my_theme["accent_color"]=="#5C2333") ? "selected" : ""; ?>" data-value="#5C2333" style="background:#5C2333;" onclick="handleSwatchClick(this, 'accentSwatches');"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-tp mt-4">
                    <div class="panel-head"><strong>Typography</strong></div>
                    <div class="panel-body form-tp">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Font Family</label>
                                <select class="form-select" id="fontFamilySelect" onchange="handleFontFamilyChange();">
                                    <option value="Work Sans" <?= ($my_theme["font_family"]=="Work Sans") ? "selected" : ""; ?>>Work Sans (Default)</option>
                                    <option value="Georgia" <?= ($my_theme["font_family"]=="Georgia") ? "selected" : ""; ?>>Georgia</option>
                                    <option value="Verdana" <?= ($my_theme["font_family"]=="Verdana") ? "selected" : ""; ?>>Verdana</option>
                                    <option value="Trebuchet MS" <?= ($my_theme["font_family"]=="Trebuchet MS") ? "selected" : ""; ?>>Trebuchet MS</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Font Size</label>
                                <select class="form-select" id="fontSizeSelect" onchange="handleFontSizeChange();">
                                    <option value="14" <?= ($my_theme["font_size"]=="14") ? "selected" : ""; ?>>Small (14px)</option>
                                    <option value="16" <?= ($my_theme["font_size"]=="16") ? "selected" : ""; ?>>Default (16px)</option>
                                    <option value="18" <?= ($my_theme["font_size"]=="18") ? "selected" : ""; ?>>Large (18px)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-teal mt-4" id="saveThemeBtn" onclick="saveTheme();"><i class="bi bi-check-lg"></i> Save Theme</button>
            </div>

            <div class="col-lg-5">
                <div class="panel-tp" style="position:sticky;top:96px;">
                    <div class="panel-head"><strong>Live Preview</strong></div>
                    <div class="panel-body">
                        <div id="themePreviewBg" style="background:<?= htmlspecialchars($my_theme["background_color"]); ?>;border-radius:var(--radius-md);padding:20px;transition:.2s;">
                            <div id="themePreviewCard" style="background:<?= htmlspecialchars($my_theme["card_color"]); ?>;border-radius:var(--radius-sm);padding:18px;border:1px solid var(--line);">
                                <div id="themePreviewAccent" style="width:34px;height:34px;border-radius:50%;background:<?= htmlspecialchars($my_theme["accent_color"]); ?>;margin-bottom:12px;"></div>
                                <h4 id="themePreviewTitle" style="font-family:<?= htmlspecialchars($my_theme["font_family"]); ?>;">Hunza Valley Guide</h4>
                                <p id="themePreviewText" style="font-size:<?= htmlspecialchars($my_theme["font_size"]); ?>px;font-family:<?= htmlspecialchars($my_theme["font_family"]); ?>;">This is how your card text will look across the site.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/*
==========================================
Theme Settings Page - Swatch Picker + Live Preview + AJAX Save
==========================================
*/

function createXHR(){
    var xhr = null;

    if(window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
    }else{
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

function handleSwatchClick(swatch, groupId){
    var swatches = document.querySelectorAll("#" + groupId + " .theme-swatch");

    for(var i = 0; i < swatches.length; i++){
        swatches[i].classList.remove("selected");
    }

    swatch.classList.add("selected");

    var value = swatch.getAttribute("data-value");

    if(groupId == "bgSwatches"){
        document.getElementById("themePreviewBg").style.background = value;
    }else if(groupId == "cardSwatches"){
        document.getElementById("themePreviewCard").style.background = value;
    }else if(groupId == "accentSwatches"){
        document.getElementById("themePreviewAccent").style.background = value;
    }
}

function getSelectedValue(groupId){
    var swatches = document.querySelectorAll("#" + groupId + " .theme-swatch");
    var selected = null;

    for(var i = 0; i < swatches.length; i++){
        if(swatches[i].classList.contains("selected")){
            selected = swatches[i].getAttribute("data-value");
        }
    }

    return selected;
}

function handleFontFamilyChange(){
    var fontFamilySelect = document.getElementById("fontFamilySelect");
    var previewTitle = document.getElementById("themePreviewTitle");
    var previewText = document.getElementById("themePreviewText");

    previewTitle.style.fontFamily = fontFamilySelect.value;
    previewText.style.fontFamily = fontFamilySelect.value;
}

function handleFontSizeChange(){
    var fontSizeSelect = document.getElementById("fontSizeSelect");
    var previewText = document.getElementById("themePreviewText");

    previewText.style.fontSize = fontSizeSelect.value + "px";
}

/*
==========================================
AJAX Request - Save Theme (writes rows into `setting` table)
==========================================
*/
function saveTheme(){
    var fontFamilySelect = document.getElementById("fontFamilySelect");
    var fontSizeSelect = document.getElementById("fontSizeSelect");

    var xhr = createXHR();

    xhr.open("POST", "../ajax/save-theme.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            if(xhr.status == 200 && xhr.responseText == "success"){
                showAlert("success", "Theme saved.");
            }else{
                showAlert("error", xhr.responseText || "Something went wrong.");
            }
        }
    };

    var params = "background_color=" + encodeURIComponent(getSelectedValue("bgSwatches")) +
        "&card_color=" + encodeURIComponent(getSelectedValue("cardSwatches")) +
        "&accent_color=" + encodeURIComponent(getSelectedValue("accentSwatches")) +
        "&font_family=" + encodeURIComponent(fontFamilySelect.value) +
        "&font_size=" + encodeURIComponent(fontSizeSelect.value) +
        "&save_theme=1";

    xhr.send(params);
}

function resetTheme(){
    var xhr = createXHR();

    xhr.open("POST", "../ajax/save-theme.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            if(xhr.status == 200 && xhr.responseText == "success"){
                showAlert("success", "Theme reset to default.");
                setTimeout(function(){ location.reload(); }, 700);
            }
        }
    };

    xhr.send("reset_theme=1");
}

function showAlert(type, text){
    var themeAlert = document.getElementById("themeAlert");

    themeAlert.className = "form-alert " + type;
    themeAlert.textContent = text;
    themeAlert.style.display = "block";
}
</script>
</body>
</html>
