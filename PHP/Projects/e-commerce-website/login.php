<?php
$base = '';
require_once 'includes/init.php';
$pageTitle = 'Login';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    $user = $userModel->findByEmail($email);

    if ($user && $userModel->verifyPassword($password, $user['password'])) {
        $userModel->login($user);

        // ---- "Remember Me" cookie (Topic 20: Cookies) ----
        if ($rememberMe) {
            $token = bin2hex(random_bytes(32));
            $userModel->saveRememberToken($user['id'], $token);
            setRememberCookie($token);
        }

        setFlash('success', 'Welcome back, ' . $user['full_name'] . '!');

        // Send them back to whichever page they tried to visit before (Topic 7: Sessions)
        if (!empty($_SESSION['redirect_after_login'])) {
            $redirectTo = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirectTo);
            exit;
        }

        header('Location: ' . ($user['role'] === 'admin' ? 'admin/dashboard.php' : 'index.php'));
        exit;
    } else {
        $errors[] = "Invalid email or password.";
    }
}

include 'includes/header.php';
?>

<div class="form-box">
    <div class="text-center mb-20">
        <span class="cat-icon" style="display:inline-flex; width:60px; height:60px; font-size:26px;"><i class='bx bx-log-in'></i></span>
    </div>
    <h2 class="text-center">Login to Your Account</h2>
    <p class="form-sub text-center">Welcome back — enter your details below</p>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-error"><i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group checkbox-line">
            <input type="checkbox" name="remember_me" id="remember_me">
            <label for="remember_me" style="margin:0;">Remember me for 30 days</label>
        </div>
        <button type="submit" class="btn btn-full">Login</button>
    </form>
    <p class="mt-20 small-text text-center">Don't have an account? <a href="register.php">Register here</a></p>
    <p class="small-text text-center">Admin login: admin@simpleshop.com (see README for password reset)</p>
</div>

<?php include 'includes/footer.php'; ?>
