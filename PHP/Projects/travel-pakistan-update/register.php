<?php
include "validation/register-validation.php";

$page_title = "Register";
$active_page = "";
include "includes/header.php";
?>
<section class="section" style="padding-top:64px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <div class="eyebrow justify-content-center">Join Travel Pakistan</div>
                        <h1 style="font-size:1.9rem;">Create Your Account</h1>
                        <p class="mb-0">Your account will be reviewed by an admin before you can log in.</p>
                    </div>

                    <div id="registerAlert" class="form-alert"></div>

                        <form id="registerForm" class="form-tp" method="POST" enctype="multipart/form-data" onsubmit="return validateRegisterForm();">
                            <div class="row g-3">

                                <!-- First Name -->
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" id="reg-first-name" name="first_name" value="<?php if(isset($first_name)) echo $first_name; ?>">
                                    <span
                                        id="reg-first-name-msg"
                                        class="text-danger small">
                                        <?php if(isset($first_name_msg)) echo $first_name_msg; ?>
                                    </span>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input  type="text" class="form-control" id="reg-last-name" name="last_name" value="<?php if(isset($last_name)) echo $last_name; ?>">
                                    <span
                                        id="reg-last-name-msg"
                                        class="text-danger small">
                                        <?php if(isset($last_name_msg)) echo $last_name_msg; ?>
                                    </span>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="reg-email" name="email" value="<?php if(isset($email)) echo $email; ?>">
                                    <span
                                        id="reg-email-msg"
                                        class="text-danger small">
                                        <?php if(isset($email_msg)) echo $email_msg; ?>
                                    </span>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="reg-password" name="password">
                                    <span
                                        id="reg-password-msg"
                                        class="text-danger small">
                                        <?php if(isset($password_msg)) echo $password_msg; ?>
                                    </span>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label>Gender</label>
                                    <select class="form-select" id="reg-gender" name="gender">
                                        <option value="">Select Gender</option>

                                        <option
                                            value="Male"
                                            <?php if(isset($gender) && $gender=="Male") echo "selected"; ?>>
                                            Male
                                        </option>

                                        <option
                                            value="Female"
                                            <?php if(isset($gender) && $gender=="Female") echo "selected"; ?>>
                                            Female
                                        </option>

                                    </select>

                                    <span
                                        id="reg-gender-msg"
                                        class="text-danger small">
                                        <?php if(isset($gender_msg)) echo $gender_msg; ?>
                                    </span>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" id="reg-dob" name="date_of_birth" value="<?php if(isset($date_of_birth)) echo $date_of_birth; ?>">
                                    <span
                                        id="reg-dob-msg"
                                        class="text-danger small">
                                        <?php if(isset($date_of_birth_msg)) echo $date_of_birth_msg; ?>
                                    </span>
                                </div>

                                <!-- Profile Image -->
                                <div class="col-md-6">
                                    <label>User Image</label>
                                    <input type="file" class="form-control" id="reg-image" name="user_image" accept="image/*">
                                    <span
                                        id="reg-image-msg"
                                        class="text-danger small">
                                        <?php if(isset($user_image_msg)) echo $user_image_msg; ?>
                                    </span>
                                </div>

                                <!-- Address -->
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input type="text" class="form-control"  id="reg-address" name="address" value="<?php if(isset($address)) echo $address; ?>">
                                    <span
                                        id="reg-address-msg"
                                        class="text-danger small">
                                        <?php if(isset($address_msg)) echo $address_msg; ?>
                                    </span>
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="reg-terms" name="terms" value="1" <?php if(isset($_POST["terms"])) echo "checked"; ?>>

                                        <label class="form-check-label" for="reg-terms">
                                            I accept the Terms &amp; Conditions
                                        </label>
                                    </div>
                                    <span
                                        id="reg-terms-msg"
                                        class="text-danger small">
                                        <?php if(isset($terms_msg)) echo $terms_msg; ?>
                                    </span>
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <button type="submit"  name="register" class="btn btn-teal w-100" id="registerSubmitBtn"> Create Account </button>
                                </div>

                            </div>
                        </form>

                    <p class="text-center mt-4 mb-0" style="font-size:.92rem;">Already have an account? <a href="login.php" style="color:var(--teal);font-weight:600;">Log In</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hidden static PDF link used to trigger the "registration summary" download.
     Later: process.php will generate this PDF server-side (e.g. with FPDF/TCPDF)
     and return its path in the AJAX response instead of this static file. -->
