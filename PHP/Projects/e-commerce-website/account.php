<?php
$base = '';
require_once 'includes/init.php';
requireLogin();
$pageTitle = 'My Account';

$user = $userModel->findById($_SESSION['user_id']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = clean($_POST['full_name']);
    $phone = clean($_POST['phone']);
    $address = clean($_POST['address']);
    $city = clean($_POST['city']);

    if (empty($fullName)) {
        $errors[] = "Full name cannot be empty.";
    } else {
        $userModel->updateProfile($user['id'], $fullName, $phone, $address, $city);
        $_SESSION['user_name'] = $fullName;
        setFlash('success', 'Profile updated successfully.');
        header('Location: account.php');
        exit;
    }
    $user = array_merge($user, $_POST); // keep the form filled in if there's an error
}

include 'includes/header.php';
?>

<h1 class="page-title">My Account</h1>

<div class="grid-2 mt-20">
    <div class="card">
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-error"><i class='bx bx-error-circle'></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
            </div>
            <div class="form-group">
                <label>Email (cannot be changed)</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
            </div>
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($user['city']); ?>">
            </div>
            <button type="submit" class="btn btn-full">Save Changes</button>
        </form>
    </div>

    <div class="card" style="text-align:center;">
        <span class="cat-icon" style="display:inline-flex; width:80px; height:80px; font-size:36px; margin-bottom:14px;">
            <i class='bx bx-user'></i>
        </span>
        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
        <p class="small-text mt-20"><?php echo htmlspecialchars($user['email']); ?></p>
        <hr class="mt-20" style="margin-bottom:20px;">
        <div class="flex" style="flex-direction:column; gap:10px;">
            <a href="order-history.php" class="btn btn-chip"><i class='bx bx-package'></i> My Orders</a>
            <a href="order-tracking.php" class="btn btn-chip"><i class='bx bx-map-pin'></i> Track an Order</a>
            <a href="chat.php" class="btn btn-chip"><i class='bx bx-support'></i> Chat Support</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
