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
<title>Register - LMS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width:480px;">
        <div class="text-center mb-4">
            <div class="brand-logo fs-3"><i class="fa-solid fa-graduation-cap"></i> LMS Portal</div>
            <p class="text-muted mb-0">Create your account</p>
        </div>

        <div id="formMessage"></div>

        <form id="registerForm" onsubmit="return handleRegister(event)">
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone">
            </div>
            <div class="mb-3">
                <label class="form-label">I am registering as</label>
                <select class="form-select" id="role" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="registerBtn">Create Account</button>
        </form>

        <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script src="assets/js/script.js"></script>
<script>
function handleRegister(event) {
    event.preventDefault();

    var firstName = document.getElementById("first_name").value.trim();
    var lastName = document.getElementById("last_name").value.trim();
    var email = document.getElementById("email").value.trim();
    var phone = document.getElementById("phone").value.trim();
    var role = document.getElementById("role").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;

    if (firstName == "" || lastName == "" || email == "" || password == "" || confirmPassword == "") {
        showMessage("formMessage", "Please fill in all required fields.", "error");
        return false;
    }

    if (!isValidEmail(email)) {
        showMessage("formMessage", "Please enter a valid email address.", "error");
        return false;
    }

    if (password.length < 6) {
        showMessage("formMessage", "Password must be at least 6 characters.", "error");
        return false;
    }

    if (password != confirmPassword) {
        showMessage("formMessage", "Passwords do not match.", "error");
        return false;
    }

    var btn = document.getElementById("registerBtn");
    btn.disabled = true;
    btn.innerHTML = "Creating account...";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process/auth_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            btn.disabled = false;
            btn.innerHTML = "Create Account";

            var response = xhr.responseText.trim();

            if (response == "success") {
                showMessage("formMessage", "Account created successfully! Redirecting to login...", "success");
                setTimeout(function () {
                    window.location.href = "login.php";
                }, 1500);
            } else if (response == "duplicate") {
                showMessage("formMessage", "An account with this email already exists.", "error");
            } else {
                showMessage("formMessage", "Something went wrong. Please try again.", "error");
            }
        }
    };

    xhr.send(
        "action=register" +
        "&first_name=" + encodeURIComponent(firstName) +
        "&last_name=" + encodeURIComponent(lastName) +
        "&email=" + encodeURIComponent(email) +
        "&phone=" + encodeURIComponent(phone) +
        "&role=" + encodeURIComponent(role) +
        "&password=" + encodeURIComponent(password)
    );

    return false;
}
</script>
</body>
</html>