<a href="assets/files/sample.pdf" id="downloadRegistrationPdf" download style="display:none;"></a>

<?php include "includes/footer.php"; ?>
<script>

// ===========================client side validation==============================================
   
function validateRegisterForm()
{
    
    // =======================Clear Previous Error Messages==================
    
    document.getElementById("reg-first-name-msg").innerHTML = "";
    document.getElementById("reg-last-name-msg").innerHTML = "";
    document.getElementById("reg-email-msg").innerHTML = "";
    document.getElementById("reg-password-msg").innerHTML = "";
    document.getElementById("reg-gender-msg").innerHTML = "";
    document.getElementById("reg-dob-msg").innerHTML = "";
    document.getElementById("reg-address-msg").innerHTML = "";
    document.getElementById("reg-image-msg").innerHTML = "";
    document.getElementById("reg-terms-msg").innerHTML = "";

    
    // =============Get Form Values============================
   
    var first_name = document.getElementById("reg-first-name").value.trim();

    var last_name = document.getElementById("reg-last-name").value.trim();

    var email = document.getElementById("reg-email").value.trim();

    var password = document.getElementById("reg-password").value;

    var gender = document.getElementById("reg-gender").value;

    var date_of_birth = document.getElementById("reg-dob").value;

    var address = document.getElementById("reg-address").value.trim();

    var image = document.getElementById("reg-image").value;


    // =================Regular Expressions========================

    var nameRegex = /^[A-Za-z\s]+$/;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;


    // ===============Values Validation==========================

    if(first_name == "")
    {
        document.getElementById("reg-first-name-msg").innerHTML =
        "First Name is Required.";

        return false;
    }

    if(!nameRegex.test(first_name))
    {
        document.getElementById("reg-first-name-msg").innerHTML =
        "Example: Ali";

        return false;
    }


    if(last_name == "")
    {
        document.getElementById("reg-last-name-msg").innerHTML =
        "Last Name is Required.";

        return false;
    }

    if(!nameRegex.test(last_name))
    {
        document.getElementById("reg-last-name-msg").innerHTML =
        "Example: Khan";

        return false;
    }


    if(email == "")
    {
        document.getElementById("reg-email-msg").innerHTML =
        "Email is Required.";

        return false;
    }

    if(!emailRegex.test(email))
    {
        document.getElementById("reg-email-msg").innerHTML =
        "Example: abc@example.com";

        return false;
    }


    if(password == "")
    {
        document.getElementById("reg-password-msg").innerHTML =
        "Password is Required.";

        return false;
    }

    if(!passwordRegex.test(password))
    {
        document.getElementById("reg-password-msg").innerHTML =
        "Minimum 8 characters with letters and numbers.";

        return false;
    }


    if(gender == "")
    {
        document.getElementById("reg-gender-msg").innerHTML =
        "Please Select Gender.";

        return false;
    }


    if(date_of_birth == "")
    {
        document.getElementById("reg-dob-msg").innerHTML =
        "Date of Birth is Required.";

        return false;
    }

    if(address == "")
    {
        document.getElementById("reg-address-msg").innerHTML =
        "Address is Required.";

        return false;
    }


    if(image == "")
    {
        document.getElementById("reg-image-msg").innerHTML =
        "Please Select Profile Image.";

        return false;
    }

    
    if(!document.getElementById("reg-terms").checked)
    {
        document.getElementById("reg-terms-msg").innerHTML =
        "Please Accept Terms & Conditions.";

        return false;
    }

    return true;

}
</script>
</body>
</html>
