<?php
// ===============Include the admin session=========


include "../includes/admin-session.php";

include "../config/database.php";

// ================Load Site Default Theme==========================

$default_theme_value = "light";

$default_theme_query = "SELECT setting_value FROM setting WHERE user_id IS NULL AND setting_key = 'default_theme'";
$default_theme_result = mysqli_query($conn, $default_theme_query);

if($default_theme_result && mysqli_num_rows($default_theme_result) > 0){

    $default_theme_row = mysqli_fetch_assoc($default_theme_result);
    $default_theme_value = $default_theme_row["setting_value"];
}


// ================Load Admin's Personal Theme==========================

$admin_id = $_SESSION["user_id"];

$admin_theme = array(
    "background_color" => "#F2F4EF",
    "accent_color" => "#0E5C56",
    "font_size" => "16"
);

$admin_theme_query = "SELECT setting_key, setting_value FROM setting WHERE user_id = ? AND setting_key IN ('background_color', 'accent_color', 'font_size')";

$admin_theme_stmt = mysqli_prepare($conn, $admin_theme_query);
mysqli_stmt_bind_param($admin_theme_stmt, "i", $admin_id);
mysqli_stmt_execute($admin_theme_stmt);
$admin_theme_result = mysqli_stmt_get_result($admin_theme_stmt);

while($admin_theme_row = mysqli_fetch_assoc($admin_theme_result)){

    $admin_theme[$admin_theme_row["setting_key"]] = $admin_theme_row["setting_value"];
}

$page_title = "Theme Management";
$dash_role = "admin";
include "../includes/dash-header.php";
$active = "theme";
?>

<div class="dash-shell">
    <?php include "../includes/admin-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">Theme Management</h2>
                <p class="mb-0">Set the site-wide default theme, or customize your own admin view.</p>
            </div>
        </div>

        <div id="themeMgmtAlert" class="form-alert"></div>

        <ul class="nav nav-tabs mb-4" style="border-color:var(--line);">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#defaultThemeTab" style="font-family:var(--font-label);text-transform:uppercase;letter-spacing:.05em;font-size:.85rem;">Default Theme</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#adminThemeTab" style="font-family:var(--font-label);text-transform:uppercase;letter-spacing:.05em;font-size:.85rem;">My Personal Theme</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="defaultThemeTab">
                <div class="panel-tp">
                    <div class="panel-body">
                        <p style="max-width:640px;">Applied automatically to every new user the moment their account is approved. Existing users keep whatever they've personally set.</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="card-tp p-3 text-center theme-option <?= ($default_theme_value=="light") ? "selected" : ""; ?>" data-theme="light" onclick="selectThemeOption(this);" style="<?= ($default_theme_value=="light") ? "border-color:var(--teal);" : ""; ?>">
                                    <div style="height:70px;border-radius:8px;background:#F2F4EF;border:1px solid var(--line);margin-bottom:10px;"></div>
                                    <strong>Light Theme</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-tp p-3 text-center theme-option <?= ($default_theme_value=="dark") ? "selected" : ""; ?>" data-theme="dark" onclick="selectThemeOption(this);" style="<?= ($default_theme_value=="dark") ? "border-color:var(--teal);" : ""; ?>">
                                    <div style="height:70px;border-radius:8px;background:#101820;margin-bottom:10px;"></div>
                                    <strong>Dark Theme</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-tp p-3 text-center theme-option <?= ($default_theme_value=="warm") ? "selected" : ""; ?>" data-theme="warm" onclick="selectThemeOption(this);" style="<?= ($default_theme_value=="warm") ? "border-color:var(--teal);" : ""; ?>">
                                    <div style="height:70px;border-radius:8px;background:#F4EDE1;border:1px solid var(--line);margin-bottom:10px;"></div>
                                    <strong>Warm Theme</strong>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-teal mt-4" id="saveDefaultThemeBtn" onclick="saveDefaultTheme();">Set as Site Default</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="adminThemeTab">
                <div class="panel-tp">
                    <div class="panel-body">
                        <p style="max-width:640px;">Only affects your own admin interface — it will not change what regular users see.</p>
                        <div class="row g-4 form-tp">
                            <div class="col-md-4">
                                <label class="d-block mb-2">Background</label>
                                <div class="d-flex gap-2" id="adminBgSwatches">
                                    <div class="theme-swatch <?= ($admin_theme["background_color"]=="#F2F4EF") ? "selected" : ""; ?>" data-value="#F2F4EF" style="background:#F2F4EF;" onclick="selectSwatch(this, 'adminBgSwatches');"></div>
                                    <div class="theme-swatch <?= ($admin_theme["background_color"]=="#101820") ? "selected" : ""; ?>" data-value="#101820" style="background:#101820;" onclick="selectSwatch(this, 'adminBgSwatches');"></div>
                                    <div class="theme-swatch <?= ($admin_theme["background_color"]=="#F4EDE1") ? "selected" : ""; ?>" data-value="#F4EDE1" style="background:#F4EDE1;" onclick="selectSwatch(this, 'adminBgSwatches');"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="d-block mb-2">Accent</label>
                                <div class="d-flex gap-2" id="adminAccentSwatches">
                                    <div class="theme-swatch <?= ($admin_theme["accent_color"]=="#0E5C56") ? "selected" : ""; ?>" data-value="#0E5C56" style="background:#0E5C56;" onclick="selectSwatch(this, 'adminAccentSwatches');"></div>
                                    <div class="theme-swatch <?= ($admin_theme["accent_color"]=="#E7A33E") ? "selected" : ""; ?>" data-value="#E7A33E" style="background:#E7A33E;" onclick="selectSwatch(this, 'adminAccentSwatches');"></div>
                                    <div class="theme-swatch <?= ($admin_theme["accent_color"]=="#A84B34") ? "selected" : ""; ?>" data-value="#A84B34" style="background:#A84B34;" onclick="selectSwatch(this, 'adminAccentSwatches');"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Font Size</label>
                                <select class="form-select" id="adminFontSize">
                                    <option value="14" <?= ($admin_theme["font_size"]=="14") ? "selected" : ""; ?>>Small</option>
                                    <option value="16" <?= ($admin_theme["font_size"]=="16") ? "selected" : ""; ?>>Default</option>
                                    <option value="18" <?= ($admin_theme["font_size"]=="18") ? "selected" : ""; ?>>Large</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-teal mt-4" id="saveAdminThemeBtn" onclick="saveAdminTheme();">Save My Theme</button>
                        <button class="btn btn-ghost mt-4" id="resetAdminThemeBtn" onclick="resetAdminTheme();">Reset to Default</button>
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
Admin Theme Management - Default Theme + Personal Theme (AJAX)
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

