<?php
$page_title = "Forgot Password";
$active_page = "";
include "includes/header.php";
?>

<section class="section" style="padding-top:64px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <div class="eyebrow justify-content-center">Account Recovery</div>
                        <h1 style="font-size:1.9rem;">Forgot Password</h1>
                        <p class="mb-0">Enter the email on your account and we'll send your login details.</p>
                    </div>

                    <div id="forgotAlert" class="form-alert"></div>

                    <form id="forgotForm" class="form-tp" novalidate onsubmit="return submitForgotPassword();">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" id="forgotEmail" name="email">
                            <div class="field-error" id="err-forgotEmail">Enter a valid email address.</div>
                        </div>
                        <button type="submit" class="btn btn-teal w-100" id="forgotSubmitBtn">Send Login Details</button>
                    </form>

                    <p class="text-center mt-4 mb-0" style="font-size:.92rem;"><a href="login.php" style="color:var(--teal);font-weight:600;"><i class="bi bi-arrow-left"></i> Back to Log In</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "includes/footer.php"; ?>
<script src="ajax/forgot_password_ajax.js"></script>