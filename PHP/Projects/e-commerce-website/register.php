<?php
$base = '';
require_once 'includes/init.php';
$pageTitle = 'Register';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = clean($_POST['full_name']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $city = clean($_POST['city']);

    // ---- Server-side validation (Topic 13: Form Validation) ----
    if (empty($fullName)) $errors[] = "Full name is required.";
    if (!isValidEmail($email)) $errors[] = "Please enter a valid email address.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirmPassword) $errors[] = "Passwords do not match.";
    if ($userModel->findByEmail($email)) $errors[] = "This email is already registered.";

    if (count($errors) === 0) {
        $newUserId = $userModel->register($fullName, $email, $password, $phone, $address, $city);
        if ($newUserId) {
            $user = $userModel->findById($newUserId);
            $userModel->login($user);
            setFlash('success', 'Welcome to SimpleShop, ' . $fullName . '!');
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}

include 'includes/header.php';
?>

<div class="form-box" style="max-width:560px;">
    <div class="text-center mb-20">
        <span class="cat-icon" style="display:inline-flex; width:60px; height:60px; font-size:26px;"><i class='bx bx-user-plus'></i></span>
    </div>
    <h2 class="text-center">Create an Account</h2>
    <p class="form-sub text-center">Join SimpleShop and start shopping in minutes</p>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>
    <p id="js-error" class="form-error"></p>

    <form method="POST" id="registerForm">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>
        <div class="input-row">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
        </div>
        <div class="form-group">
            <label>City</label>
            <input type="text" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-full">Register</button>
    </form>
    <p class="mt-20 small-text text-center">Already have an account? <a href="login.php">Login here</a></p>
</div>

<?php include 'includes/footer.php'; ?>
