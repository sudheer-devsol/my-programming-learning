<?php
require_once "includes/functions.php";

if (is_logged_in()) {
    redirect_to_dashboard($_SESSION["role"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand-logo fs-3"><i class="fa-solid fa-graduation-cap"></i> LMS Portal</div>
            <p class="text-muted mb-0">Sign in to continue learning</p>
        </div>

        <div id="formMessage"></div>

        <form id="loginForm" onsubmit="return handleLogin(event)">
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <a href="forgot_password.php" class="small">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="loginBtn">Login</button>
        </form>

        <p class="text-center mt-3 mb-0">Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
function handleLogin(event) {
    event.preventDefault();

    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value.trim();

    if (email == "" || password == "") {
        showMessage("formMessage", "Please fill in all fields.", "error");
        return false;
    }

    if (!isValidEmail(email)) {
        showMessage("formMessage", "Please enter a valid email address.", "error");
        return false;
    }

    var btn = document.getElementById("loginBtn");
    btn.disabled = true;
    btn.innerHTML = "Logging in...";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process/auth_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            btn.disabled = false;
            btn.innerHTML = "Login";

            if (xhr.status == 200) {
                var response = xhr.responseText.trim();

                if (response == "success_admin") {
                    window.location.href = "admin/dashboard.php";
                } else if (response == "success_teacher") {
                    window.location.href = "teacher/dashboard.php";
                } else if (response == "success_student") {
                    window.location.href = "student/dashboard.php";
                } else if (response == "invalid") {
                    showMessage("formMessage", "Invalid email or password.", "error");
                } else if (response == "inactive") {
                    showMessage("formMessage", "Your account has been deactivated. Contact the admin.", "error");
                } else {
                    showMessage("formMessage", "Something went wrong. Please try again.", "error");
                }
            }
        }
    };

    xhr.send("action=login&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));

    return false;
}
</script>
</body>
</html>
