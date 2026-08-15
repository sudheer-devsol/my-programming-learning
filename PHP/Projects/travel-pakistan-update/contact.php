<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Contact";
$active_page = "contact";

include "includes/header.php";

// Pre-fill name and email if user is logged in
$logged_name  = "";
$logged_email = "";

if (isset($_SESSION['user'])) {
    $first_name   = $_SESSION['user']['first_name'] ?? '';
    $last_name    = $_SESSION['user']['last_name'] ?? '';
    $logged_name  = trim($first_name . ' ' . $last_name);
    $logged_email = $_SESSION['user']['email'] ?? $_SESSION['user']['user_email'] ?? '';
}
?>

<section class="section-tight" style="background:var(--paper-raised);border-bottom:1px solid var(--line);">
    <div class="container">
        <div class="eyebrow">Get In Touch</div>
        <h1 style="font-size:2.4rem;">Contact &amp; Feedback</h1>
        <p class="mb-0" style="max-width:600px;">Spotted an outdated route, a closed viewpoint, or just want to say hello? This goes straight to our team.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">

                <div class="panel-tp">
                    <div class="panel-body form-tp">
                        <div id="feedbackAlert" class="form-alert" style="display:none;"></div>

                        <form id="feedbackForm" novalidate onsubmit="return submitFeedback();">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label>Your Name</label>
                                    <input type="text" class="form-control" id="fbName" name="user_name" value="<?php echo htmlspecialchars($logged_name); ?>">
                                    <div class="field-error" id="err-fbName" style="display:none;">Name is required.</div>
                                </div>

                                <div class="col-md-6">
                                    <label>Your Email</label>
                                    <input type="email" class="form-control" id="fbEmail" name="user_email" value="<?php echo htmlspecialchars($logged_email); ?>">
                                    <div class="field-error" id="err-fbEmail" style="display:none;">Enter a valid email.</div>
                                </div>

                                <div class="col-12">
                                    <label>Message</label>
                                    <textarea class="form-control" id="fbMessage" name="feedback" rows="5"></textarea>
                                    <div class="field-error" id="err-fbMessage" style="display:none;">Please write a message.</div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-teal" id="fbSubmitBtn">
                                        Send Feedback
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>

<script>
/*
==========================================
Contact Page - Validation + AJAX
==========================================
*/

function createXHR() {
    var xhr = null;

    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    return xhr;
}

function submitFeedback() {
    var name = document.getElementById("fbName");
    var email = document.getElementById("fbEmail");
    var message = document.getElementById("fbMessage");
    var submitBtn = document.getElementById("fbSubmitBtn");

    var valid = true;
    valid = validateField(name, "err-fbName", name.value.trim() != "") && valid;
    valid = validateField(email, "err-fbEmail", isValidEmail(email.value.trim())) && valid;
    valid = validateField(message, "err-fbMessage", message.value.trim() != "") && valid;

    if (!valid) {
        return false;
    }

    var xhr = createXHR();
    xhr.open("POST", "process/feedback_process.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    submitBtn.disabled = true;
    submitBtn.textContent = "Sending...";

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            submitBtn.disabled = false;
            submitBtn.textContent = "Send Feedback";

            if (xhr.status == 200) {
                if (xhr.responseText == "success") {
                    showAlert("success", "Thanks - your feedback has been sent successfully.");
                    message.value = "";
                } else {
                    showAlert("error", xhr.responseText);
                }
            } else {
                showAlert("error", "Server connection error. Please try again.");
            }
        }
    };

    var params = "user_name=" + encodeURIComponent(name.value.trim()) +
        "&user_email=" + encodeURIComponent(email.value.trim()) +
        "&feedback=" + encodeURIComponent(message.value.trim()) +
        "&submit_feedback=1";

    xhr.send(params);

    return false;
}

function validateField(field, errorId, condition) {
    var errorEl = document.getElementById(errorId);

    if (!condition) {
        field.classList.add("is-invalid-tp");
        if (errorEl) {
            errorEl.style.display = "block";
        }
        return false;
    }

    field.classList.remove("is-invalid-tp");
    if (errorEl) {
        errorEl.style.display = "none";
    }
    return true;
}

function isValidEmail(value) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(value);
}

function showAlert(type, text) {
    var alertBox = document.getElementById("feedbackAlert");

    alertBox.className = "form-alert " + (type == "success" ? "alert-success" : "alert-danger");
    alertBox.textContent = text;
    alertBox.style.display = "block";
}
</script>

</body>
</html>