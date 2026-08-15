<?php
$base = '';
require_once 'includes/init.php';
requireLogin();
$pageTitle = 'My Orders';

$orders = $orderModel->getUserOrders($_SESSION['user_id']);

include 'includes/header.php';
?>

<h1 class="page-title">My Orders</h1>

<?php if (count($orders) === 0): ?>
    <div class="empty-state">
        <i class='bx bx-package'></i>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders yet.</p>
        <a href="products.php" class="btn">Start Shopping</a>
    </div>
<?php else: ?>
    <div class="table-wrap mt-20">
        <table>
            <tr>
                <th>Tracking Code</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th></th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($order['tracking_code']); ?></strong></td>
                    <td><?php echo formatDate($order['created_at']); ?></td>
                    <td><?php echo money($order['total_amount']); ?></td>
                    <td><?php echo strtoupper($order['payment_method']); ?></td>
                    <td><span class="status-badge status-<?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></span></td>
                    <td><a href="order-tracking.php?code=<?php echo $order['tracking_code']; ?>" class="btn btn-small">Track</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
