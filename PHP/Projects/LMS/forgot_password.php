<?php
require_once "includes/functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand-logo fs-3"><i class="fa-solid fa-key"></i> Reset Password</div>
            <p class="text-muted mb-0">Verify your identity to set a new password</p>
        </div>

        <div id="formMessage"></div>

        <form id="resetForm" onsubmit="return handleReset(event)">
            <div class="mb-3">
                <label class="form-label">Registered Email</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Registered Phone Number</label>
                <input type="text" class="form-control" id="phone" required>
                <div class="form-text">Used only to verify it's really you (no email/SMS server needed for this demo).</div>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="resetBtn">Reset Password</button>
        </form>

        <p class="text-center mt-3 mb-0"><a href="login.php">Back to login</a></p>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
function handleReset(event) {
    event.preventDefault();

    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var password = document.getElementById("password").value;

    if (email == "" || phone == "" || password == "") {
        showMessage("formMessage", "Please fill in all fields.", "error");
        return false;
    }

    if (password.length < 6) {
        showMessage("formMessage", "Password must be at least 6 characters.", "error");
        return false;
    }

    var btn = document.getElementById("resetBtn");
    btn.disabled = true;
    btn.innerHTML = "Processing...";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process/auth_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            btn.disabled = false;
            btn.innerHTML = "Reset Password";

            var response = xhr.responseText.trim();

            if (response == "success") {
                showMessage("formMessage", "Password reset successfully! Redirecting to login...", "success");
                setTimeout(function () {
                    window.location.href = "login.php";
                }, 1500);
            } else if (response == "notfound") {
                showMessage("formMessage", "No matching account found for that email and phone.", "error");
            } else {
                showMessage("formMessage", "Something went wrong. Please try again.", "error");
            }
        }
    };

    xhr.send(
        "action=reset_password" +
        "&email=" + encodeURIComponent(email) +
        "&phone=" + encodeURIComponent(phone) +
        "&password=" + encodeURIComponent(password)
    );

    return false;
}
</script>
</body>
</html>
