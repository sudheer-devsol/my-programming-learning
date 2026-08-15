<?php
$base = '../';
require_once '../includes/init.php';
requireAdmin();
$pageTitle = 'Admin Dashboard';

$totalProducts = $db->selectOne("SELECT COUNT(*) as c FROM products")['c'];
$totalOrders = $db->selectOne("SELECT COUNT(*) as c FROM orders")['c'];
$totalUsers = $db->selectOne("SELECT COUNT(*) as c FROM users WHERE role = 'customer'")['c'];
$totalRevenue = $db->selectOne("SELECT SUM(total_amount) as t FROM orders WHERE payment_status = 'paid' OR payment_method = 'cod'")['t'];
$unreadChats = $db->selectOne("SELECT COUNT(*) as c FROM chat_messages WHERE sender = 'user' AND is_read = 0")['c'];

include '../includes/header.php';
?>

<h1 class="page-title">Admin Dashboard</h1>
<p class="section-sub" style="margin-left:0;">A quick look at how the store is doing</p>

<div class="product-grid mt-20" style="grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));">
    <div class="stat-card">
        <span class="stat-icon"><i class='bx bx-box'></i></span>
        <div><h3>Total Products</h3><div class="stat-value"><?php echo $totalProducts; ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon alt"><i class='bx bx-receipt'></i></span>
        <div><h3>Total Orders</h3><div class="stat-value"><?php echo $totalOrders; ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><i class='bx bx-group'></i></span>
        <div><h3>Registered Customers</h3><div class="stat-value"><?php echo $totalUsers; ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon alt"><i class='bx bx-dollar-circle'></i></span>
        <div><h3>Total Revenue</h3><div class="stat-value"><?php echo money($totalRevenue ?: 0); ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><i class='bx bx-chat'></i></span>
        <div><h3>Unread Chat Messages</h3><div class="stat-value"><?php echo $unreadChats; ?></div></div>
    </div>
</div>

<div class="mt-20 flex flex-wrap gap-10" style="margin-bottom:20px;">
    <a href="products.php" class="btn"><i class='bx bx-box'></i> Manage Products</a>
    <a href="orders.php" class="btn btn-secondary"><i class='bx bx-receipt'></i> Manage Orders</a>
    <a href="chat.php" class="btn btn-outline"><i class='bx bx-chat'></i> Customer Chat</a>
</div>

<?php include '../includes/footer.php'; ?>