function selectThemeOption(opt){
    var themeOptions = document.querySelectorAll(".theme-option");

    for(var i = 0; i < themeOptions.length; i++){
        themeOptions[i].classList.remove("selected");
        themeOptions[i].style.borderColor = "var(--line)";
    }

    opt.classList.add("selected");
    opt.style.borderColor = "var(--teal)";
}

function saveDefaultTheme(){
    var selected = document.querySelector(".theme-option.selected");
    var themeValue = selected.getAttribute("data-theme");

    var xhr = createXHR();

    xhr.open("POST", "../ajax/save-default-theme.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            if(xhr.status == 200 && xhr.responseText == "success"){
                showAlert("success", "Default theme updated for all new users.");
            }else{
                showAlert("error", xhr.responseText || "Something went wrong.");
            }
        }
    };

    xhr.send("default_theme=" + encodeURIComponent(themeValue) + "&save_default_theme=1");
}

function selectSwatch(swatch, groupId){
    var group = document.querySelectorAll("#" + groupId + " .theme-swatch");

    for(var i = 0; i < group.length; i++){
        group[i].classList.remove("selected");
    }

    swatch.classList.add("selected");
}

function getSelectedSwatch(groupId){
    var group = document.querySelectorAll("#" + groupId + " .theme-swatch");
    var val = null;

    for(var i = 0; i < group.length; i++){
        if(group[i].classList.contains("selected")){
            val = group[i].getAttribute("data-value");
        }
    }

    return val;
}

function saveAdminTheme(){
    var adminFontSize = document.getElementById("adminFontSize");

    var xhr = createXHR();

    xhr.open("POST", "../ajax/save-theme.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            if(xhr.status == 200 && xhr.responseText == "success"){
                showAlert("success", "Your personal theme was saved.");
            }else{
                showAlert("error", xhr.responseText || "Something went wrong.");
            }
        }
    };

    var params = "background_color=" + encodeURIComponent(getSelectedSwatch("adminBgSwatches")) +
        "&accent_color=" + encodeURIComponent(getSelectedSwatch("adminAccentSwatches")) +
        "&font_size=" + encodeURIComponent(adminFontSize.value) +
        "&save_theme=1";

    xhr.send(params);
}

function resetAdminTheme(){
    var xhr = createXHR();

    xhr.open("POST", "../ajax/save-theme.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200 && xhr.responseText == "success"){
            showAlert("success", "Your theme was reset to the site default.");
            setTimeout(function(){ location.reload(); }, 700);
        }
    };

    xhr.send("reset_theme=1");
}

function showAlert(type, text){
    var themeMgmtAlert = document.getElementById("themeMgmtAlert");

    themeMgmtAlert.className = "form-alert " + type;
    themeMgmtAlert.textContent = text;
    themeMgmtAlert.style.display = "block";
}
</script>
</body>
</html>
