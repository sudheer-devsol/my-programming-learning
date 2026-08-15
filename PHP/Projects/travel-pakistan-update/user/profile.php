<?php
// =========Include User Session=================
include "../includes/user-session.php";

// =============Database Connection==============================
include "../config/database.php";

// ===================Page Information========================
$page_title = "My Profile";
$dash_role  = "user";
$active     = "profile";

// ======================Get Current User Data=====================
$user_id = $_SESSION['user_id'];

$query = "SELECT first_name, last_name, email, gender, date_of_birth, user_image, address 
FROM user WHERE user_id = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Default Image
if (empty($user['user_image'])) {
    $profile_image = "../assets/images/users/default.png";
} else {
    $profile_image = "../assets/images/users/" . $user['user_image'];
}

// ===========================================
// Header
// ===========================================
include "../includes/dash-header.php";
?>
<div class="dash-shell">
    <?php include "../includes/user-sidebar.php"; ?>

    <main class="dash-main">
        <div class="dash-topbar">
            <div>
                <h2 class="mb-1" style="font-size:1.6rem;">
                    <?php echo $user['first_name'] . " " . $user['last_name']; ?>'s Profile
                </h2>
                <p class="mb-0">
                    Update your details. Your email address can't be changed.
                </p>
                <?php if(isset($_GET["mesg"])){ ?>
                    <div class="form-alert" style="display:block;"><?= htmlspecialchars($_GET["mesg"]); ?></div>
                <?php } ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="panel-tp p-4 text-center">
                    <img id="profilePreview" src="<?php echo !empty($user['user_image']) ? "../assets/images/users/" . $user['user_image'] : "../assets/images/users/default.png"; ?>" style="width:120px; height:120px; border-radius:50%; object-fit:cover; margin:0 auto 16px;">
                    <h3 style="font-size:1.2rem;" id="profileName">
                        <?php echo $user['first_name'] . " " . $user['last_name']; ?>
                    </h3>
                    <div class="post-meta justify-content-center mb-3">
                        <?php echo $user['email']; ?>
                    </div>
                    <label class="btn btn-outline-teal btn-sm">
                        Change Photo
                        <input type="file" id="profileImageInput" name="user_image" form="profileForm" accept="image/*" hidden onchange="previewProfileImage(this);">
                    </label>
                </div>
            </div>

            <!-- PROFILE UPDATE FORM -->
            <div class="col-lg-8">
                <div class="panel-tp">
                    <div class="panel-body form-tp">
                        <div id="profileAlert" class="form-alert"></div>
                        <form id="profileForm" method="POST" enctype="multipart/form-data" action="../process/user_profile_process.php" onsubmit="return validateProfileForm();">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label> First Name </label>
                                    <input type="text" class="form-control" id="pFirstName" name="first_name" value="<?php echo $user['first_name']; ?>">
                                    <div class="field-error" id="err-pFirstName">
                                        First name is required.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label> Last Name </label>
                                    <input type="text" class="form-control" id="pLastName" name="last_name" value="<?php echo $user['last_name']; ?>">
                                    <div class="field-error" id="err-pLastName">
                                        Last name is required.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>
                                        Email 
                                        <span style="text-transform:none; font-weight:400;">
                                            (cannot be changed)
                                        </span>
                                    </label>
                                    <input type="email" class="form-control" id="pEmail" value="<?php echo $user['email']; ?>" disabled>
                                </div>

                                <div class="col-md-6">
                                    <label> Gender </label>
                                    <select class="form-select" id="pGender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo ($user['gender'] == "Male") ? "selected" : ""; ?>>Male</option>
                                        <option value="Female" <?php echo ($user['gender'] == "Female") ? "selected" : ""; ?>>Female</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label> Date of Birth </label>
                                    <input type="date" class="form-control" id="pDob" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label> Address </label>
                                    <input type="text" class="form-control" id="pAddress" name="address" value="<?php echo $user['address']; ?>">
                                    <div class="field-error" id="err-pAddress">
                                        Address is required.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-teal" id="profileSubmitBtn">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../ajax/user_profile_ajax.js"></script>
</body>
</html>