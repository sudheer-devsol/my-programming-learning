<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - SimpleShop' : 'SimpleShop'; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo isset($base) ? $base : ''; ?>assets/css/style.css">
</head>
<body>

<div class="top-bar">
    <div class="container">
        <span><i class='bx bx-truck'></i> Free shipping on orders over $50</span>
        <span><i class='bx bx-headphone'></i> Support: 9am–9pm, every day</span>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
        <a href="<?php echo isset($base) ? $base : ''; ?>index.php" class="logo">
            <i class='bx bxs-store-alt'></i> Simple<span>Shop</span>
        </a>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu"><i class='bx bx-menu'></i></button>

        <nav class="main-nav" id="mainNav">
            <a href="<?php echo isset($base) ? $base : ''; ?>index.php"><i class='bx bx-home-alt'></i> Home</a>
            <a href="<?php echo isset($base) ? $base : ''; ?>products.php"><i class='bx bx-grid-alt'></i> Products</a>
            <a href="<?php echo isset($base) ? $base : ''; ?>contact.php"><i class='bx bx-envelope'></i> Contact</a>
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="<?php echo isset($base) ? $base : ''; ?>admin/dashboard.php"><i class='bx bxs-dashboard'></i> Admin Panel</a>
                <?php endif; ?>
                <a href="<?php echo isset($base) ? $base : ''; ?>order-history.php"><i class='bx bx-package'></i> My Orders</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>order-tracking.php"><i class='bx bx-map-pin'></i> Track Order</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>chat.php"><i class='bx bx-support'></i> Chat Support</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>account.php"><i class='bx bx-user-circle'></i> My Account</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>logout.php"><i class='bx bx-log-out'></i> Logout (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)</a>
            <?php else: ?>
                <a href="<?php echo isset($base) ? $base : ''; ?>login.php"><i class='bx bx-log-in'></i> Login</a>
                <a href="<?php echo isset($base) ? $base : ''; ?>register.php"><i class='bx bx-user-plus'></i> Register</a>
            <?php endif; ?>
            <a href="<?php echo isset($base) ? $base : ''; ?>cart.php" class="cart-link">
                <i class='bx bx-cart'></i> Cart (<span id="cart-count"><?php echo cartCount(); ?></span>)
            </a>
        </nav>
    </div>
</header>

<main class="container">
<?php showFlash(); ?>
