<?php
require_once "../includes/functions.php";
require_role("student");

$page_title = "My Profile";
$asset_path = "../";

$query = "SELECT * FROM user WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

include "../includes/head.php";
include "../includes/sidebar_student.php";
?>
<div class="main-content">
    <?php include "../includes/topbar.php"; ?>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="stat-card">
                <h6 class="section-title">Profile Information</h6>
                <div id="profileMessage"></div>
                <form id="profileForm" onsubmit="return updateProfile(event)">
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">First Name</label><input type="text" class="form-control" id="first_name" value="<?php echo htmlspecialchars($user["first_name"]); ?>" required></div>
                        <div class="col-6 mb-3"><label class="form-label">Last Name</label><input type="text" class="form-control" id="last_name" value="<?php echo htmlspecialchars($user["last_name"]); ?>" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="<?php echo htmlspecialchars($user["email"]); ?>" disabled></div>
                    <div class="mb-3"><label class="form-label">Phone</label><input type="text" class="form-control" id="phone" value="<?php echo htmlspecialchars($user["phone"]); ?>"></div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="stat-card">
                <h6 class="section-title">Change Password</h6>
                <div id="passwordMessage"></div>
                <form id="passwordForm" onsubmit="return changePassword(event)">
                    <div class="mb-3"><label class="form-label">Current Password</label><input type="password" class="form-control" id="current_password" required></div>
                    <div class="mb-3"><label class="form-label">New Password</label><input type="password" class="form-control" id="new_password" required></div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include "../includes/foot.php"; ?>
<script>
function updateProfile(event) {
    event.preventDefault();
    var firstName = document.getElementById("first_name").value.trim();
    var lastName = document.getElementById("last_name").value.trim();
    var phone = document.getElementById("phone").value.trim();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/profile_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                showMessage("profileMessage", "Profile updated successfully.", "success");
            } else {
                showMessage("profileMessage", "Could not update profile.", "error");
            }
        }
    };
    xhr.send("action=update_profile&first_name=" + encodeURIComponent(firstName) + "&last_name=" + encodeURIComponent(lastName) + "&phone=" + encodeURIComponent(phone));
    return false;
}

function changePassword(event) {
    event.preventDefault();
    var currentPassword = document.getElementById("current_password").value;
    var newPassword = document.getElementById("new_password").value;

    if (newPassword.length < 6) {
        showMessage("passwordMessage", "New password must be at least 6 characters.", "error");
        return false;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/profile_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();
            if (response == "success") {
                showMessage("passwordMessage", "Password updated successfully.", "success");
                document.getElementById("passwordForm").reset();
            } else if (response == "wrong_password") {
                showMessage("passwordMessage", "Current password is incorrect.", "error");
            } else {
                showMessage("passwordMessage", "Could not update password.", "error");
            }
        }
    };
    xhr.send("action=change_password&current_password=" + encodeURIComponent(currentPassword) + "&new_password=" + encodeURIComponent(newPassword));
    return false;
}
</script>
