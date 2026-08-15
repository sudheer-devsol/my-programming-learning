<?php
$page_title = "Log In";
$active_page = "";
include "includes/header.php";
?>

<section class="section" style="padding-top:64px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <div class="eyebrow justify-content-center">Welcome Back</div>
                        <h1 style="font-size:1.9rem;">Log In</h1>
                        <p class="mb-0">Follow provinces, comment on posts, and build your travel feed.</p>
                    </div>

                    <div id="loginAlert" class="form-alert"></div>

                    <form id="loginForm" class="form-tp" novalidate onsubmit="return submitLogin();">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="email">
                            <div class="field-error" id="err-loginEmail">Enter a valid email address.</div>
                        </div>
                        <div class="mb-2">
                            <label>Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password">
                            <div class="field-error" id="err-loginPassword">Password is required.</div>
                        </div>
                        <div class="d-flex justify-content-end mb-3">
                            <a href="forgot-password.php" style="font-size:.85rem;color:var(--teal);">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-teal w-100" id="loginSubmitBtn">Log In</button>
                    </form>

                    <p class="text-center mt-4 mb-0" style="font-size:.92rem;">Don't have an account? <a href="register.php" style="color:var(--teal);font-weight:600;">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <?php include "includes/footer.php"; ?> -->
<script>
/*
==========================================
Login Page - Validation + AJAX
==========================================
*/

function createXHR()
{
    var xhr = null;

    if(window.XMLHttpRequest)
    {
        xhr = new XMLHttpRequest();
    }
    else
    {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

function submitLogin()
{
    var email = document.getElementById("loginEmail");
    var password = document.getElementById("loginPassword");
    var submitBtn = document.getElementById("loginSubmitBtn");

    /*
    ==========================================
    Client Side Validation
    ==========================================
    */

    var valid = true;

    valid = validateField(email, "err-loginEmail", isValidEmail(email.value.trim())) && valid;

    valid = validateField(password, "err-loginPassword", password.value.trim() != "") && valid;

    if(!valid)
    {
        return false;
    }

    /*
    ==========================================
    AJAX Request
    ==========================================
    */

    var xhr = createXHR();

    xhr.open("POST", "process/login-process.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    submitBtn.disabled = true;
    submitBtn.textContent = "Logging in...";

    /*
    ==========================================
    Server Response
    ==========================================
    */

    xhr.onreadystatechange = function()
    {
        if(xhr.readyState == 4)
        {
            submitBtn.disabled = false;
            submitBtn.textContent = "Log In";

            if(xhr.status == 200)
            {
                var response = xhr.responseText;

                if(response == "success")
                {
                    window.location.href = "user/dashboard.php";
                }
                else if(response == "admin")
                {
                    window.location.href = "admin/dashboard.php";
                }
                else if(response == "pending")
                {
                    showAlert("error","Your account is still pending admin approval.");
                }
                else if(response == "rejected")
                {
                    showAlert("error","Your registration has been rejected.");
                }
                else if(response == "inactive")
                {
                    showAlert("error","Your account is inactive. Please contact the administrator.");
                }
                else
                {
                    showAlert("error",response);
                }
            }
            else
            {
                showAlert("error","Something went wrong. Please try again.");
            }
        }
    };

    var params = "email=" + encodeURIComponent(email.value.trim()) + "&password=" + encodeURIComponent(password.value) + "&login=1";

    xhr.send(params);

    return false;
}

/*
==========================================
Validate Field
==========================================
*/

function validateField(field, errorId, condition)
{
    var errorEl = document.getElementById(errorId);

    if(!condition)
    {
        field.classList.add("is-invalid-tp");
        errorEl.style.display = "block";
        return false;
    }

    field.classList.remove("is-invalid-tp");
    errorEl.style.display = "none";

    return true;
}

/*
==========================================
Email Validation
==========================================
*/

function isValidEmail(value)
{
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return pattern.test(value);
}

/*
==========================================
Show Alert
==========================================
*/

function showAlert(type, text)
{
    var alertBox = document.getElementById("loginAlert");

    alertBox.className = "form-alert " + type;
    alertBox.textContent = text;
    alertBox.style.display = "block";
}
</script>

</body>
</html>
